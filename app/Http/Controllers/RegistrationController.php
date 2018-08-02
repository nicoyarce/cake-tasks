<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Proyecto;
use App\Role;

use App\Http\Requests\RegistrationRequest;

class RegistrationController extends Controller
{   
    public function create(){        
        $proyectos = Proyecto::all();
        $roles = Role::all();
        return view('registration.create', compact('proyectos','roles'));
    }

    public function store(RegistrationRequest $request){
        $user = User::create([
            'name'=>request('name'),
            'email'=>request('email'),
            'password'=>bcrypt(request('password'))
        ]);        
        $user->role_id = $request->rol;
        
        auth()->login($user);
        flash('Registrado correctamente')->success();
        return redirect()->home();
    }

}
