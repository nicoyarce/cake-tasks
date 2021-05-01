<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tarea;
use App\Area;
use App\Proyecto;
use App\PropiedadesGrafico;

class GraficosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function vistaGrafico(Proyecto $proyecto)
    {
        $areas = Area::all();
        $tareas = Proyecto::find($proyecto->id)->tareas
            ->sortBy(function ($tarea) {
                return [$tarea->fecha_inicio, $tarea->fecha_termino];
            })->values()->all();
        $propiedades = PropiedadesGrafico::all();
        //dd($tareas);
        //$tareas = $tareas->makeHidden('created_at');
        //$tareas = $tareas->makeHidden('updated_at');
        $tareas = json_encode($tareas);
        //dd($tareas);
        return view('grafico', compact('proyecto', 'areas', 'tareas', 'propiedades'));
    }

    public function vistaGraficoArchivados($id)
    {
        $areas = Area::all();
        $proyecto = Proyecto::withTrashed()
            ->where('id', $id)
            ->get()
            ->first();
        $tareas = $proyecto->tareas()->withTrashed()->get()
            ->sortBy(function ($tarea) {
                return [$tarea->fecha_inicio, $tarea->fecha_termino];
            })->values()->all();
        $propiedades = PropiedadesGrafico::all();
        //dd($tareas);
        //$tareas = $tareas->makeHidden('created_at');
        //$tareas = $tareas->makeHidden('updated_at');
        $tareas = json_encode($tareas);
        //dd($tareas);
        return view('grafico', compact('proyecto', 'areas', 'tareas', 'propiedades'));
    }

    public function filtrar(Request $request)
    {
        $proyectoid = $request->proyectoid;
        $areaid = $request->areaid;
        $opcionColor = $request->colorAtraso;
        $proyecto = Proyecto::find($proyectoid);
        $propiedades = PropiedadesGrafico::all();
        if ($areaid == 0 && $opcionColor == "TODAS") {
            $tareas = $proyecto->tareas
                ->sortBy(function ($tarea) {
                    return [$tarea->fecha_inicio, $tarea->fecha_termino];
                })->values()->all();
        } elseif ($areaid != 0 && $opcionColor == "TODAS") {
            $tareas = $proyecto->tareas
                ->where('area_id', $areaid)
                ->sortBy(function ($tarea) {
                    return [$tarea->fecha_inicio, $tarea->fecha_termino];
                })->values()->all();
        } elseif ($areaid == 0 && $opcionColor != "TODAS") {
            $tareas = $proyecto->tareas
                ->where('colorAtraso', $opcionColor)
                ->where('avance', '<', 100)
                ->sortBy(function ($tarea) {
                    return [$tarea->fecha_inicio, $tarea->fecha_termino];
                })->values()->all();
        } else {
            $tareas = $proyecto->tareas
                ->where('area_id', $areaid)
                ->where('colorAtraso', $opcionColor)
                ->where('avance', '<', 100)
                ->sortBy(function ($tarea) {
                    return [$tarea->fecha_inicio, $tarea->fecha_termino];
                })->values()->all();
        }
        $tareas = json_encode($tareas);
        return $tareas;
    }
}
