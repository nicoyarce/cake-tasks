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
Route::get('/about', 'HomeController@about');
Route::get('/pdf', 'InformesController@test'); //test de pdf
Route::group(['middleware' => ['permission:gestionar_proyectos']], function () {
    //Cargas masivas
    Route::get('/proyectos/cargarXLS', 'ProyectosController@vistaCargarXLS');
    Route::post('/proyectos/cargarXLS', 'ProyectosController@cargarXLS');
    Route::get('/proyectos/cargarHijas', 'ProyectosController@vistaCargarHijas');
    Route::post('/proyectos/cargarHijas', 'ProyectosController@cargarHijas');
    Route::resource('proyectos', 'ProyectosController', ['except' => 'index', 'show']);
});
Route::group(['middleware' => ['permission:crear_informes']], function () {
    Route::post('/generarInforme/{proyecto}', 'InformesController@generarInforme');
});
Route::group(['middleware' => ['permission:borrar_informes']], function () {
    Route::delete('/informes/destroy/{id}', 'InformesController@destroy');
});
Route::group(['middleware' => ['permission:gestionar_usuarios']], function () {
    Route::delete('users/destroySelected', [
        'as' => 'users.destroySelected',
        'uses' => 'UsersController@destroySelected'
    ]);
    Route::get('/users/cargarXLS', 'UsersController@vistaCargarUsuarios');
    Route::post('/users/cargarXLS', 'UsersController@cargarUsuarios');
    Route::resource('users', 'UsersController');
});
Route::group(['middleware' => ['permission:gestionar_configuraciones']], function () {
    Route::get('areas/crear', 'AreasController@indexConModal');
    Route::resource('areas', 'AreasController');

    Route::resource('propiedadesGrafico', 'PropiedadesGraficoController');

    Route::get('avances/crear', 'NomenclaturaAvancesController@indexConModal');
    Route::resource('avances', 'NomenclaturaAvancesController');

    Route::get('tipotareas/crear', 'TipoTareasController@indexConModal');
    Route::resource('tipotareas', 'TipoTareasController');

    Route::get('roles/crear', 'RolesController@indexConModal');
    Route::resource('roles', 'RolesController');
});

Route::group(['middleware' => ['permission:borrar_tareas']], function () {
    Route::resource('tareas', 'TareasController', ['except' => 'create', 'edit', 'update', 'show']);
});

Route::group(['middleware' => ['permission:ver_graficos']], function () {
    Route::post('/visor', 'TareasController@cargarVisor'); //ajax
    Route::get('/visor', 'TareasController@cargarVisor'); //ajax
});

Route::group(['middleware' => ['permission:crear_tareas']], function () {
    Route::get('/tareas/create/{proyectoId}', [
        'as' => 'tareas.create',
        'uses' => 'TareasController@create'
    ]);
});

Route::group(['middleware' => ['permission:indice_proyectos_archivados']], function () {
    //Archivar proyectos terminados
    Route::get('proyectosArchivados', 'ProyectosController@indexArchivados');
    Route::get('proyectosArchivados/{id}', 'ProyectosController@showArchivados');
    Route::get('graficoArchivados/{id}', 'GraficosController@vistaGraficoArchivados');
    Route::get('informesArchivados/{id}', 'InformesController@vistaListaInformesArchivados');
    Route::get('tareasArchivadas/{id}', 'TareasController@showArchivadas');
    Route::get('proyectosArchivados/restaurar/{id}', 'ProyectosController@restaurar');
    Route::delete('/proyectosArchivados/eliminarPermanente/{id}', 'ProyectosController@eliminarPermanente');
});

Route::group(['middleware' => ['permission:modificar_tareas|modificar_avance_tareas']], function () {
    Route::get('/tareas/{tarea}/edit', [
        'as' => 'tareas.edit',
        'uses' => 'TareasController@edit'
    ]);

    Route::put('/tareas/{tarea}', [
        'as' => 'tareas.update',
        'uses' => 'TareasController@update'
    ]);
});

Route::get('/tareas/{tarea}', [
    'as' => 'tareas.show',
    'uses' => 'TareasController@show'
]);

Route::get('/proyectos', [
    'as' => 'proyectos.index',
    'uses' => 'ProyectosController@index'
]);

Route::get('/proyectos/{proyecto}', [
    'as' => 'proyectos.show',
    'uses' => 'ProyectosController@show'
]);

Route::group(['middleware' => ['permission:ver_informes']], function () {
    Route::get('/informes/{proyecto}', 'InformesController@vistaListaInformes');
});

Route::group(['middleware' => ['permission:ver_graficos']], function () {
    Route::get('/grafico/{proyecto}', 'GraficosController@vistaGrafico'); //ajax
    Route::post('/grafico/{proyecto}/filtrar', 'GraficosController@filtrar');  //ajax
});

Route::post('/tareas/consultaAvances', 'NomenclaturaAvancesController@avances'); //ajax
Route::post('/obtienePropiedadesGrafico', 'PropiedadesGraficoController@obtienePropiedadesGrafico');
Route::get('/login', 'SessionsController@create')->name('login');
Route::post('/login', 'SessionsController@store');
Route::get('/logout', 'SessionsController@destroy');
