<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('roles.index')->with('roles', $roles);
    }

    public function indexConModal()
    {
        $abrir_modal = 1;
        $roles = Role::all();
        return view('roles.index', compact('roles', 'abrir_modal'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nombre = $request->nombre;
        $role = Role::create(['name' => $nombre]);
        $role->permissions()->attach($request->permisos);
        $role->save();
        flash('Rol registrado')->success();
        return redirect('roles');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roles = Role::all();
        $editar = Role::findById($id);
        $permisos = Permission::all();
        $actuales = $editar->permissions()->get();
        return view('roles.index', compact('roles', 'editar', 'permisos', 'actuales'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rolNuevo = Role::findById($id);
        $rolNuevo->name = $request->nombre;
        $rolNuevo->permissions()->sync($request->permisos);        
        $rolNuevo->save();
        flash('Rol actualizado')->success();
        return redirect('roles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $temp = Role::findById('id', $id)->first()->delete();
        flash('Rol eliminado')->success();
        return redirect('roles');
    }
}
