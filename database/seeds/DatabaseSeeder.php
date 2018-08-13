<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Proyecto;
use App\Tarea;
use App\Area;
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
        DB::unprepared(File::get('poblar tablas area y porcentaje.sql')); 
        $this->call(RoleTableSeeder::class);        
                
        $faker = Faker::create('es_ES');
        $minimo = new DateTime('01/01/2018');
        $maximo = new DateTime('12/31/2018');
        foreach (range(1,5) as $index){           
            $fecha_inicio = $faker->dateTimeBetween($startDate = $minimo, $endDate = $maximo, $timezone = 'America/Santiago');
            $fecha_inicio = $fecha_inicio->format('Y-m-d'); 
            $fecha_termino = $faker->dateTimeBetween($startDate = $fecha_inicio, $endDate = $maximo, $timezone = 'America/Santiago');
            $fecha_termino = $fecha_termino->format('Y-m-d'); 
            DB::table('proyectos')->insert([
                'created_at'=>now(),
                'updated_at'=>now(),
                'nombre'=>$faker->catchPhrase,
                'fecha_inicio'=>$fecha_inicio,
                'fecha_termino_original'=>$fecha_termino,
                'fecha_termino'=>$fecha_termino
            ]);
        }

        $proyectos = Proyecto::all()->pluck('id');
        $areas = Area::all()->pluck('id');
        $multiplos = DB::table('nomenclaturasAvance')->pluck('porcentaje');
        foreach (range(1,250) as $index){
            $fecha_inicio = $faker->dateTimeBetween($startDate = $minimo, $endDate = $maximo, $timezone = 'America/Santiago');
            $fecha_inicio = $fecha_inicio->format('Y-m-d'); 
            $fecha_termino = $faker->dateTimeBetween($startDate = $fecha_inicio, $endDate = $maximo, $timezone = 'America/Santiago');
            $fecha_termino = $fecha_termino->format('Y-m-d'); 
            DB::table('tareas')->insert([
                'created_at'=>now(),
                'updated_at'=>now(),
                'proyecto_id'=>$faker->randomElement($proyectos),
                'area_id'=>$faker->randomElement($areas),
                'nombre'=>$faker->catchPhrase,
                'fecha_inicio'=>$fecha_inicio,
                'fecha_termino_original'=>$fecha_termino,
                'fecha_termino'=>$fecha_termino,
                'avance'=>$faker->randomElement($multiplos)
            ]);
        }
        
    }      
}
