<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $request->user()->hasRole($role)) {
            //Hemos agregado una redirección estándar a la ruta “home”, pero en esta línea podrás agregar lo que desees. Por ejemplo:
            return redirect('home');
        }
        return $next($request);
    }
}
