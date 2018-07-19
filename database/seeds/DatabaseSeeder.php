<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Proyecto;
use App\Tarea;
use App\Area;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(UsersTableSeeder::class);
        
        $faker = Faker::create('es_ES');

        foreach (range(1,5) as $index){
            $fecha_termino = $faker->date($formate = 'Y-m-d', $max = 'now');
            DB::table('proyectos')->insert([
                'created_at'=>now(),
                'updated_at'=>now(),
                'nombre'=>$faker->catchPhrase,
                'fecha_inicio'=>$faker->date($formate = 'Y-m-d', $max = $fecha_termino),
                'fecha_termino_original'=>$fecha_termino,
                'fecha_termino'=>$fecha_termino
            ]);
        }

        $proyectos = Proyecto::all()->pluck('id');
        $areas = Area::all()->pluck('id');
        foreach (range(1,250) as $index){
            DB::table('tareas')->insert([
                'created_at'=>now(),
                'updated_at'=>now(),
                'proyecto_id'=>$faker->randomElement($proyectos),
                'area_id'=>$faker->randomElement($areas),
                'nombre'=>$faker->catchPhrase,
                'fecha_inicio'=>$faker->date($formate = 'Y-m-d', $max = 'fecha_termino'),
                'fecha_termino'=>$faker->date($formate = 'Y-m-d', $max = 'now'),
                'avance'=>$faker->numberBetween($min = 0, $max = 100)                
            ]);
        }    
    }   


            

        
    
}
