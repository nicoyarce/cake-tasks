<div class="row">	
	<div class="col-10">
		<h4><b>Tareas</b> - Total: {{count($proyecto->tareas)}}</h4>
	</div>
	@can('crear_tareas')
	<div class="col-2">		
		<a href="{{action('TareasController@create', $proyecto->id)}}" type="submit" class="btn btn-success float-right" role="button">Crear tarea <i class="fas fa-plus"></i></a>		
	</div>
	@endcan
</div>
@if(count($proyecto->tareas)>0)
<table id="tablaTareas" class="table table-hover mt-2">
	<thead class="thead-light">
		<tr>
			<th>NOMBRE<br>TAREA</th>
			<th>FIT<br>&nbsp;</th>
			<th>FTT<br>Original</th>
			<th>FTT<br>Modificada</th>
			<th>ATRASO<br>[días]</th>
			<th>AVANCE<br>[%]</th>
			@can('modificar_tareas')
			<th>Editar</th>
			@endcan
			@can('borrar_tareas')
			<th>Eliminar</th>
			@endcan
		</tr>
	</thead>	
	<tbody>
		@foreach ($tareas as $tarea)
		<tr id="{{$tarea->id}}">
			@if($tarea->colorAtraso == "VERDE" || $tarea->avance == 100)
				<td class="bg-success">{{$tarea->nombre}}</td>
			@elseif($tarea->colorAtraso == "AMARILLO")
				<td class="fondo-amarillo">{{$tarea->nombre}}</td>				
			@elseif($tarea->colorAtraso == "NARANJO")
				<td class="fondo-naranjo">{{$tarea->nombre}}</td>
			@elseif($tarea->colorAtraso == "ROJO")
				<td class="bg-danger">{{$tarea->nombre}}</td>
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
				@if($tarea->atraso==0)
				-
				@else
				{{$tarea->atraso}}
				@endif
			</td>
			<td>{{$tarea->avance}}</td>
			@can('modificar_tareas')
			<td>
				<a href="{{action('TareasController@edit', $tarea['id'])}} "type="button" class="btn btn-primary">
				<i class="fas fa-pen"></i></a>
			</td>
			@endcan
			@can('borrar_tareas')
			<td>
				<form method="POST" action="{{action('TareasController@destroy', $tarea)}}">
					{{csrf_field()}}
					{{method_field('DELETE')}}
				<button type="submit" class="btn btn-danger" onclick="return confirm('¿Desea eliminar la tarea?')"><i class="fas fa-trash-alt"></i></button>
			</form>
			@endcan
		</td>
	</tr>
	@endforeach
</tbody>
</table>
@else
<h4 align="center">No hay tareas</h4>
@endif
<script src="/js/jquery.stickytableheaders.min.js"></script>

<script>
	$('#tablaTareas').stickyTableHeaders();
	@if (session('idTareaMod'))
		window.scrollTo(0, $("#{{session('idTareaMod')}}").offset().top-100);
		$(document).ready(function(){
			$("#{{session('idTareaMod')}}").effect("highlight", {}, 3000);
		});
	@endif     
</script>
