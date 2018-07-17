<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $faker = Faker::create('es_ES');
        foreach (range(1,8) as $index){
            DB::table('proyectos')->insert([
                'nombre'=>$faker->catchPhrase,
                'fecha_inicio'=>$faker->date($formate = 'Y-m-d', $max = 'now'),
                'fecha_termino'=>$faker->date($formate = 'Y-m-d', $max = 'now')
            ]);
        }

        foreach (range(1,250) as $index){
            DB::table('tareas')->insert([
                'area_id'=>$faker->numberBetween($min = 1, $max = 6),
                'nombre'=>$faker->catchPhrase,
                'fecha_inicio'=>$faker->date($formate = 'Y-m-d', $max = 'fecha_termino'),
                'fecha_termino'=>$faker->date($formate = 'Y-m-d', $max = 'now'),
                'avance'=>$faker->numberBetween($min = 0, $max = 100)
                
            ]);
        }
    }
}
