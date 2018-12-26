@extends('layouts.master')
@section('content')
@include('layouts.errors')
<div class="row justify-content-between">
    <h2 class="col-11">{{$tarea->nombre}}</h1>
    <div class="col-1">        
        <a type="button" class="btn btn-primary float-right" href="{{url()->previous()}}">Atrás <i class="fas fa-arrow-left "></i></a>        
    </div>
</div>
<hr>
<div class="row">
    <ul class="list-inline">
        <li class="list-inline-item"><h4>Area:</h4></li>
        <li class="list-inline-item"><p>{{$tarea->area->nombrearea}}</p></li>
    </ul>
</div>
<div class="row">
    <ul class="list-inline">
        <li class="list-inline-item"><h4>Observaciones:</h4></li>
        <li class="list-inline-item">{{$tarea->observaciones}}</li>
    </ul>  
</div>
<div class="row">
    <h4>Tareas hijas:</h4>
</div>
<div class="row">
    @if(count($tareasHijas)>0)
    <ul>
        @foreach ($tareasHijas as $tareaHija)
            <li>{{$tareaHija->nombre}}</li>
        @endforeach       
    </ul>
    @else
        <h5>No hay datos.</h5>
    @endif
</div>
@endsection
