<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Proyecto;
use App\Informe;
use Carbon\Carbon;
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
            $incluye_grafico = true;
            $incluye_observaciones = true;
            $arrayConfiguraciones = compact('incluye_grafico', 'incluye_observaciones');
            for ($i = 1; $i <= 4; $i++) {
                foreach ($proyectos as $proyecto) {
                    if ($i == 1) {
                        //para generar informe completo
                        $arrayColores = PropiedadesGrafico::all()->whereNotIn('id', 6)->pluck('color');
                        $tareas = $proyecto->tareas()->get();
                    } else {
                        //para generar colores individuales
                        $arrayColores = PropiedadesGrafico::where('id', $i)->pluck('color');
                        $tareas = $proyecto->tareas()->get()->whereIn('colorAtraso', $arrayColores);
                    }
                    $tareasJSON = $tareas->sortBy(function ($tarea) {
                        return [$tarea->fecha_inicio, $tarea->fecha_termino];
                    })->values()->all();
                    $tareasJSON = json_encode($tareasJSON);
                    $pdf = \PDF::loadView('pdf', compact('proyecto', 'tareas', 'tareasJSON', 'arrayConfiguraciones'));
                    $pdf->setOption('enable-local-file-access', true);
                    $pdf->setOption('encoding', 'UTF-8');
                    $pdf->setOption('enable-javascript', true);
                    $pdf->setOption('images', true);
                    $pdf->setOption('javascript-delay', 5000);
                    $informe = new Informe;
                    $informe->fecha = Carbon::now();
                    $informe->ruta = 'public/' . $proyecto->nombre . ' - ' . $informe->fecha->format('d-M-Y') . '-' . $informe->fecha->format('H.i.s') . '.pdf';
                    $informe->colores = json_encode($arrayColores, JSON_FORCE_OBJECT);
                    $informe->proyecto()->associate($proyecto);
                    $informe->save();
                    Storage::disk('local')->put($informe->ruta, $pdf->output());
                }
            }
            DB::commit();
            return 0;
        } catch (\Exception $e) {
            Log::error('Ha ocurrido una excepcion: ' . $e);
            DB::rollback();
            return 1;
        }
        Log::info('Termino de generacion de informes');
    }
}
