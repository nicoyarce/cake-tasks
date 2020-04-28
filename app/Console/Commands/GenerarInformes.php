<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Proyecto;
use App\Informe;
use Jenssegers\Date\Date;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Barryvdh\Snappy\Snappy as PDF;
use Illuminate\Support\Facades\DB;
use App\PropiedadesGrafico;

class GenerarInformes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:generarInformes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permite generar informes de los proyectos en formato PDF';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info('Inicio de generacion de informes');
        DB::beginTransaction();
        try {
            $proyectos = Proyecto::all();
            $arrayConfiguraciones = PropiedadesGrafico::all();
            foreach ($proyectos as $proyecto) {
                $tareas = $proyecto->tareas()->get();
                $tareasJSON = $tareas->sortBy(function ($tarea) {
                                return [$tarea->fecha_inicio, $tarea->fecha_termino];
                })->values()->all();
                $tareasJSON = json_encode($tareasJSON);
                $pdf = \PDF::loadView('pdf', compact('proyecto', 'tareas', 'tareasJSON', 'arrayConfiguraciones'));
                $pdf->setOption('encoding', 'UTF-8');
                $pdf->setOption('javascript-delay', 1000);
                $informe = new Informe;
                $informe->fecha = Date::now();
                $informe->ruta = 'public/'.$proyecto->nombre.' - '.$informe->fecha->format('d-M-Y').'-'.$informe->fecha->format('H.i.s').'.pdf';
                $arrayColores = PropiedadesGrafico::all()->whereNotIn('id', 6)->pluck('color');
                $informe->colores = json_encode($arrayColores, JSON_FORCE_OBJECT);
                $informe->proyecto()->associate($proyecto);
                $informe->save();
                Storage::disk('local')->put($informe->ruta, $pdf->output());
            }
            DB::commit();
        } catch (\Exception $e) {
            Log::error('Ha ocurrido una excepcion: '.$e);
            DB::rollback();
        }
        Log::info('Termino de generacion de informes');
    }
}
