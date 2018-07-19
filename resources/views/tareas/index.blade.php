<div class="row justify-content-between">
	<div class="col-4">
		<h3>Tareas</h3>		
	</div>		
		<div class="col-4">			
			<form method="POST" action="/tareas/create/">
				{{csrf_field()}}
				<input type="text" hidden name="proyecto_id" value="{{$proyecto->id}}">				
				<button type="submit" class="btn btn-success float-right" role="button">Crear tarea <i class="fas fa-plus"></i></button>
			</form>
		</div>
	</div>

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

					<td><a href="{{action('TareasController@show', $tarea['id'])}}">{{$tarea->nombre}}</a></td>
					<td>{{ $tarea->fecha_inicio->format('d-M-Y')}}</td>
					<td>{{ $tarea->fecha_termino->format('d-M-Y')}}</td>
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
<h4 align="center">No hay tareas</h4>
@endif
