<?php

namespace App\Http\Controllers;

use App\Categoria;
use Illuminate\Http\Request;

class CategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Categoria::paginate(10);
        return view('categorias.index')->with('categorias', $categorias);
    }

    public function indexConModal()
    {
        $abrir_modal = 1;
        $categorias = Categoria::paginate(10);
        return view('categorias.index', compact('categorias', 'abrir_modal'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $categoria = new Categoria;
        $categoria->nombre = $request->nombre;
        $categoria->save();
        flash('Categoría registrada')->success();
        return redirect('categorias');
    }
    
    public function edit($id)
    {
        $categorias = Categoria::paginate(10);
        $editar = Categoria::find($id);
        return view('categorias.index', compact('categorias', 'editar'));
    }

    public function update(Request $request, $id)
    {
        $categoriaNueva = Categoria::find($id);
        $categoriaNueva->nombre = $request->nombre;
        $categoriaNueva->save();
        flash('Categoría actualizada')->success();
        return redirect('categorias');
    }

    public function destroy($id)
    {
        $temp = Categoria::where('id', $id)->first()->delete();
        flash('Categoría eliminada')->success();
        return redirect('categorias');
    }
}
