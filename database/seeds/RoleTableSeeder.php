<?php

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

        $indice_tareas = Permission::create(['name' => 'indice_tareas']);
        $crear_tareas = Permission::create(['name' => 'crear_tareas']);
        $modificar_tareas = Permission::create(['name' => 'modificar_tareas']);
        $borrar_tareas = Permission::create(['name' => 'borrar_tareas']);

        $indice_proyectos = Permission::create(['name' => 'indice_proyectos']);
        $crear_proyectos = Permission::create(['name' => 'crear_proyectos']);
        $modificar_proyectos = Permission::create(['name' => 'modificar_proyectos']);
        $borrar_proyectos = Permission::create(['name' => 'borrar_proyectos']);
        $detalle_proyectos = Permission::create(['name' => 'detalle_proyectos']);

        $gestionar_usuarios = Permission::create(['name' => 'gestionar_usuarios']);
        $ver_graficos = Permission::create(['name' => 'ver_graficos']);

        
        $roleUser->givePermissionTo(
            'indice_tareas', 
            'modificar_tareas',
            'indice_proyectos',
            'detalle_proyectos');
        
        $roleOCR->givePermissionTo(
            'indice_tareas', 
            'modificar_tareas',
            'indice_proyectos',
            'detalle_proyectos',
            'crear_tareas',
            'borrar_tareas',
            'ver_graficos');
        
        $roleAdmin->givePermissionTo(Permission::all());

        $user = new User();
        $user->nombre = 'Pedro';
        $user->run = '1.111.111-1';
        $user->password = bcrypt('secret');
        $user->save();
        $user->assignRole(['Administrador']);
        
                
        $user = new User();
        $user->nombre = 'Juan';
        $user->run = '2.222.222-2';
        $user->password = bcrypt('secret');
        $user->save();
        $user->assignRole(['OCR']);
        
        
        $user = new User();
        $user->nombre = 'Diego';
        $user->run = '3.333.333-3';
        $user->password = bcrypt('secret');
        $user->save();
        $user->assignRole('Usuario');
        
    }
}
