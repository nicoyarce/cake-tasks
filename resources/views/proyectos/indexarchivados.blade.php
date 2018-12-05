@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col-6">
        <h1>Proyectos Archivados</h1>
    </div>    
</div>
@if(count($proyectos)>0)
<table class="table table-hover">
    <thead>
        <tr>
            <th>NOMBRE<br>PROYECTO</th>
            <th>FIR<br>&nbsp;</th>
            <th>FTR<br>Original</th>
            <th>FTR<br>Modificada</th>
            <th>ATRASO<br>[días]</th>
            <th>AVANCE<br>[%]</th>
            <th>Restaurar</th>                                 
            <th>Borrar definitivamente</th>
        </tr>
    </thead>    
    <tbody>
        @foreach ($proyectos as $proyecto)
        <tr>
            @if($proyecto->colorAtraso == "VERDE" || $proyecto->avance == 100)
            <td class="bg-success"><a class="text-dark">{{$proyecto->nombre}}</a></td>
            @elseif($proyecto->colorAtraso == "AMARILLO")
            <td class="fondo-amarillo"><a class="text-dark">{{$proyecto->nombre}}</a></td>
            @elseif($proyecto->colorAtraso == "NARANJO")
            <td class="fondo-naranjo"><a class="text-dark">{{$proyecto->nombre}}</a></td>
            @elseif($proyecto->colorAtraso == "ROJO")
            <td class="bg-danger"><a class="text-dark">{{$proyecto->nombre}}</a><p/td>
            @endif
            <td style="width: 12%" >{{ $proyecto->fecha_inicio->format('d-M-Y') }}</td>
            <td style="width: 12%">{{ $proyecto->fecha_termino_original->format('d-M-Y') }}</td>
            <td style="width: 12%">
                @if($proyecto->fecha_termino_original == $proyecto->fecha_termino)
                -
                @else
                {{ $proyecto->fecha_termino->format('d-M-Y')}}
                @endif
            </td>
            <td>
                @if($proyecto->atraso==0)
                -
                @else
                {{$proyecto->atraso}}
                @endif
            </td>
            <td>{{$proyecto->avance}}</td> 
            <td>
                <a href="{{action('ProyectosController@restaurar', $proyecto['id'])}}" type="button" class="btn btn-warning" >
                    <i class="fas fa-redo"></i>
                </a>
            </td>             
            @can('borrar_proyectos')        
            <td>
                <form method="POST" action="{{action('ProyectosController@eliminarPermanente', $proyecto['id'])}}">
                    {{csrf_field()}}
                    {{method_field('DELETE')}}
                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Desea eliminar el proyecto?. Esto también eliminará todas las tareas del proyecto.')">
                        <i class="fas fa-trash-alt"></i></a>
                    </button>
                </form>
            </td>
            @endcan     
        </tr>
    @endforeach
    </tbody>
</table>
@else
<hr>
<h3 class="text-center">No hay proyectos archivados</h3>
@endif
@endsection
