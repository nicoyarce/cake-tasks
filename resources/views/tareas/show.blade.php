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
    <div class="form-group col-6 pt-5">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Área</h5>    
            <p class="card-text">{{$tarea->area->nombrearea}}</p>
          </div>
        </div>
    </div>
    <div class="form-group col-6">
        <h4>Observaciones</h4>
        <div class="list-group">        
            @if(count($tarea->observaciones)>0)
                @foreach ($tarea->observaciones()->get() as $observaciones)
                <div class="list-group-item flex-column align-items-start">
                    <p class="mb-1">{{$observaciones->contenido}}</p>
                    <small>{{$observaciones->created_at->format('d-M-Y H:m')}}</small>
                </div>
                @endforeach
            @else
            <div class="list-group-item ">
                <p class="mb-1">No hay datos.</p>
            </div>
            @endif        
        </div>      
    </div>
</div>

<div class="row">     
    <h4>Carta gantt detalle</h4>   
</div>
<hr>
<div class="row">    
    
</div>
@endsection
