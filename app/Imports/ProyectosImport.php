<?php

namespace App\Imports;

use App\Proyecto;
use App\Area;
use App\Tarea;
use App\TareaHija;
use App\TipoTarea;
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
        //dd($rows->toArray());
        // Validator::make($rows->toArray(),[
        //     '*.comienzo' => [
        //         function($attribute, $value, $fail) {
        //             if ($value === 'foo') {
        //                 return $fail($attribute.' es invalido.');
        //             }
        //         }
        //     ],
        //     '*.fin' => [
        //         function($attribute, $value, $fail) {
        //             if ($value === 'foo') {
        //                 return $fail($attribute.' es invalido.');
        //             }
        //         }
        //     ]
        // ])->validate();
        
        $ultimaTareaMadre = new Tarea;
        DB::beginTransaction();
        try {
            $formato = "d-m-y G:i"; //01-06-20 8:00
            $timeZone = "America/Santiago";
            foreach ($rows as $key => $row) {
                $indicador = $row['indicador'];
                $nombre = $row['nombre'];
                $fecha_inicio = $row['comienzo'];
                $fecha_termino = $row['fin'];
                $nivel = $row['nivel_de_esquema'];
                $tipo_tarea = $row['tipo_tarea'];
                $area = $row['area'];
                $nro_documento = $row['cot'];
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
                            try {
                                $area = Area::where('nombrearea', $area)->firstOrFail();
                            } catch (ModelNotFoundException $e) {
                                $area = Area::where('nombrearea', 'Otra')->first();
                            } finally {
                                $tarea->area()->associate($area);
                            }
                            try {
                                $tipo_tarea = TipoTarea:: where('descripcion', $tipo_tarea)
                                    ->orWhere('descripcion', 'like', '%' . $tipo_tarea . '%')->firstOrFail();
                            } catch (ModelNotFoundException $e) {
                                $tipo_tarea = TipoTarea::all()->first();
                            } finally {
                                $tarea->tipoTarea()->associate($tipo_tarea);
                            }
                            $tarea->nombre = $nombre;
                            //$tarea->nro_documento = (!is_null($nro_documento)) ? $nro_documento : '';
                            $tarea->fecha_inicio = Date::createFromFormat($formato, $fecha_inicio, $timeZone)->toDateTimeString();
                            $tarea->fecha_termino_original =  Date::createFromFormat($formato, $fecha_termino, $timeZone)->toDateTimeString();
                            $tarea->fecha_termino =  Date::createFromFormat($formato, $fecha_termino, $timeZone)->toDateTimeString();
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
