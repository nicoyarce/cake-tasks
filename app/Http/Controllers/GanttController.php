<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tarea;
use App\Link;

class GanttController extends Controller
{
    public function get(){
        $tasks = new Tarea();
        $links = new Link(); 
        return response()->json([
            "data" => $tareas->all(),
            "links" => $links->all()
        ]);
    }
}
