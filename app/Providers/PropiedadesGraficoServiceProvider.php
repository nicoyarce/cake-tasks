<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\PropiedadesGrafico;  // Asegúrate de que estás usando el modelo correcto
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;  // Agregar para verificar la existencia de la tabla
use Illuminate\Database\QueryException;

class PropiedadesGraficoServiceProvider extends ServiceProvider
{
    /**
     * Registrar cualquier servicio en el contenedor de la aplicación.
     *
     * @return void
     */
    public function register()
    {
        // Aquí no necesitas registrar nada, ya que utilizamos el modelo directamente
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
            // Verificar si la tabla existe
            if (Schema::hasTable('propiedades_grafico')) {
                try {
                    // Intentar cargar las propiedades de la base de datos
                    $data = PropiedadesGrafico::all();  // Cargar todas las propiedades de la base de datos

                    // Almacenar las propiedades en la sesión
                    View::share('propiedades', $data);
                    Session::put('propiedades_grafico_cache', $data);
                } catch (QueryException $e) {
                    // Capturar la excepción si la consulta falla (por ejemplo, la tabla no existe)
                    \Log::error("Error al cargar las propiedades desde la base de datos: " . $e->getMessage());

                    // Puedes hacer cualquier otra acción, como enviar un mensaje o manejar el error de manera más amigable
                    Session::put('propiedades_grafico_cache', []);
                    View::share('propiedades', []);
                }
            } else {
                // Si la tabla no existe, maneja el caso (por ejemplo, muestra un mensaje de error o limpia la caché)
                \Log::warning("La tabla propiedades_grafico no existe en la base de datos.");

                // Limpiar la caché de la sesión si la tabla no existe
                Session::put('propiedades_grafico_cache', []);
                View::share('propiedades', []);
            }
        }
    }
}
