<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

use App\Http\Requests\RegistrationRequest;

class RegistrationController extends Controller
{
    public function __construct(){
        $this->middleware('guest');
    }

    public function create(){
        return view('registration.create');
    }

    public function store(RegistrationRequest $request){
        $user = User::create([
            'name'=>request('name'),
            'email'=>request('email'),
            'password'=>bcrypt(request('password'))
        ]);

        auth()->login($user);
        flash('Registrado correctamente')->success();
        return redirect()->home();
    }

}
