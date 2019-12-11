<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Proyecto;
use App\Informe;
use Jenssegers\Date\Date;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Barryvdh\Snappy\Snappy as PDF;

class GeneraInforme extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:generaInforme {proyecto : El ID del proyecto}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permite generar informe del proyecto indicado por su id en formato PDF';

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
        Log::info('Inicio de generacion de informe');
        $proyectoId = $this->argument('proyecto');
        try{
            $proyecto = Proyecto::find($proyectoId);            
            $tareas = $proyecto->tareas()->get();
            $tareasJSON = $tareas->sortBy(function($tarea) {
                            return [$tarea->fecha_inicio, $tarea->fecha_termino];
                        })->values()->all();
            $tareasJSON = json_encode($tareasJSON);            
            $pdf = \PDF::loadView('pdf', compact('proyecto', 'tareas', 'tareasJSON'));
            $pdf->setOption('encoding', 'UTF-8');
            $pdf->setOption('javascript-delay', 2000);
            $informe = new Informe;
            $informe->fecha = Date::now();        
            $informe->ruta = 'public/'.$proyecto->nombre.' - '.$informe->fecha->format('d-M-Y').'-'.$informe->fecha->format('H.i.s').'.pdf';
            $informe->proyecto()->associate($proyecto);
            $informe->save();
            Storage::disk('local')->put($informe->ruta, $pdf->output());            
        } catch(\Exception $e){
            Log::error('Ha ocurrido una excepcion: '.$e);
        }
        Log::info('Termino de generacion de informe');        
    }
}
