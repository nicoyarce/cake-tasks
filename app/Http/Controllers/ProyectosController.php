<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Proyecto;
use App\Observacion;
use App\User;
use App\PropiedadesGrafico;
use App\Http\Requests\StoreProyectosRequest;
use App\Http\Requests\UpdateProyectosRequest;
use Jenssegers\Date\Date;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProyectosImport;
use App\Imports\TareasImport;

class ProyectosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('gestionar_proyectos')) {
            $proyectos = Proyecto::with('tareas')->get();
        } else {
            $proyectos = Auth::user()->proyectos()->with('tareas');
        }
        return view('proyectos.index', compact('proyectos'));
    }

    public function indexArchivados()
    {
        if (Auth::user()->can('gestionar_proyectos') && Auth::user()->can('indice_proyectos_archivados')) {
            $proyectos = Proyecto::with('tareas')->onlyTrashed()->orderBy('deleted_at')->paginate(5);
        } elseif (Auth::user()->can('indice_proyectos_archivados')) {
            $proyectos = Auth::user()->proyectos()->with('tareas')->onlyTrashed()->orderBy('deleted_at')->paginate(5);
        }
        return view('proyectos.indexarchivados', compact('proyectos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('proyectos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProyectosRequest $request)
    {
        $proyecto = new Proyecto($request->all());
        $proyecto->nombre = $request->nombre;
        $proyecto->fecha_inicio = $request->fecha_inicio;
        $proyecto->fecha_termino_original = $request->fecha_termino_original;
        $proyecto->fecha_termino = $request->fecha_termino_original;
        $proyecto->save();
        foreach ($request->observaciones as $textoObservacion) {
            if (!is_null($textoObservacion)) {
                $observacion = new Observacion();
                $observacion->contenido = $textoObservacion;
                $observacion->proyecto()->associate($proyecto);
                $observacion->autor()->associate(User::find(Auth::user()->id))->save();
                $observacion->save();
            }
        }
        flash('Proyecto <b>' . $proyecto->nombre . '</b> registrado')->success();
        return redirect('proyectos');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function show(Proyecto $proyecto)
    {
        $tareas = $proyecto->tareas
            ->sortBy(function ($tarea) {
                return [$tarea->fecha_inicio, $tarea->fecha_termino];
            })->values()->all();        
        return view('proyectos.show', compact('proyecto', 'tareas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function edit(Proyecto $proyecto)
    {
        return view('proyectos.edit', compact('proyecto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */

    public function update(UpdateProyectosRequest $request, Proyecto $proyecto)
    {
        $proyectoNuevo = Proyecto::find($proyecto->id);
        $proyectoNuevo->nombre = $request->nombre;
        if ($request->has('fecha_termino_original') && $request->fecha_termino_original != $proyecto->fecha_termino_original) {
            $proyectoNuevo->fecha_termino_original = $request->fecha_termino_original;
        }
        if ($request->has('fecha_termino') && $request->fecha_termino != $proyecto->fecha_termino) {
            $proyectoNuevo->autorUltimoCambioFtr()->associate(User::find(Auth::user()->id))->save();
            $proyectoNuevo->fecha_ultimo_cambio_ftr = Date::now();
            $proyectoNuevo->fecha_termino = $request->fecha_termino;
        }
        if ($request->has('observaciones')) {
            $ids_observaciones = collect($request->ids_observaciones);
            $proyectoNuevo->observaciones()->where('proyecto_id', $proyectoNuevo->id)->whereNotIn('id', $ids_observaciones)->forceDelete();
            $observacionesRestantes = $proyectoNuevo->observaciones()->get()->pluck('contenido');
            foreach ($request->observaciones as $n => $textoObservacion) {
                if (!is_null($textoObservacion) && !$observacionesRestantes->contains($textoObservacion)) {
                    $observacion = new Observacion();
                    $observacion->contenido = $textoObservacion;
                    $observacion->proyecto()->associate($proyectoNuevo);
                    $observacion->autor()->associate(User::find(Auth::user()->id));
                    $observacion->save();
                }
            }
        }
        if ($request->has('fecha_termino_original') && Auth::user()->can('modificar_fechas_originales_proyecto')) {
            $proyectoNuevo->fecha_termino_original = $request->fecha_termino_original;
        }
        $proyectoNuevo->save();
        flash('Proyecto <b>' . $proyectoNuevo->nombre . '</b> actualizado.')->success();
        return redirect('proyectos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proyecto $proyecto)
    {
        $string = $proyecto->nombre;
        $comando = 'command:generaInforme';
        \Artisan::call($comando, ['proyecto' => $proyecto->id]);
        $proyecto = Proyecto::destroy($proyecto->id);
        flash('Proyecto <b>' . $string . '</b> archivado')->success();
        return redirect('proyectos');
    }

    public function showArchivados($id)
    {
        $proyecto = Proyecto::withTrashed()
            ->where('id', $id)
            ->get()
            ->first();
        $tareas = $proyecto->tareas()->withTrashed()->get()
            ->sortBy(function ($tarea) {
                return [$tarea->fecha_inicio, $tarea->fecha_termino];
            })->values()->all();
        return view('proyectos.show', compact('proyecto', 'tareas'));
    }

    public function restaurar($id)
    {
        $proyecto = Proyecto::withTrashed()->find($id);
        $proyecto->restore();
        $proyecto->informes()->withTrashed()->restore();
        $proyecto->tareas()->withTrashed()->restore();
        $proyecto->tareasHijas()->withTrashed()->restore(); //usar esto en laravel 5.8
        foreach ($proyecto->tareas()->withTrashed()->get() as $tarea) {
            $tarea->restore();
            foreach ($tarea->tareasHijas()->withTrashed()->get() as $tareaHija) {
                $tareaHija->restore();
            }
        }
        flash('Proyecto restaurado')->success();
        return redirect('proyectosArchivados');
    }

    public function eliminarPermanente($id)
    {
        $proyecto = Proyecto::withTrashed()->find($id);
        $string = $proyecto->nombre;
        $proyecto->informes()->withTrashed()->forceDelete();
        $proyecto->tareasHijas()->withTrashed()->forceDelete(); //usar esto en laravel 5.8
        foreach ($proyecto->tareas()->withTrashed()->get() as $tarea) {
            foreach ($tarea->tareasHijas()->withTrashed()->get() as $tareaHija) {
                $tareaHija->forceDelete();
            }
            $tarea->forceDelete();
        }
        $proyecto->forceDelete();
        flash('Proyecto <b>' . $string . '</b> eliminado')->success();
        return redirect('proyectosArchivados');
    }

    public function vistaCargarXLS()
    {
        return view('proyectos.cargarxls');
    }

    public function cargarXLS(Request $request)
    {
        $validatedData = $request->validate([
            'archivo' => 'required|file|mimes:xlsx',
        ]);
        Excel::import(new ProyectosImport, $request->archivo);
        flash('Proyecto importado correctamente')->success();
        return redirect('proyectos');
    }

    public function vistaCargarHijas()
    {
        $proyectos = Proyecto::all();
        return view('proyectos.cargarhijas', compact('proyectos'));
    }

    public function cargarHijas(Request $request)
    {
        $validatedData = $request->validate([
            'archivo' => 'required|file|mimes:xlsx',
        ]);
        Excel::import(new TareasImport, $request->archivo);
        flash('Tareas importadas correctamente')->success();
        return redirect('proyectos');
    }
}
