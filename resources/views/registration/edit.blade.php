@extends('layouts.master')
@section('content')
@include('layouts.errors')
<link href="/css/signin.css" rel="stylesheet">
<form method="POST" action="{{action('RegistrationController@store')}}"class="form-signin">
    {{csrf_field()}}
    <h1 class="h3 mb-3 font-weight-normal">Editar datos usuario</h1>
    
    <label for="name" class="sr-only">Nombre</label>
    <input type="name" id="name" name="name" class="form-control" required autofocus value="{{$usuario->nombre}}">

    <label for="run" class="sr-only">RUN</label>
    <input type="text" id="run" name="run" class="form-control" placeholder="RUN" readonly value="{{$usuario->rut}}"> 

    <label for="password" class="sr-only">Contraseña</label>
    <input type="text" id="password" name="password" class="form-control" required value="{{bcrypt($usuario->password)}}">

    <label for="listaProyectos" class="sr-only">Lista de proyectos (mantenga pulsado Ctrl para seleccionar varios)</label>
    <select multiple class="form-control" id="listaProyectos">
        @foreach ($proyectos as $proyecto)
            <option value="{{$proyecto->id}}">{{$proyecto->id}}</option>            
        @endforeach
    </select>
    
    <button class="btn btn-lg btn-primary btn-block" type="submit">Modificar usuario</button>
</form>
@endsection
