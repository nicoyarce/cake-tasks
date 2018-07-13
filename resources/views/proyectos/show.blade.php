@extends('layout')
@section('content')
<h1>Proyecto: {{$proyecto->nombre}}</h1>
<hr>
<ul class="list-group">
    <li class="list-group-item">Fecha inicio: {{$proyecto->fechainicio}}</li>
    <li class="list-group-item">Fecha termino: {{$proyecto->fechatermino}}</li>
    <li class="list-group-item">Porcentaje avance: {{$proyecto->avance}} %</li>
</ul>
<br>
<ul class="list-group">
    @if (count($proyecto->tareas)>0)
    <li class="list-group-item">
        <div class="row justify-content-between">            
            <div class="col-4">
                <p class="font-weight-bold">Lista de tareas:</p>
            </div>
            <div class="col-4">
                <a type="button" class="btn btn-success float-right"  href="/tareas/create" role="button">
                    Crear tarea <i class="fas fa-plus"></i>
                </a>
            </div>
        </div>
    </li>
    @foreach ($proyecto->tareas as $tarea)
    <li class="list-group-item">{{$tarea->nombre}}</li>
    @endforeach
    @else
    <li class="list-group-item">No hay tareas</li>
    @endif
</ul>
@endsection
