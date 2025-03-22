<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proyecto;
use App\Informe;
use App\PropiedadesGrafico;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Barryvdh\Snappy\Snappy as PDF;
use Validator;

class InformesController extends Controller
{
    public function vistaListaInformes(Proyecto $proyecto)
    {
        $lista_informes = $proyecto->informes->sortByDesc('created_at')->groupBy(function ($item) {
            return $item->fecha->format('d-M-y');
        });
        $propiedades = session('propiedades_grafico_cache');
        return view('informes.index', compact('proyecto', 'lista_informes', 'propiedades'));
    }

    public function vistaListaInformesArchivados($id)
    {
        $proyecto = Proyecto::withTrashed()
            ->where('id', $id)
            ->get()
            ->first();
        $informes = $proyecto->informes()->withTrashed()->get()->sortByDesc('created_at');
        $propiedades = session('propiedades_grafico_cache');
        return view('informes.index', compact('proyecto', 'informes', 'propiedades'));
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
            $arrayColores = session('propiedades_grafico_cache')->whereNotIn('id', 6)->pluck('color');
            $arrayConfiguraciones = compact('incluye_grafico', 'incluye_observaciones');
            $proyecto = Proyecto::find($proyecto->id);
            $tareas = $proyecto->tareas()->get();
        }

        $tareas = $tareas->sortBy(function ($tarea) {
            return [$tarea->fecha_inicio, $tarea->fecha_termino];
        })->values()->all();
        $tareasJSON = json_encode($tareas);
        $propiedades = session('propiedades_grafico_cache');
        DB::beginTransaction();
        try {
            $pdf = \PDF::loadView('pdf', compact('proyecto', 'tareas', 'tareasJSON', 'arrayConfiguraciones', 'propiedades'));
            $pdf->setOption('enable-local-file-access', true);
            $pdf->setOption('encoding', 'UTF-8');
            $pdf->setOption('enable-javascript', true);
            $pdf->setOption('images', true);
            $pdf->setOption('javascript-delay', 2000);
            $informe = new Informe;
            $informe->fecha = Carbon::now();
            $informe->grafico = $incluye_grafico;
            $informe->observaciones = $incluye_observaciones;
            $informe->colores = json_encode($arrayColores, JSON_FORCE_OBJECT);
            $informe->ruta = 'public/' . $proyecto->nombre . ' - ' . $informe->fecha->format('d-M-Y') . '-' . $informe->fecha->format('H.i.s') . '.pdf';
            //$rutaCompleta = storage_path().'/'.$proyecto->nombre.' - '.$informe->fecha->format('d-M-Y').'.pdf';
            $informe->proyecto()->associate($proyecto);
            $informe->save();
            Storage::disk('local')->put($informe->ruta, $pdf->output());
            DB::commit();
            flash('Informe generado')->success();
        } catch (\RuntimeException $e) {
            flash('Error al generar informe')->error();
            DB::rollBack();
        }
        return redirect()->action(
            'InformesController@vistaListaInformes',
            ['proyecto' => $proyecto]
        );
    }

    public function destroy($id)
    {
        $informe = Informe::find($id);
        $proyecto = $informe->proyecto()->get()->first();
        Storage::delete($informe->ruta);
        $informe->forceDelete();
        flash('Informe eliminado')->success();
        return redirect()->action(
            'InformesController@vistaListaInformes',
            ['proyecto' => $proyecto]
        );
    }

    public function test()
    {
        $id = 1;
        $incluye_grafico = true;
        $incluye_observaciones = true;
        $arrayConfiguraciones = compact('incluye_grafico', 'incluye_observaciones');
        $proyecto = Proyecto::find($id);
        $tareas = $proyecto->tareas()->get();
        $tareasJSON = $tareas->sortBy(function ($tarea) {
            return [$tarea->fecha_inicio, $tarea->fecha_termino];
        })->values()->all();
        $tareasJSON = json_encode($tareasJSON);
        $propiedades = session('propiedades_grafico_cache');
        return view('pdf', compact('proyecto', 'tareas', 'tareasJSON', 'propiedades', 'arrayConfiguraciones'));
    }
}
