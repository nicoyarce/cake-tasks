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
Route::get('/', 'HomeController@index');
//Route::get('/pdf', 'InformesController@test'); //test de pdf

Route::group(['middleware' => ['role:Administrador']], function () {
    Route::get('/proyectos/cargarXLS', 'ProyectosController@vistaCargarXLS');
    Route::post('/proyectos/cargarXLS', 'ProyectosController@cargarXLS');
    Route::get('/proyectos/cargarHijas', 'ProyectosController@vistaCargarHijas');
    Route::post('/proyectos/cargarHijas', 'ProyectosController@cargarHijas');

    Route::delete('/informes/destroy/{id}','InformesController@destroy');    

    Route::resource('users', 'UsersController');
    Route::resource('proyectos', 'ProyectosController', ['except' => 'index', 'show']);    
    Route::get('proyectosArchivados', 'ProyectosController@indexArchivados');
    Route::get('proyectosArchivados/restaurar/{id}', 'ProyectosController@restaurar');
    Route::delete('/proyectosArchivados/eliminarPermanente/{id}','ProyectosController@eliminarPermanente');
    
});

Route::group(['middleware' => ['role:Administrador|OCR']], function () {
    Route::resource('tareas', 'TareasController', ['except' => 'create', 'edit', 'update']);    
    Route::post('/visor', 'TareasController@cargarVisor'); //ajax    
    Route::get('/tareas/create/{proyectoId}',[
    'as' => 'tareas.create', 
    'uses' => 'TareasController@create']);     
});

Route::group(['middleware' => ['role:Administrador|OCR|Usuario']], function () {
    Route::get('/tareas/{tarea}/edit',[
    'as' => 'tareas.edit', 
    'uses' => 'TareasController@edit']);

    Route::put('/tareas/{tarea}',[
    'as' => 'tareas.update', 
    'uses' => 'TareasController@update']);

    Route::get('/proyectos', [
    'as' => 'proyectos.index', 
    'uses' => 'ProyectosController@index']);

    Route::get('/proyectos/{proyecto}', [
    'as' => 'proyectos.show', 
    'uses' =>'ProyectosController@show']);

    Route::get('/generarInforme','InformesController@vistaGenerarInformes');
    Route::get('/generarInformeManual/{proyecto}','InformesController@generarInformeManual');    
    Route::get('/informes/{proyecto}','InformesController@vistaListaInformes');    

    Route::get('/grafico/{proyecto}', 'GraficosController@vistaGrafico'); //ajax    
    Route::post('/grafico/{proyecto}/filtrar', 'GraficosController@filtrar');  //ajax
});

Route::get('/login', 'SessionsController@create')->name('login');
Route::post('/login', 'SessionsController@store');
Route::get('/logout', 'SessionsController@destroy');

