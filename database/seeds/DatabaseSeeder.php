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
        $this->call(RoleTableSeeder::class);
        DB::unprepared(File::get('poblar tablas.sql')); 
        $this->call(ProyectosTareasSeeder::class);             
    }      
}
