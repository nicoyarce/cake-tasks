<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Proyecto;
use App\User;
use App\Informe;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function index()
    {
        if (Auth::check()) {
            $nroProyectos = null;
            $nroProyectosArch = 0;
            $nroUsuarios = null;
            $nroInformes = null;
            if (Auth::user()->can('gestionar_proyectos') && Auth::user()->can('indice_proyectos_archivados')) {
                $nroProyectos = Proyecto::count();
                $nroProyectosArch = Proyecto::onlyTrashed()->count();
            } elseif (Auth::user()->can('indice_proyectos_archivados')) {
                $nroProyectos = Auth::user()->proyectos->count();
                $nroProyectosArch = Auth::user()->proyectos()->onlyTrashed()->count();
            } else {
                $nroProyectos = Auth::user()->proyectos->count();
            }
            if (Auth::user()->can('gestionar_usuarios')) {
                $nroUsuarios = User::count();
            }
            if (Auth::user()->can('crear_informes')) {
                $nroInformes = Informe::count();
            }
            return view('welcome', compact('nroProyectos', 'nroProyectosArch', 'nroUsuarios', 'nroInformes'));
        } else {
            return redirect('login');
        }
    }

    public function about()
    {
        return view('about');
    }

    /*public function __construct()
    {
        $this->middleware('auth');
    }
    */
}
