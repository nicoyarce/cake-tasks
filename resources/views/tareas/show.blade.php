@extends('layouts.master')
@section('content')
<div class="row justify-content-between">
    <div class="col-4">
        <h2>Tarea: </h2>
        <h2>{{$tarea->nombre}}</h2>
    </div>
    <div class="col-4">
        <a type="button" class="btn btn-primary float-right" href="{{url()->previous()}}" title="">Atrás <i class="fas fa-arrow-left "></i></a>
    </div>
</div>
<ul class="list-group"> 
    <li class="list-group-item">Pertenece a: <b>{{$tarea->proyecto->nombre}}</b></li>   
    <li class="list-group-item">Area: {{$tarea->area->nombrearea}}</li>    
    <li class="list-group-item">Fecha inicio reparaciones: {{$tarea->fecha_inicio->format('d-M-Y')}}</li>
    <li class="list-group-item">Fecha termino reparaciones original: {{$tarea->fecha_termino_original->format('d-M-Y')}}</li>
    @if(! $tarea->atraso <= 0)
        <li class="list-group-item">Fecha termino reparaciones modificada: {{$tarea->fecha_termino->format('d-M-Y')}}</li>
        <li class="list-group-item">Atraso [días]: {{$tarea->atraso}}</li>
    @endif
    <li class="list-group-item">Porcentaje avance: {{$tarea->avance}} %</li>
</ul>
@endsection
