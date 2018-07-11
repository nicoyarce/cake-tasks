<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Tarea;

Route::get('/', function () {
    $nro = Tarea::all()->count();    
    return view('welcome', compact('nro'));
});

Route::resource('tareas', 'TareasController');

Route::get('/grafico', function () {		
	JavaScript::put(['tarea' => Tarea::sacarDatos()]);
	return view('grafico');
});

