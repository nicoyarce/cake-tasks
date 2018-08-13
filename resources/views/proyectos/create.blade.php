@extends('layouts.master')
@section('content')
<div class="row justify-content-between">
    <h1>Crear proyecto</h1>
    <div class="col-4">
        <a type="button" class="btn btn-primary float-right" href="/proyectos">Atrás <i class="fas fa-arrow-left "></i></a>
    </div>    
</div>
<hr>
@include('layouts.errors')
<form class="form-horizontal" method="POST" action="/proyectos">
    {{csrf_field()}}
    <div class="form-group">
        <label for="nombre">Nombre</label>       
        <input type="text" class="form-control" id="nombre" required name="nombre">        
    </div>
    <div class="form-row">
        <div class="form-group col-6">
            <label for="fecha_inicio">FIR</label>            
            <input class="form-control" type="date" id="fecha_inicio" required name="fecha_inicio">
        </div>
        <div class="form-group col-6">
            <label for="fecha_termino">FTR</label>
            <input class="form-control" type="date" id="fecha_termino" required name="fecha_termino">
        </div>
    </div>    
    <div class="form-group text-center">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>
@endsection
