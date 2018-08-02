<div class="row justify-content-between">
	<div class="col-4">
		<h3>Tareas - Total: {{count($tareas)}}</h3>
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
			<th>NOMBRE</th>
			<th>FIR<br>Original</th>
			<th>FTR<br>Original</th>
			<th>FTR<br>Modificada</th>
			<th>ATRASO<br>[días]</th>
			<th>AVANCE<br>[%]</th>
		</tr>
	</thead>
	
	<tbody>
		@foreach ($tareas as $tarea)
		<tr>
			@if($tarea->atraso > 7)
				<td class="list-group-item-danger"><a href="{{action('TareasController@show', $tarea['id'])}}">{{$tarea->nombre}}</a></td>				
			@elseif($tarea->atraso <= 7 && $tarea->atraso > 0)
				<td class="list-group-item-warning"><a href="{{action('TareasController@show', $tarea['id'])}}">{{$tarea->nombre}}</a></td>
			@elseif($tarea->atraso <= 0)
				<td class="list-group-item-success"><a href="{{action('TareasController@show', $tarea['id'])}}">{{$tarea->nombre}}</a></td>
			@endif			
			<td>{{ $tarea->fecha_inicio->format('d-M-Y')}}</td>
			<td >{{ $tarea->fecha_termino_original->format('d-M-Y') }}</td>
			<td>
				@if($tarea->fecha_termino_original == $tarea->fecha_termino)
				-
				@else
				{{ $tarea->fecha_termino->format('d-M-Y')}}
				@endif
			</td>
			<td>
				@if($tarea->fecha_termino_original->gte($tarea->fecha_termino))
				-
				@else
				{{$tarea->atraso}}
				@endif
			</td>
			<td>{{$tarea->avance}}</td>
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
