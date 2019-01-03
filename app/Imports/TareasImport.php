<?php

namespace App\Imports;

use App\Proyecto;
use App\Tarea;
use App\TareaHija;
use Jenssegers\Date\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TareasImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows){
        /*Validator::make($rows->toArray(), [
            function ($attribute, $value, $fail) {
                if ($value === 'foo') {
                    $fail($attribute.' is invalid.');
                }
            },
        ])->validate();*/
        $proyecto = Proyecto::find(request()->proyecto_id);
        $ultimaTareaMadre = new Tarea;   
        $primerIndicadorEncontrado = false;
        DB::beginTransaction();
        try{
            foreach ($rows as $key=>$row) {                              
                if(!$key == 0){
                    if(!is_null($row['indicador'])){
                        $primerIndicadorEncontrado = true;
                        $tareasProyecto = $proyecto->tareas()->get();
                        //$ultimaTareaMadre = $tareasProyecto::where('nombre', 'LIKE', "%{$row->nombre}%")->get();
                        $ultimaTareaMadre = $tareasProyecto->filter(function ($tarea) use ($row){
                            return false !== stristr($tarea->nombre, $row['nombre']);
                        });                    
                    }
                    elseif($primerIndicadorEncontrado){
                        $tareaHija = new TareaHija;
                        $tareaHija->nombre = $row['nombre'];
                        $tareaHija->fecha_inicio = Date::createFromFormat('d-m-y G:i', $row['comienzo'], 'America/Santiago')->toDateTimeString();
                        $tareaHija->fecha_termino =  Date::createFromFormat('d-m-y G:i', $row['fin'], 'America/Santiago')->toDateTimeString();
                        $tareaHija->nivel = $row['nivel_de_esquema'];
                        $tareaHija->tareaMadre()->associate($ultimaTareaMadre->first());
                        $tareaHija->save();
                    }                   
                }
            }
            DB::commit();
        }
        catch(InvalidArgumentException $e) {
            DB::rollBack();
        }
    }
}
