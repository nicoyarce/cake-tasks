<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
		DB::table('areas')->insert([
			['id'=> 1, 'nombrearea' => 'Ingeniería'],
			['id'=> 2, 'nombrearea' => 'Mecánica'],
			['id'=> 3, 'nombrearea' => 'Telecomunicaciones'],
			['id'=> 4, 'nombrearea' => 'Electricidad'],
			['id'=> 5, 'nombrearea' => 'Electrónica'],
			['id'=> 6, 'nombrearea' => 'Otra'],
		]);
		DB::table('tipo_tareas')->insert([
			['id'=> 1, 'descripcion' => 'Tarea Normal']	,		
			['id'=> 2, 'descripcion' => 'Tarea Extendida']	
		]);
		DB::table('nomenclaturasavance')->insert([
			['id'=> 1, 'porcentaje' => 5, 'glosa' => 'Paso 1: Iniciada', 'tipo_tarea' => 1],
			['id'=> 2, 'porcentaje' => 25, 'glosa' => 'Paso 2: Planificada', 'tipo_tarea' => 1],
			['id'=> 3, 'porcentaje' => 50, 'glosa' => 'Paso 3: Desarrollo', 'tipo_tarea' => 1],
			['id'=> 4, 'porcentaje' => 75, 'glosa' => 'Paso 4: Finalizando', 'tipo_tarea' => 1],
			['id'=> 5, 'porcentaje' => 95, 'glosa' => 'Paso 5: Revision Final', 'tipo_tarea' => 1],
			['id'=> 6, 'porcentaje' => 100, 'glosa' => 'Paso 6: Terminada', 'tipo_tarea' => 1],
		]);

		DB::table('propiedades_grafico')->insert([
			['id'=> 1, 'nombre' => 'A Tiempo', 'avance' => 0, 'color' => '#28a745'],
			['id'=> 2, 'nombre' => 'Advertencia', 'avance' => 60, 'color' => '#ffff00'],
			['id'=> 3, 'nombre' => 'Peligro', 'avance' => 90, 'color' => '#f48024'],
			['id'=> 4, 'nombre' => 'Atrasado', 'avance' => 100, 'color' => '#dc3545'],
			['id'=> 5, 'nombre' => 'Avance', 'avance' => -1, 'color' => '#074590'],
			['id'=> 6, 'nombre' => 'Porcentaje para verde', 'avance' => 101, 'color' => '#28a745'],
		]);

		DB::table('categoria')->insert([
			['id'=> 1, 'nombre' => 'Actividad Fisica'],
			['id'=> 2, 'nombre' => 'Actividad Mental'],
		]);
        $this->call(RoleTableSeeder::class);
        $this->call(ProyectosTareasSeeder::class);
    }
}
