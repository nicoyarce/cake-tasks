@extends('layouts.master')
@section('content')
@include('layouts.errors')
<link href="/css/signin.css" rel="stylesheet">
<form method="POST" action="{{action('RegistrationController@store')}}"class="form-signin">
    {{csrf_field()}}
    <h1 class="h3 mb-3 font-weight-normal">Editar datos usuario</h1>
    
    <label for="name" class="sr-only">Nombre</label>
    <input type="name" id="name" name="name" class="form-control" placeholder="Nombre" required autofocus>

    <label for="email" class="sr-only">Email</label>
    <input type="email" id="email" name="email" class="form-control" placeholder="Email" required autofocus>

    <label for="password" class="sr-only">Contraseña</label>
    <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required>

    <label for="password_confirmation" class="sr-only">Confirme Contraseña</label>
    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirme Contraseña" required>
    <!--<div class="checkbox mb-3">
        <label>
            <input type="checkbox" value="remember-me"> Recuerdame
        </label>
    </div>-->
    <button class="btn btn-lg btn-primary btn-block" type="submit">Registrarse</button>
</form>
@endsection
