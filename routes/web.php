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
Route::get('/', 'HomeController@index')->name('home');

Route::post('/tareas/create/',[
    'as' => 'tareas.create', 
    'uses' => 'TareasController@create']);
Route::resource('tareas', 'TareasController', ['except' => 'create']);

Route::resource('proyectos', 'ProyectosController');

Route::get('/grafico/{proyecto}', 'GraficosController@show');
Route::post('/grafico/{proyecto}/filtrar', 'GraficosController@filtrar');

Route::get('/register', 'RegistrationController@create');
Route::post('/register', 'RegistrationController@store');

Route::get('/login', 'SessionsController@create');
Route::post('/login', 'SessionsController@store');
Route::get('/logout', 'SessionsController@destroy');

