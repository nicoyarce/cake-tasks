<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');
        $roleAdmin = Role::create(['name' => 'Administrador']);        
        $roleOCR = Role::create(['name' => 'OCR']);       
        $roleUser = Role::create(['name' => 'Usuario']);    

        Permission::create(['name' => 'crear_tareas']);
        Permission::create(['name' => 'modificar_tareas']);
        Permission::create(['name' => 'borrar_tareas']);

        Permission::create(['name' => 'gestionar_proyectos']);        

        Permission::create(['name' => 'gestionar_usuarios']);
        Permission::create(['name' => 'ver_graficos']);
        
        Permission::create(['name' => 'crear_informes']);
        Permission::create(['name' => 'ver_informes']);
        Permission::create(['name' => 'borrar_informes']);

        Permission::create(['name' => 'modificar_avance_tareas']);
        Permission::create(['name' => 'modificar_fechas_originales_tareas']);
        Permission::create(['name' => 'modificar_fechas_originales_proyecto']);        
        Permission::create(['name' => 'indice_proyectos_archivados']);
        Permission::create(['name' => 'gestionar_configuraciones']);
        
        $roleAdmin->givePermissionTo(Permission::all());

        $user = new User();
        $user->nombre = 'Pedro';
        $user->run = '11.111.111-1';
        $user->password = bcrypt('secret');
        $user->save();
        $user->assignRole(['Administrador']);
        
        $user = new User();
        $user->nombre = 'Juan';
        $user->run = '11.111.112-K';
        $user->password = bcrypt('secret');
        $user->save();
        $user->assignRole(['OCR']);        
        
        $user = new User();
        $user->nombre = 'Diego';
        $user->run = '11.111.120-0';
        $user->password = bcrypt('secret');
        $user->save();
        $user->assignRole('Usuario');
        
    }
}
