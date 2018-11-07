@extends('layouts.master')
@section('content')
@include('layouts.errors')
<div class="row">    
    <h1>{{$tarea->nombre}}</h1>
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
