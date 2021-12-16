<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Freshwork\ChileanBundle\Rut;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class UsuariosImport implements ToCollection, WithHeadingRow{
    
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $key => $row) {
                $usuario = new User; 
                $usuario->run = Rut::parse($row['run'])->format(Rut::FORMAT_COMPLETE);
                $usuario->nombre = $row['nombre'];
                $usuario->cargo = $row['uurr'];
                $usuario->password = bcrypt(Str::limit(Rut::parse($row['run'])->format(Rut::FORMAT_ESCAPED), 4));
                $usuario->save();
                $rol = Role::findByName($row['rol']);
                $usuario->assignRole($rol->name);                   
            }
            DB::commit();
        } catch (InvalidArgumentException $e) {
            DB::rollBack();
        }       
    }
}
