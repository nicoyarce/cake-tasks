<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Proyecto;
use Freshwork\ChileanBundle\Rut;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{    
    public function __construct()
    {
        $this->middleware('auth');        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */  
    public function index()
    {
        $usuarios = User::all();
        return view('usuarios.index', compact('usuarios'));
    }
    
    public function create(){        
        $proyectos = Proyecto::all();
        $roles = Role::all();        
        return view('usuarios.create', compact('proyectos','roles'));
    }

    public function store(Request $request){ 
        $this->validate($request, [
            'nombre' => 'required|min:2',
            'run' => 'required|unique:users,run|cl_rut',
            'password' => 'required|confirmed',
            'role_id' => 'required'
        ],
        ['cl_rut' => 'El RUN ingresado no es valido']);
        $rol = Role::find($request->role_id);
        $user = new User;
        $user->nombre = $request->nombre;
        $user->cargo = $request->cargo;
        $user->run = Rut::parse($request->run)->format(Rut::FORMAT_COMPLETE);
        $user->password = bcrypt($request->password);       
        $user->save();
        $user->assignRole($rol->name);
        $user->proyectos()->attach($request->listaProyectos);        
        flash('Registrado correctamente')->success();
        return redirect('users')->with('idUserMod', $user->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $usuario)
    {
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $usuario = User::find($id);
        $proyectos = Proyecto::all();        
        $roles = Role::all();
        return view('usuarios.edit', compact('usuario', 'proyectos', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $idUsuario){        
        $rol = Role::find($request->role_id);
        $usuarioNuevo = User::find($idUsuario);

        $usuarioNuevo->nombre = $request->nombre;
        $usuarioNuevo->cargo = $request->cargo;      
        $usuarioNuevo->run = Rut::parse($request->run)->format(Rut::FORMAT_COMPLETE);
        if(!is_null($request->password)){
            $usuarioNuevo->password = bcrypt($request->password);
        }
        $usuarioNuevo->save();
        $usuarioNuevo->syncRoles($rol->name);
        $usuarioNuevo->proyectos()->sync($request->listaProyectos);       
        flash('Usuario actualizado')->success();
        return redirect('users')->with('idUserMod', $usuarioNuevo->id);
    }    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {              
        $temp = User::where('id', $id)->first()->delete();        
        flash('Usuario eliminado')->success(); 
        return redirect('users');
    }

    public function destroySelected(Request $request)
    {              
        User::destroy($request->eliminar);        
        flash('Usuarios eliminados')->success(); 
        return redirect('users');
    }

}
