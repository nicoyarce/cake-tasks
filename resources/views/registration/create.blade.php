@extends('layouts.master')
@section('content')
@include('layouts.errors')
<form method="POST" action="{{action('RegistrationController@store')}}"class="form-signin">
    {{csrf_field()}}
    <h1 class="h3 mb-3 font-weight-normal">Crear usuario</h1>
    <div class="form-group">
        <label for="name" class="col-2 col-form-label">Nombre</label>
        <input type="name" id="name" name="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="run" class="col-2 col-form-label">RUN</label>
        <input type="text" id="run" name="run" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="password" class="col-2 col-form-label">Contraseña</label>
        <input type="password" id="password" name="password" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="password_confirmation" class="col-2 col-form-label">Confirme Contraseña</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="rol" class="col-2 col-form-label">Rol de usuario</label>
        <select class="form-control" name="rol" id="rol">
            <@foreach ($roles as $rol)
            <option value="{{$rol->id}}">{{$rol->descripcion}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="listaProyectos" class="col-form-label">Lista de proyectos (mantenga pulsado Ctrl para seleccionar varios)</label>
        <select multiple class="form-control" id="listaProyectos">
            @foreach ($proyectos as $proyecto)
            <option value="{{$proyecto->id}}">{{$proyecto->nombre}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group text-center">
        <button class="btn btn-primary" type="submit">Crear usuario</button>
    </div>
</form>
@endsection
