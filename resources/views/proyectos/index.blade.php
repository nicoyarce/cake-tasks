@extends('layouts.master')
@section('content')
<div class="row justify-content-between">
	<div class="col-4">
		<h1>Proyectos</h1>
	</div>
	<div class="col-4">
		<a type="button" class="btn btn-success float-right" href="{{action('ProyectosController@create')}}" role="button">Crear proyecto
			<i class="fas fa-plus"></i>
		</a>
	</div>
</div>
<hr>
@if(count($proyectos)>0)
<table class="table">
	<thead>
		<tr>
			<th>Nombre</th>
			<th>Fecha Inicio</th>
			<th>Fecha Termino</th>
			<th>Avance</th>
			<th>Editar</th>
			<th>Borrar</th>
		</tr>
	</thead>
	
	<tbody>
		@foreach ($proyectos as $proyecto)
		<tr>
			<td><a href="/proyectos/{{$proyecto->id}}">{{$proyecto->nombre}}</a></td>
			<td>{{ Carbon::parse($proyecto->fecha_inicio)->format('d/m/Y')}}</td>
			<td>{{ Carbon::parse($proyecto->fecha_termino)->format('d/m/Y')}}</td>
			<td>{{$proyecto->avance}} %</td>
			<td>
				<a href="{{action('ProyectosController@edit', $proyecto['id'])}}" type="button" class="btn btn-primary" >
					<i class="fas fa-pen"></i>
				</a>
			</td>
			<td>
				<form method="POST" action="{{action('ProyectosController@destroy', $proyecto)}}">
					{{csrf_field()}}
					{{method_field('DELETE')}}
					<button type="submit" class="btn btn-danger" onclick="return confirm('¿Desea eliminar el proyecto?')">
				<i class="fas fa-trash-alt"></i></a>
				</button>
			</form>
		</td>
	</tr>
	@endforeach
	
</tbody>
</table>
@else
<h1 align="center">No hay proyectos</h1>
@endif
@endsection
