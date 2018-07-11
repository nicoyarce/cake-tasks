@extends('layout')
@section('content')
<br>
<h1>Tareas</h1>
<hr>
<a type="button" class="btn btn-success" href="/tareas/create" role="button">Crear tarea <i class="fas fa-plus"></i></a>	</a>
<hr>
@if(count($tareas)>0)
<table class="table">
	<thead>
		<tr>
			<th>ID</th>
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
				<td>{{$tarea->id}}</td>
				<td><a href="/tareas/{{$tarea->id}}">{{$tarea->nombre}}</a></td>
				<td>{{ Carbon::parse($tarea->fechainicio)->format('d/m/Y')}}</td>
				<td>{{ Carbon::parse($tarea->fechatermino)->format('d/m/Y')}}</td>
				<td>{{$tarea->avance}} %</td>
				<td>
					<a type="button" class="btn btn-primary"
					href="/tareas/{{$tarea->id}}/edit">
						<i class="fas fa-pen"></i></a>					
				</td>
				<td>
					<form method="POST" action="{{action('TareasController@destroy', $tarea)}}"> 
						{{csrf_field()}}
						{{method_field('DELETE')}}
						<button type="submit" class="btn btn-danger" onclick="return confirm('Desea eliminar tarea')"><i class="fas fa-trash-alt"></i></a></button>
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
