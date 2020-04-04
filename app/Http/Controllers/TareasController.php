<?php

namespace App\Http\Controllers;

use App\Tarea;
use App\Proyecto;
use App\Area;
use App\Observacion;
use App\User;
use App\TipoTarea;
use Jenssegers\Date\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTareasRequest;
use App\Http\Requests\UpdateTareasRequest;

class TareasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Tarea $tarea)
    {
        $tareasHijas = $tarea->tareasHijas()->get();
        return view('tareas.show', compact('tarea', 'tareasHijas'));
    }


    public function showArchivadas($id) {
        $tarea = Tarea::withTrashed()
            ->where('id', $id)
            ->get()
            ->first();
        $tareasHijas = $tarea->tareasHijas()->withTrashed()->get();
        return view('tareas.show', compact('tarea', 'tareasHijas'));
    }

    public function create($proyectoId)
    {
        $proyecto = Proyecto::find($proyectoId);
        $areas = Area::all();
        $tipo_tareas = TipoTarea::all();
        return view('tareas.create', compact('proyecto', 'areas', 'tipo_tareas'));
    }


    public function store(StoreTareasRequest $request)
    {
        $tarea = new Tarea;
        $area = Area::find($request->area_id);
        $proyecto = Proyecto::find($request->proyecto_id);
        $tipo_tarea = TipoTarea::find($request->tipo_tarea);
        $tarea->nombre = $request->nombre;
        $tarea->fecha_inicio = $request->fecha_inicio;
        $tarea->fecha_termino_original = $request->fecha_termino_original;
        $tarea->fecha_termino = $request->fecha_termino_original;
        $tarea->nro_documento = $request->nro_documento;

        $tarea->critica = ($request->has('critica')) ? true : false;

        $tarea->avance = $request->avance;
        $tarea->proyecto()->associate($proyecto);
        $tarea->area()->associate($area);
        $tarea->tipoTarea()->associate($tipo_tarea);
        $tarea->save();
        foreach ($request->observaciones as $textoObservacion) {
            if (!is_null($textoObservacion)) {
                $observacion = new Observacion();
                $observacion->contenido = $textoObservacion;
                $observacion->tarea()->associate($tarea);
                $observacion->autor()->associate(User::find(Auth::user()->id))->save();
            }
        }
        flash('Tarea <b>' . $tarea->nombre . '</b> registrada')->success();
        return redirect()->route('proyectos.show', $request->proyecto_id)->with('idTareaMod', $tarea->id);
    }

    public function edit(Tarea $tarea)
    {
        $listaProyectos = Proyecto::all();
        $areas = Area::all();
        $tipo_tareas = (is_null($tarea->tipo_tarea)) ? TipoTarea::all() : TipoTarea::findOrFail($tarea->tipo_tarea)->get();
        $avances = (is_null($tarea->tipo_tarea)) ? [] : TipoTarea::find($tarea->tipo_tarea)->nomenclaturasAvances()->get()->sortBy('porcentaje');
        $observaciones = $tarea->observaciones()->get();
        return view('tareas.edit', compact('tarea', 'listaProyectos', 'areas', 'tipo_tareas', 'avances', 'observaciones'));
    }

    public function update(UpdateTareasRequest $request, Tarea $tarea) {
        $tareaNueva = Tarea::find($tarea->id);
        if (Auth::user()->can('modificar_tareas') && Auth::user()->can('modificar_avance_tareas')) {
            $tareaNueva->nombre = $request->nombre;
            $tareaNueva->nro_documento = $request->nro_documento;
            $area = Area::find($request->area_id);
            $tareaNueva->area()->associate($area);
            $tareaNueva->critica = ($request->has('critica')) ? true : false;
            if (Auth::user()->can('modificar_fechas_originales_tareas')) {
                if ($request->has('fecha_termino_original') && $request->fecha_termino_original != $tarea->fecha_termino_original) {
                    $tareaNueva->fecha_termino_original = $request->fecha_termino_original;
                    $tareaNueva->fecha_termino = $request->fecha_termino_original;
                }
            }
            if ($request->has('fecha_termino') && !is_null($request->fecha_termino) && $request->fecha_termino != $tarea->fecha_termino) {
                $tareaNueva->autorUltimoCambioFtt()->associate(User::find(Auth::user()->id));
                $tareaNueva->fecha_ultimo_cambio_ftt = Date::now();
                $tareaNueva->fecha_termino = $request->fecha_termino;
                $tareaNueva->save();
            }
            if ($request->has('observaciones')) {
                $ids_observaciones = collect($request->ids_observaciones);
                Observacion::whereNotIn('id', $ids_observaciones)->forceDelete();
                $observacionesRestantes = $tareaNueva->observaciones()->get()->pluck('contenido');
                foreach ($request->observaciones as $n => $textoObservacion) {
                    if (!is_null($textoObservacion) && !$observacionesRestantes->contains($textoObservacion)) {
                        $observacion = new Observacion();
                        $observacion->contenido = $textoObservacion;
                        $observacion->tarea()->associate($tareaNueva);
                        $observacion->autor()->associate(User::find(Auth::user()->id));
                        $observacion->save();
                    }
                }
            }
            $tipo_tarea = TipoTarea::find($request->tipo_tarea);
            $tareaNueva->tipoTarea()->associate($tipo_tarea);
            if ($request->has('avance') && $request->avance != $tarea->avance) {
                $tareaNueva->autorUltimoCambioAvance()->associate(User::find(Auth::user()->id));
                $tareaNueva->fecha_ultimo_cambio_avance = Date::now();
                $tareaNueva->avance = $request->avance;
                $tareaNueva->save();
            }
        } elseif (Auth::user()->can('modificar_avance_tareas')) {
            $tareaNueva->fill($request->only('avance'));
        }
        $tareaNueva->save();
        flash('Tarea <b>' . $tareaNueva->nombre . '</b>  actualizada')->success();
        return redirect()->route('proyectos.show', $tareaNueva->proyecto_id)->with('idTareaMod', $tareaNueva->id);
    }

    public function destroy(Tarea $tarea)
    {
        $string = $tarea->nombre;
        $proyectoId = $tarea->proyecto()->get()->first()->id;
        Tarea::find($tarea->id)->forceDelete();
        flash('Tarea <b>' . $string . '</b>  eliminada')->success();
        return redirect()->route('proyectos.show', $proyectoId);
    }

    public function cargarVisor(Request $request)
    {
        $response = [
            'tasks'  => []
        ];
        $tareaMadre = Tarea::find($request->tareaid);
        $tareas = $tareaMadre->tareasHijas()->get();
        //dd($tareaMadre->fecha_inicio);
        $response['tasks'][] = [
            'id'            => $tareaMadre->id,
            'name'            => $tareaMadre->nombre,
            'progress'            => $tareaMadre->avance,
            'level'            => 0,
            'status' => "STATUS_ACTIVE",
            //'depends'            => $tareaMadre->id,
            'start'            => $tareaMadre->fecha_inicio->timestamp * 1000,
            //'duration'            => (string) "-"+$tareaMadre->id,
            'end'            => $tareaMadre->fecha_termino->timestamp * 1000,
            'collapsed'            => false,
            'hasChild' => true,
        ];
        foreach ($tareas as $tarea) {
            $response['tasks'][] = [
                'id'            => $tarea->id,
                'name'            => $tarea->nombre,
                'progress'            => $tarea->avance,
                'level'            => $tarea->nivel,
                'status' => "STATUS_ACTIVE",
                //'depends'            => $tarea->id,
                'start'            => $tarea->fecha_inicio->timestamp * 1000,
                //'duration'            => (string) "-"+$tareaMadre->id,
                'end'            => $tarea->fecha_termino->timestamp * 1000,
                'collapsed'            => false,
            ];
        }

        $response = json_encode($response);
        //dd($response);
        return view('visor', compact('response'));
    }
}
