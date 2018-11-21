<?php

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
        DB::unprepared(File::get('poblar tablas area y porcentaje.sql')); 
        $this->call(RoleTableSeeder::class);
        $this->call(ProyectosTareasSeeder::class);             
    }      
}
