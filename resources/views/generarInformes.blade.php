@extends('layouts.master')
@section('content')
<div class="row justify-content-between">
    <h1>Generar informe proyecto</h1>
    <div class="col-4">
        <a type="button" class="btn btn-primary float-right" href="/proyectos">Atrás <i class="fas fa-arrow-left "></i></a>
    </div>
</div>
<hr>
@include('layouts.errors')
<form id="formulario" class="form-horizontal" action="{{action('InformesController@generarInforme')}}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
        <div class="form-group col-8 offset-2">
            <label class="h4" for="archivo">Seleccione proyecto</label>
            <select class="form-control" id="proyecto_id" required name="proyecto_id">
                <option value="" disabled selected>Elija una opción</option>
                @foreach ($proyectos as $proyecto)
                <option value="{{$proyecto->id}}">{{$proyecto->nombre}}</option>
                @endforeach
            </select>
        </div> 
        {{-- Opciones informe --}}
        <div class="form-check col-8 offset-2">
        <input type="checkbox" name="opciones" value="grafico">
            <label class="form-check-label">
                Incluir gráfico
            </label>
        </div>        
        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary">Generar informe</button>
        </div>        
</form>
@endsection
