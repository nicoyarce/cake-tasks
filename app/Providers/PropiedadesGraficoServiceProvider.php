<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\PropiedadesGrafico;
use Illuminate\Support\Facades\Session;

class PropiedadesGraficoServiceProvider extends ServiceProvider
{
    /**
     * Registrar cualquier servicio en el contenedor de la aplicación.
     *
     * @return void
     */
    public function register()
    {
        // Aquí no necesitas registrar nada, ya que utilizaremos el modelo directamente
    }

    /**
     * Realizar cualquier tarea de arranque (booting) que sea necesaria.
     *
     * @return void
     */
    public function boot()
    {
        // Este código se ejecutará en el arranque de la aplicación
        $this->cargarPropiedadesEnSesion();
    }

    /**
     * Método para cargar las propiedades desde la base de datos y almacenarlas en la sesión.
     */
    protected function cargarPropiedadesEnSesion()
    {
        // Verificar si las propiedades ya están en la sesión
        $data = Session::get('propiedades_grafico_cache');

        // Si no están en la sesión, cargar desde la base de datos y almacenarlas en la sesión
        if (!$data) {
            $data = PropiedadesGrafico::all();  // Cargar todas las propiedades de la base de datos

            // Almacenar las propiedades en la sesión
            Session::put('propiedades_grafico_cache', $data);
        }
    }
}
