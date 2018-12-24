<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proyecto;
use App\Informe;
use Jenssegers\Date\Date;
use Illuminate\Support\Facades\Storage;
use Barryvdh\Snappy\Snappy as PDF;

class InformesController extends Controller
{
    public function vistaListaInformes(Proyecto $proyecto){
        $informes = $proyecto->informes->sortByDesc('created_at');
        return view('informes.index', compact('proyecto','informes'));
    }

    public function vistaListaInformesArchivados($id){
        $proyecto = Proyecto::withTrashed()
            ->where('id', $id)
            ->get()
            ->first();
        $informes = $proyecto->informes()->withTrashed()->get()->sortByDesc('created_at');
        return view('informes.index', compact('proyecto','informes'));
    }
  
    public function generarInformeManual(Proyecto $proyecto){
        $proyecto = Proyecto::find($proyecto->id);        
        $tareas = $proyecto->tareas()->get();
        $tareas = $tareas->sortBy(function($tarea) {
                        return [$tarea->fecha_inicio, $tarea->fecha_termino];
                    })->values()->all();
        $tareasJSON = json_encode($tareas);
        //dd($tareasJSON);
        $pdf = \PDF::loadView('pdf', compact('proyecto', 'tareas', 'tareasJSON'));
        $pdf->setOption('encoding', 'UTF-8');
        $pdf->setOption('javascript-delay', 2000);
        $informe = new Informe;
        $informe->fecha = Date::now();        
        $informe->ruta = 'public/'.$proyecto->nombre.' - '.$informe->fecha->format('d-M-Y').'-'.$informe->fecha->format('H.i.s').'.pdf';
        //$rutaCompleta = storage_path().'/'.$proyecto->nombre.' - '.$informe->fecha->format('d-M-Y').'.pdf';
        //dd($informe->ruta);
        $informe->proyecto()->associate($proyecto);
        $informe->save();
        Storage::disk('local')->put($informe->ruta, $pdf->output());
        flash('Informe generado')->success(); 
        return redirect()->action(
            'InformesController@vistaListaInformes', ['id' => $proyecto->id]
        );
    }

    public function destroy($id){
        $informe = Informe::find($id);        
        $proyectoId = $informe->proyecto()->get()->first()->id;        
        Storage::delete($informe->ruta);
        $informe->forceDelete();
        flash('Informe eliminado')->success(); 
        return redirect()->action(
            'InformesController@vistaListaInformes', ['id' => $proyectoId]
        );
    }

    // public function test(){
    //     $id = 1;
    //     $proyecto = Proyecto::find($id);        
    //     $tareas = $proyecto->tareas()->get();
    //     $tareasJSON = $tareas->sortBy(function($tarea) {
    //                     return [$tarea->fecha_inicio, $tarea->fecha_termino];
    //                 })->values()->all();
    //     $tareasJSON = json_encode($tareasJSON);
    //     return view('pdf', compact('proyecto', 'tareas', 'tareasJSON'));
    // }
    
}
