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
		$base = env('DB_DATABASE', 'holistic');
		$sql = "
		INSERT INTO $base.areas (id, nombrearea, created_at, updated_at) VALUES
		(1, 'Ingeniería', now(), now()),
		(2, 'Mecánica', now(), now()),
		(3, 'Telecomunicaciones', now(), now()),
		(4, 'Electricidad', now(), now()),
		(5, 'Electrónica', now(), now()),
		(6, 'Otra', now(), now());

		INSERT INTO $base.tipo_tareas (id, descripcion, created_at, updated_at) VALUES
			(1, 'Tarea', now(), now());

		INSERT INTO $base.nomenclaturasavance (id, porcentaje, glosa, created_at, updated_at, tipo_tarea) VALUES
			(1, 0, 'Paso 1', now(), now(), 1),
			(2, 5, 'Paso 2', now(), now(), 1),
			(3, 10, 'Paso 3', now(), now(), 1),
			(4, 15, 'Paso 4', now(), now(), 1),
			(5, 25, 'Paso 5', now(), now(), 1),
			(6, 30, 'Paso 6', now(), now(), 1),
			(7, 35, 'Paso 7', now(), now(), 1),
			(8, 40, 'Paso 8', now(), now(), 1),
			(9, 50, 'Paso 9', now(), now(), 1),
			(10, 60, 'Paso 10', now(), now(), 1),
			(11, 70, 'Paso 11', now(), now(), 1),
			(12, 75, 'Paso 12', now(), now(), 1),
			(13, 80, 'Paso 13', now(), now(), 1),
			(14, 85, 'Paso 14', now(), now(), 1),
			(15, 95, 'Paso 15', now(), now(), 1),
			(16, 100, 'Paso 16', now(), now(), 1);

		INSERT INTO $base.propiedades_grafico (id, nombre, avance, color) VALUES
			(1, 'A tiempo', 0, '#28a745'),
			(2, 'Advertencia', 60, '#ffff00'),
			(3, 'Peligro', 90, '#f48024'),
			(4, 'Atrasado', 100, '#dc3545'),
			(5, 'Avance', -1, '#074590'),
			(6, 'Porcentaje para verde', 101, '#28a745');
		";
        $this->call(RoleTableSeeder::class);
        DB::unprepared($sql); 
        $this->call(ProyectosTareasSeeder::class);             
    }      
}
