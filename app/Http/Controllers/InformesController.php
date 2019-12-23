<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proyecto;
use App\Informe;
use App\PropiedadesGrafico;
use Jenssegers\Date\Date;
use Illuminate\Support\Facades\Storage;
use Barryvdh\Snappy\Snappy as PDF;
use Validator;

class InformesController extends Controller
{
    public function vistaListaInformes(Proyecto $proyecto)
    {
        $informes = $proyecto->informes->sortByDesc('created_at');
        return view('informes.index', compact('proyecto', 'informes'));
    }

    public function vistaListaInformesArchivados($id)
    {
        $proyecto = Proyecto::withTrashed()
            ->where('id', $id)
            ->get()
            ->first();
        $informes = $proyecto->informes()->withTrashed()->get()->sortByDesc('created_at');
        return view('informes.index', compact('proyecto', 'informes'));
    }

    public function generarInforme(Proyecto $proyecto, Request $request)
    {
        if ($request->has('grafico') || $request->has('observaciones') || $request->has('incluye_tareas')) {
            $incluye_grafico = ($request->has('grafico')) ? true : false;
            $incluye_observaciones = ($request->has('observaciones')) ? true : false;
            $arrayConfiguraciones = compact('incluye_grafico', 'incluye_observaciones');
            $arrayColores = [];
            if ($request->has('incluye_tareas')) {
                foreach ($request->incluye_tareas as $seleccion) {
                    $propiedad = PropiedadesGrafico::find($seleccion);
                    if ($propiedad->id == $seleccion) {
                        array_push($arrayColores, $propiedad->color);
                    }
                }
            }
            $tareas = $proyecto->tareas()->get()->whereIn('colorAtraso', $arrayColores);
            //dd($tareas);
        } else {
            $incluye_grafico = true;
            $incluye_observaciones = true;
            $arrayColores = PropiedadesGrafico::all()->whereNotIn('id', 6)->pluck('color');
            $arrayConfiguraciones = compact('incluye_grafico', 'incluye_observaciones');
            $proyecto = Proyecto::find($proyecto->id);
            $tareas = $proyecto->tareas()->get();
        }
        
        $tareas = $tareas->sortBy(function ($tarea) {
            return [$tarea->fecha_inicio, $tarea->fecha_termino];
        })->values()->all();
        $tareasJSON = json_encode($tareas);
        //dd($tareasJSON);
        $pdf = \PDF::loadView('pdf', compact('proyecto', 'tareas', 'tareasJSON', 'arrayConfiguraciones'));
        $pdf->setOption('encoding', 'UTF-8');
        $pdf->setOption('javascript-delay', 2000);
        $informe = new Informe;
        $informe->fecha = Date::now();
        $informe->grafico = $incluye_grafico;
        $informe->observaciones = $incluye_observaciones;
        $informe->colores = json_encode($arrayColores, JSON_FORCE_OBJECT);
        $informe->ruta = 'public/' . $proyecto->nombre . ' - ' . $informe->fecha->format('d-M-Y') . '-' . $informe->fecha->format('H.i.s') . '.pdf';
        //$rutaCompleta = storage_path().'/'.$proyecto->nombre.' - '.$informe->fecha->format('d-M-Y').'.pdf';
        $informe->proyecto()->associate($proyecto);
        $informe->save();
        Storage::disk('local')->put($informe->ruta, $pdf->output());
        flash('Informe generado')->success();
        return redirect()->action(
            'InformesController@vistaListaInformes',
            ['id' => $proyecto->id]
        );
    }

    public function destroy($id)
    {
        $informe = Informe::find($id);
        $proyectoId = $informe->proyecto()->get()->first()->id;
        Storage::delete($informe->ruta);
        $informe->forceDelete();
        flash('Informe eliminado')->success();
        return redirect()->action(
            'InformesController@vistaListaInformes',
            ['id' => $proyectoId]
        );
    }

    public function test()
    {
        $id = 1;
        $proyecto = Proyecto::find($id);
        $tareas = $proyecto->tareas()->get();
        $tareasJSON = $tareas->sortBy(function ($tarea) {
            return [$tarea->fecha_inicio, $tarea->fecha_termino];
        })->values()->all();
        $tareasJSON = json_encode($tareasJSON);
        return view('pdf', compact('proyecto', 'tareas', 'tareasJSON'));
    }
}
