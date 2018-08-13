@extends('layouts.master')
@section('content')
<div class="row">
	<div class="col-8">
		<h1>Proyectos</h1>
	</div>
	@can('crear_proyectos')
	<div class="col-2">
		<a type="button" class="btn btn-primary float-right" href="/proyectos/cargar" role="button">Cargar archivo
			<i class="fas fa-upload"></i>
		</a>
	</div>
	<div class="col-2">
		<a type="button" class="btn btn-success float-right" href="{{action('ProyectosController@create')}}" role="button">Crear proyecto
			<i class="fas fa-plus"></i>
		</a>
	</div>
	@endcan
</div>
@if(count($proyectos)>0)
<table class="table">
	<thead>
		<tr>
			<th>NOMBRE<br>PROYECTO</th>
			<th>FIR<br>&nbsp;</th>
			<th>FTR<br>Original</th>
			<th>FTR<br>Modificada</th>
			<th>ATRASO<br>[días]</th>
			<th>AVANCE<br>[%]</th>
			@can('ver_graficos')
			<th>Ver gráfico</th>
			@endcan
			@can('modificar_proyectos')
			<th>Editar</th>
			@endcan
			@can('borrar_proyectos')
			<th>Borrar</th>
			@endcan
		</tr>
	</thead>
	
	<tbody>
		@foreach ($proyectos as $proyecto)
		<tr>
			@if($proyecto->colorAtraso == "ROJO")
			<td class="table-danger"><a class="text-warning" href="{{action('ProyectosController@show', $proyecto['id'])}}">{{$proyecto->nombre}}</a></td>
			@elseif($proyecto->colorAtraso == "NARANJO")
			<td class="table-warning"><a href="{{action('ProyectosController@show', $proyecto['id'])}}">{{$proyecto->nombre}}</a></td>
			@elseif($proyecto->colorAtraso == "VERDE")
			<td class="table-success"><a href="{{action('ProyectosController@show', $proyecto['id'])}}">{{$proyecto->nombre}}</a></td>
			@endif
			<td >{{ $proyecto->fecha_inicio->format('d-M-Y') }}</td>
			<td >{{ $proyecto->fecha_termino_original->format('d-M-Y') }}</td>
			<td>
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
			@can('ver_graficos')
			<td>
				<a href="{{action('GraficosController@show', $proyecto['id'])}}" type="button" class="btn btn-primary" >
					<i class="fas fa-chart-pie"></i>
				</a>
			</td>
			@endcan
			@can('modificar_proyectos')
			<td>
				<a href="{{action('ProyectosController@edit', $proyecto['id'])}}" type="button" class="btn btn-primary" >
					<i class="fas fa-edit"></i>
				</a>
			</td>
			@endcan
			@can('borrar_proyectos')
			<td>
				<form method="POST" action="{{action('ProyectosController@destroy', $proyecto)}}">
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
<h1 align="center">No hay proyectos</h1>
@endif
@endsection
