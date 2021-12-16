<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tarea;
use App\Area;
use App\Proyecto;
use App\PropiedadesGrafico;
use App\Categoria;

class GraficosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function vistaGrafico(Proyecto $proyecto)
    {
        $areas = Area::all();
        $categorias = Categoria::all();
        $opcionColor = null;
        $areaid = null;
        $categoriaid = null;
        $proyecto =  Proyecto::with('tareas')->withCount('tareas')->find($proyecto->id);
        $tareas = $proyecto->tareas;
        $promedio_avances_tareas = 0;
        foreach ($tareas as $key => $tarea) {
            $promedio_avances_tareas += $tarea->avance;
        }
        $promedio_avances_tareas = round($promedio_avances_tareas/$proyecto->tareas_count, 2);
        $tareas = $tareas
        ->when($opcionColor != null, function ($query) use ($opcionColor) {
            return $query->where('colorAtraso', $opcionColor);
        })->when($areaid != null, function ($query) use ($areaid) {
            return $query->where('area_id', $areaid);
        })->when($categoriaid != null, function ($query) use ($categoriaid) {
            return $query->whereIn('categoria_id', $categoriaid);
        })->sortBy(function ($tarea) {
            return [$tarea->fecha_inicio, $tarea->fecha_termino];
        })->values()->all();
        $tareas = json_encode($tareas);
        return view('grafico', compact('proyecto', 'areas', 'tareas', 'categorias', 'promedio_avances_tareas'));
    }

    public function vistaGraficoArchivados($id)
    {
        $areas = Area::all();
        $categorias = Categoria::all();
        $proyecto = Proyecto::withTrashed()
            ->where('id', $id)
            ->get()
            ->first();
        $tareas = $proyecto->tareas()->withTrashed()->get()
            ->sortBy(function ($tarea) {
                return [$tarea->fecha_inicio, $tarea->fecha_termino];
            })->values()->all();
        $promedio_avances_tareas = 0;
        foreach ($tareas as $key => $tarea) {
            $promedio_avances_tareas += $tarea->avance;
        }
        $promedio_avances_tareas = round($promedio_avances_tareas/count($tareas), 2);
        $tareas = json_encode($tareas);
        return view('grafico', compact('proyecto', 'areas', 'tareas', 'categorias', 'promedio_avances_tareas'));
    }

    public function filtrar(Request $request)
    {
        $proyecto_id = $request->proyecto_id;
        $opcionColor = json_decode($request->filtro_color);
        $areaid = json_decode($request->filtro_area);
        $categoriaid = json_decode($request->filtro_categoria);
        $trabajoExterno = json_decode($request->filtro_externo);
        $tareas = Proyecto::with('tareas')->find($proyecto_id)->tareas;
        $tareas = $tareas
        ->when($opcionColor != null, function ($query) use ($opcionColor) {
            return $query->whereIn('colorAtraso', $opcionColor)
                        ->where('avance', '<', 100);
        })->when($areaid != null, function ($query) use ($areaid) {
            return $query->whereIn('area_id', $areaid);
        })->when($categoriaid != null, function ($query) use ($categoriaid) {
            return $query->whereIn('categoria_id', $categoriaid);
        })->when($trabajoExterno != null, function ($query) use ($trabajoExterno) {
            return $query->where('trabajo_externo', $trabajoExterno);
        })->sortBy(function ($tarea) {
            return [$tarea->fecha_inicio, $tarea->fecha_termino];
        })->values()->all();
        $promedio_avances_tareas = 0;
        if (count($tareas) > 0) {
            foreach ($tareas as $key => $tarea) {
                $promedio_avances_tareas += $tarea->avance;
            }
            $promedio_avances_tareas = round($promedio_avances_tareas/count($tareas), 2);
        }
        return json_encode(compact('tareas', 'promedio_avances_tareas'));
    }
}
