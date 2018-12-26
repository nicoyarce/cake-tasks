<?php

namespace App\Imports;

use App\Proyecto;
use App\Area;
use App\Tarea;
use App\TareaHija;
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
    public function collection(Collection $rows){       
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
        $primerIndicadorEncontrado = false;
        DB::beginTransaction();
        try {            
            foreach ($rows as $key=>$row){
                if($key == 0){
                    $proyecto = Proyecto::create([
                        'nombre' => $row['nombre'],
                        'fecha_inicio' => Date::createFromFormat('d F Y H:i', $row['comienzo'], 'America/Santiago')->toDateTimeString(),
                        'fecha_termino_original' =>  Date::createFromFormat('d F Y H:i', $row['fin'], 'America/Santiago')->toDateTimeString(),
                        'fecha_termino' =>  Date::createFromFormat('d F Y H:i', $row['fin'], 'America/Santiago')->toDateTimeString()
                    ]);
                    $proyecto->save();
                }
                else{
                    if(!is_null($row['indicador'])){
                        $primerIndicadorEncontrado = true;
                        $tarea = new Tarea;
                        try{
                            $area = Area::where('nombrearea', $row['area'])->firstOrFail();                            
                        }                       
                        catch(ModelNotFoundException $e){
                            $area = Area::where('nombrearea', 'Otra')->first();                                                        
                        }
                        finally{
                            $tarea->area()->associate($area);
                        }
                        $tarea->nombre = $row['nombre'];
                        $tarea->fecha_inicio = Date::createFromFormat('d F Y H:i', $row['comienzo'], 'America/Santiago')->toDateTimeString();
                        $tarea->fecha_termino_original =  Date::createFromFormat('d F Y H:i', $row['fin'], 'America/Santiago')->toDateTimeString();
                        $tarea->fecha_termino =  Date::createFromFormat('d F Y H:i', $row['fin'], 'America/Santiago')->toDateTimeString();
                        $tarea->proyecto()->associate($proyecto);                        
                        $tarea->save();
                        $ultimaTareaMadre = $tarea;
                    }
                    elseif($primerIndicadorEncontrado){
                        $tareaHija = new TareaHija;
                        $tareaHija->nombre = $row['nombre'];
                        $tareaHija->fecha_inicio = Date::createFromFormat('d M Y H:i', $row['comienzo'], 'America/Santiago')->toDateTimeString();
                        $tareaHija->fecha_termino =  Date::createFromFormat('d M Y H:i',$row['fin'], 'America/Santiago')->toDateTimeString();
                        $tareaHija->nivel = $row['nivel_de_esquema'];
                        $tareaHija->tareaMadre()->associate($ultimaTareaMadre);
                        $tareaHija->save();
                    }
                }
            }
            DB::commit();
        }    
        catch (InvalidArgumentException $e) {
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
