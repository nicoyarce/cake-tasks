@extends('layouts.master')
@section('content')
<h1>Crear proyecto</h1>
<hr>
@include('layouts.errors')
<form method="POST" action="/proyectos">
    {{csrf_field()}}

    <div class="form-group">
        <label for="nombre" class="col-6 col-form-label">Nombre</label>
        <div class="col-10">
            <input type="text" class="form-control" id="nombre" required name="nombre">
        </div>
    </div>

    <div class="form-group">
        <label for="fecha_inicio" class="col-6 col-form-label">Fecha inicio reparaciones</label>
        <div class="col-10">
            <input class="form-control" type="date" id="fecha_inicio" required name="fecha_inicio">
        </div>
    </div>

    <div class="form-group">
        <label for="fecha_termino" class="col-6 col-form-label">Fecha término reparaciones</label>
        <div class="col-10">
            <input class="form-control" type="date" id="fecha_termino" required name="fecha_termino">
        </div>
    </div>    

    <div class="form-group text-center">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>
@endsection
