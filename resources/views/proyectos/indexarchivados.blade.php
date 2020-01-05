@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col-6">
        <h1>Proyectos Terminados</h1>
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
			<th class="text-center">AVANCE<br>REAL[%]</th>
			<th class="text-center">AVANCE<br>PROGRAMADO[%]</th>
            @can('ver_graficos')
            <th>Ver gráfico</th>
            @endcan
            @can('ver_informes')
            <th>Ver informes</th>
            @endcan            
            @can('borrar_proyectos')            
            <th>Restaurar</th>                                 
            <th>Borrar</th>   
            @endcan             
        </tr>
    </thead>    
    <tbody>
        @foreach ($proyectos as $proyecto)
        <tr>
            @if($proyecto->colorAtraso == "VERDE" || $proyecto->avance == 100)
			<td class="bg-success">
			@elseif($proyecto->colorAtraso == "AMARILLO")
			<td class="fondo-amarillo">
			@elseif($proyecto->colorAtraso == "NARANJO")
			<td class="fondo-naranjo">
			@elseif($proyecto->colorAtraso == "ROJO")
			<td class="bg-danger">
			@endif
            <a class="text-dark" href="{{action('ProyectosController@showArchivados', $proyecto['id'])}}">{{$proyecto->nombre}}</a>
            </td>            
            <td style="width: 12%" >{{ $proyecto->fecha_inicio->format('d-M-Y') }}</td>
            <td style="width: 12%">{{ $proyecto->fecha_termino_original->format('d-M-Y') }}</td>
            <td style="width: 12%">
                @if($proyecto->fecha_termino_original == $proyecto->fecha_termino)
                -
                @else
					@if(empty($proyecto->autorUltimoCambioFtr))
						{{ $proyecto->fecha_termino->format('d-M-Y')}}
					@else					
						<a data-toggle="tooltip" data-placement="bottom" data-html="true" 
							title="Modificado por: {{$proyecto->autorUltimoCambioFtr->nombre}} <br> Fecha: <br> {{$proyecto->fecha_ultimo_cambio_ftr->format('d-M-Y H:i:s')}}">
							{{ $proyecto->fecha_termino->format('d-M-Y')}}
						</a>
					@endif
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
            <td>{{$proyecto->porcentajeAtraso}}</td>
            @can('ver_graficos')
            <td>
                <a href="{{action('GraficosController@vistaGraficoArchivados', $proyecto['id'])}}" type="button" class="btn btn-primary" >
                    <i class="fas fa-chart-pie"></i>
                </a>
            </td>
            @endcan
            @can('ver_informes')
            <td>
                <a href="{{action('InformesController@vistaListaInformesArchivados', $proyecto['id'])}}" type="button" class="btn btn-info" >
                    <i class="fas fa-file-alt"></i>
                </a>
            </td>
            @endcan            
            @can('borrar_proyectos') 
            <td>
                <a href="{{action('ProyectosController@restaurar', $proyecto['id'])}}" type="button" class="btn btn-warning" >
                    <i class="fas fa-redo"></i>
                </a>
            </td> 
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
{{$proyectos->links()}}
@else
<hr>
<h3 class="text-center">No hay proyectos archivados</h3>
@endif
@endsection
