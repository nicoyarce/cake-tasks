@extends('layouts.master')
@section('content')
<div class="row justify-content-between">
    <div class="col-4">
        <h2>Proyecto: </h2>
        <h2>{{$proyecto->nombre}}</h2>
    </div>
    <div class="col-4">
        <a type="button" class="btn btn-primary float-right" href="/proyectos" title="">Atrás <i class="fas fa-arrow-left "></i></a>
    </div>
</div>
<hr>

<ul class="list-group">
    
    <li class="list-group-item">Fecha inicio reparaciones original: {{ $proyecto->fecha_inicio->format('d-M-Y')}}</li>
    <li class="list-group-item">Fecha término reparaciones original: {{ $proyecto->fecha_termino_original->format('d-M-Y')}}</li>
    <li class="list-group-item">Fecha término reparaciones modificada: 
        @if($proyecto->fecha_termino_original == $proyecto->fecha_termino)
        -
        @else
        {{ Carbon::parse($proyecto->fecha_termino)->format('d-M-Y')}}
        @endif
    </li>
    <li class="list-group-item">Atraso [días]: 
        @if(Carbon::parse($proyecto->fecha_termino_original)->lte(Carbon::parse($proyecto->fecha_termino)))
                -
                @else
                {{$proyecto->atraso}}
                @endif
    </li>
    <li class="list-group-item">Porcentaje avance: {{$proyecto->avance}} %</li>    
</ul>
<br>

@include('tareas.index', ['proyecto' => $proyecto])

</ul>
@endsection
