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
    <h3>Tareas hijas:</h3>
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
