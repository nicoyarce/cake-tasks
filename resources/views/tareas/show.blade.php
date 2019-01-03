@extends('layouts.master')
@section('content')
@include('layouts.errors')
<div class="row justify-content-between">
    <h2 class="col-11">{{$tarea->nombre}}
        @if($tarea->critica)
            <span class="badge badge-pill badge-warning">Crítica</span>
        @endif
    </h2>
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
    <div class="form-group">        
            <h4>Observaciones:</h4>
            <ul>
                @if(count($tarea->observaciones)>0)
                @foreach ($tarea->observaciones as $observacion)
                    <li>{{$observacion}}</li>
                @endforeach
                @else
                    <li><h5>No hay datos.</h5></li>
                @endif
            </ul>         
    </div>    
</div>
<div class="row">
    <div class="form-group">
        <h4>Tareas hijas:</h4>        
        <ul>
            @if(count($tareasHijas)>0)
            @foreach ($tareasHijas as $tareaHija)
                <li>{{$tareaHija->nombre}}</li>
            @endforeach         
            @else
                <li><h5>No hay datos.</h5></li>
            @endif
        </ul>
    </div>
</div>
@endsection
