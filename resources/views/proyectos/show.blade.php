@extends('layouts.master')
@section('content')
@include('layouts.errors')
<table class="table">
    <thead>
        <tr>
            <th>NOMBRE</th>
            <th>FIR<br>&nbsp;</th>
            <th>FTR<br>Original</th>
            <th>FTR<br>Modificada</th>
            <th>ATRASO<br>[días]</th>
            <th>AVANCE<br>[%]</th>
            <th><a type="button" class="btn btn-primary float-right" href="/proyectos" title="">Atrás <i class="fas fa-arrow-left "></i></a></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="h4">{{ $proyecto->nombre }}</td>
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
@include('tareas.index', ['proyecto' => $proyecto])
</ul>
@endsection
