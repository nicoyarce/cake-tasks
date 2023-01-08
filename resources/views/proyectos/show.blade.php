@extends('layouts.master')
@section('content')
@section('tituloPagina', 'Lista Tareas - ' . $proyecto->nombre)
@include('layouts.errors')
<table class="table table-hover">
    <thead>
        <tr>
            <th>NOMBRE<br>PROYECTO</th>
            <th>Fecha Inicio Proyecto<br>&nbsp;</th>
            <th>Fecha Término Proyecto<br>Original</th>
            <th>Fecha Término Proyecto<br>Modificada</th>
            <th>ATRASO<br>[días]</th>
            <th>AVANCE<br>[%]</th>
            @if(!is_null($proyecto->deleted_at))
                <th><a type="button" class="btn btn-primary float-right" href="/proyectosArchivados" title="">Atrás <i class="fas fa-arrow-left "></i></a></th>
            @else
                <th><a type="button" class="btn btn-primary float-right" href="/proyectos" title="">Atrás <i class="fas fa-arrow-left "></i></a></th>
            @endif
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><b>{{ $proyecto->nombre }}</b>
                @if(!is_null($proyecto->deleted_at))
                    <span class="badge badge-pill badge-warning">Archivado</span>
                @endif
            </td>
            <td>{{ $proyecto->fecha_inicio->format('d-M-Y') }}</td>
            <td>{{ $proyecto->fecha_termino_original->format('d-M-Y') }}</td>
            <td>
                @if($proyecto->fecha_termino_original == $proyecto->fecha_termino)
                -
                @else
                {{ $proyecto->fecha_termino->format('d-M-Y')}}
                @endif
            </td>
            <td>
                @if($proyecto->fecha_termino_original->gte($proyecto->fecha_termino))
                -
                @else
                {{$proyecto->atraso}}
                @endif
            </td>
            <td>{{$proyecto->avance}}</td>
        </tr>
    </tbody>
</table>
<br>
@include('tareas.index', ['proyecto' => $proyecto, 'propiedades' => $propiedades])
@endsection

