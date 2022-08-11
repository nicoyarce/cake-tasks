<?php

namespace App\Imports;

use App\Proyecto;
use App\Area;
use App\Tarea;
use App\TareaHija;
use App\TipoTarea;
use App\Categoria;
use App\NomenclaturaAvance;
use Carbon\Carbon;
use Jenssegers\Date\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProyectosImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithMultipleSheets
{

    
    public function collection(Collection $rows)
    {
        $ultimaTareaMadre = new Tarea;
        DB::beginTransaction();
        try {
            $formato = "d-m-y G:i"; //14-11-21 17:00
            $timeZone = "America/Santiago";
            foreach ($rows as $key => $row) {
                $indicador = $row['indicador'];
                $nombre = $row['nombre'];
                $fecha_inicio = $row['comienzo'];
                $fecha_termino = $row['fin'];
                $nivel = $row['nivel_de_esquema'];
                $nombre_tipo_tarea = ($row['tipo_tarea'] != "") ? $row['tipo_tarea'] : "";
                $nombre_area = ($row['area'] != "") ? $row['area'] : "";
                $nro_documento = $row['cot'];
                $nombre_tipo_proyecto = ($row['tipo_proyecto'] != "") ? $row['tipo_proyecto'] : "";
                $trabajo_interno = ($row['trabajo_propio'] != "") ? $row['trabajo_propio'] : 0;
    
                if ($row->filter()->isNotEmpty()) {
                    if ($key == 0) {
                        $proyecto = Proyecto::create([
                            'nombre' => $nombre,
                            'fecha_inicio' => Date::createFromFormat($formato, $fecha_inicio, $timeZone)->toDateTimeString(),
                            'fecha_termino_original' =>  Date::createFromFormat($formato, $fecha_termino, $timeZone)->toDateTimeString(),
                            'fecha_termino' =>  Date::createFromFormat($formato, $fecha_termino, $timeZone)->toDateTimeString()
                        ]);
                        $proyecto->save();
                    } else {
                        if ($indicador == "*") {
                            $tarea = new Tarea;
                            if ($nombre_area != "") {                     
                                $area = Area::where('nombrearea', $nombre_area)
                                    ->orWhereRaw('UPPER("nombrearea") LIKE ?', ['%' . strtoupper($nombre_area) . '%'])->get();

                                if($area->isEmpty()){
                                    $nuevaArea = new Area;
                                    $nuevaArea->nombrearea = $nombre_area;
                                    $nuevaArea->save();
                                    $tarea->area()->associate($nuevaArea);
                                } else {
                                    $tarea->area()->associate($area->first());
                                }                          
                            }                            
                            if ($nombre_tipo_tarea != ""){                                
                                $tipo_tarea = TipoTarea::where('descripcion', $nombre_tipo_tarea)
                                ->orWhereRaw('UPPER("descripcion") LIKE ?', ['%' . strtoupper($nombre_tipo_tarea) . '%'])->get();

                                if ($tipo_tarea->isEmpty()){
                                    $nuevoTipoTarea = new TipoTarea;
                                    $nuevoTipoTarea->descripcion = $nombre_tipo_tarea;
                                    $nuevoTipoTarea->save();
                                    $tarea->tipoTarea()->associate($nuevoTipoTarea);
                                } else {
                                    $tarea->tipoTarea()->associate($tipo_tarea->first());
                                }
                            }
                            if ($nombre_tipo_proyecto != ""){
                                $tipo_proyecto = Categoria::where('nombre', $nombre_tipo_proyecto)
                                    ->orWhereRaw('UPPER("nombre") LIKE ?', ['%' . strtoupper($nombre_tipo_proyecto) . '%'])->get();
                               
                                if ($tipo_proyecto->isEmpty()){
                                    $nuevoTipoProyecto = new Categoria;
                                    $nuevoTipoProyecto->nombre = $nombre_tipo_proyecto;
                                    $nuevoTipoProyecto->save();
                                    $tarea->categoria()->associate($nuevoTipoProyecto);
                                } else {
                                    $tarea->categoria()->associate($tipo_proyecto->first());
                                }
                            }
                            
                            $tarea->nombre = $nombre;
                            $tarea->nro_documento = (!is_null($nro_documento)) ? $nro_documento : null;
                            $tarea->fecha_inicio = Date::createFromFormat($formato, $fecha_inicio, $timeZone)->toDateTimeString();
                            $tarea->fecha_termino_original =  Date::createFromFormat($formato, $fecha_termino, $timeZone)->toDateTimeString();
                            $tarea->fecha_termino =  Date::createFromFormat($formato, $fecha_termino, $timeZone)->toDateTimeString();
                            $tarea->trabajo_interno = (!is_null($trabajo_interno)) ? $trabajo_interno : 0;                            
                            $tarea->proyecto()->associate($proyecto);
                            $tarea->save();
                            $ultimaTareaMadre = $tarea;
                        } else {
                            $tareaHija = new TareaHija;
                            $tareaHija->nombre = $nombre;
                            $tareaHija->fecha_inicio = Date::createFromFormat($formato, $fecha_inicio, $timeZone)->toDateTimeString();
                            $tareaHija->fecha_termino = Date::createFromFormat($formato, $fecha_termino, $timeZone)->toDateTimeString();
                            if (is_numeric($nivel)) {
                                $tareaHija->nivel = $nivel;
                            } else {
                                $tareaHija->nivel = 2;
                            }
                            $tareaHija->tareaMadre()->associate($ultimaTareaMadre);
                            $tareaHija->save();
                        }
                    }
                }
            }
            DB::commit();
        } catch (InvalidArgumentException $e) {
            DB::rollBack();
        }
    }
    public function batchSize(): int
    {
        return 100;
    }
    public function sheets(): array
    {
        return [
            // Select by sheet index
            0 => new ProyectosImport()
        ];
    }
}
