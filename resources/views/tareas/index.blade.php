@extends('layouts.master')
@section('content')
<div class="row justify-content-between">
	<div class="col-4">
		<h1>Tareas</h1>
	</div>
	<div class="col-4">
		<a type="button" class="btn btn-success float-right" href="{{action('TareasController@create')}}" role="button">Crear tarea
			<i class="fas fa-plus"></i>
		</a>
	</div>
</div>
<hr>
@if(count($tareas)>0)
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
		@foreach ($tareas as $tarea)
		<tr>
			<td><a href="/tareas/{{$tarea->id}}">{{$tarea->nombre}}</a></td>
			<td>{{ Carbon::parse($tarea->fecha_inicio)->format('d/m/Y')}}</td>
			<td>{{ Carbon::parse($tarea->fecha_termino)->format('d/m/Y')}}</td>
			<td>{{$tarea->avance}} %</td>
			<td>
				<a href="{{action('TareasController@edit', $tarea['id'])}} "type="button" class="btn btn-primary">
				<i class="fas fa-pen"></i></a>
			</td>
			<td>
				<form method="POST" action="{{action('TareasController@destroy', $tarea)}}">
					{{csrf_field()}}
					{{method_field('DELETE')}}
				<button type="submit" class="btn btn-danger" onclick="return confirm('¿Desea eliminar la tarea?')"><i class="fas fa-trash-alt"></i></a></button>
			</form>
		</td>
	</tr>
	@endforeach
	
</tbody>
</table>
@else
<h1 align="center">No hay tareas</h1>
@endif
@endsection
