@extends('layouts.master')
@section('content')
@include('layouts.errors')

<link href="/css/signin.css" rel="stylesheet">
<form method="POST" action="{{action('SessionsController@store')}}"class="form-signin">
    {{csrf_field()}}
    <h1 class="h3 mb-3 font-weight-normal">Iniciar sesion</h1>
    
    <label for="email" class="sr-only">Email</label>
    <input type="email" id="email" name="email" class="form-control" placeholder="Email" required autofocus>

    <label for="password" class="sr-only">Contraseña</label>
    <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required>

    <!--<div class="checkbox mb-3">
        <label>
            <input type="checkbox" value="remember-me"> Recuerdame
        </label>
    </div>-->
    <button class="btn btn-lg btn-primary btn-block" type="submit">Iniciar sesion</button>
</form>
@endsection
