@if(is_null($proyecto->deleted_at))
	{{-- Para tareas normales --}}
	<div class="row">
		<div class="col-10">
			<h4><b>Tareas</b> - Total: {{count($proyecto->tareas)}}</h4>
		</div>
		@can('crear_tareas')
			<div class="col-2 p-2">
				<a href="{{action('TareasController@create', $proyecto->id)}}" type="submit" class="btn btn-success float-right" role="button">Crear tarea <i class="fas fa-plus"></i></a>
			</div>
		@endcan
	</div>
	@if(count($proyecto->tareas)>0)
		<table id="tablaTareas" class="table table-hover mt-2">
			<thead class="thead-light" style="position: sticky;">
				<tr>
					<th>NOMBRE<br>TAREA</th>
					<th>FIT<br>&nbsp;</th>
					<th>FTT<br>Original</th>
					<th>FTT<br>Modificada</th>
					<th>ATRASO<br>[días]</th>
					<th>AVANCE<br>REAL[%]</th>
                    <th>AVANCE<br>PROYECTADO[%]</th>
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
						<td class="bg-success"><a class="text-dark" href="{{action('TareasController@show', $tarea['id'])}}">{{$tarea->nombre}}</a>
					@elseif($tarea->colorAtraso == "AMARILLO")
						<td class="fondo-amarillo"><a class="text-dark" href="{{action('TareasController@show', $tarea['id'])}}">{{$tarea->nombre}}</a>
					@elseif($tarea->colorAtraso == "NARANJO")
						<td class="fondo-naranjo"><a class="text-dark" href="{{action('TareasController@show', $tarea['id'])}}">{{$tarea->nombre}}</a>
					@elseif($tarea->colorAtraso == "ROJO")
						<td class="bg-danger"><a class="text-dark" href="{{action('TareasController@show', $tarea['id'])}}">{{$tarea->nombre}}</a>
					@endif
					@if($tarea->critica)
						<span class="badge badge-pill badge-warning">Crítica</span>
					@endif
					</td>
					<td style="width: 12%">{{ $tarea->fecha_inicio->format('d-M-Y')}}</td>
					<td style="width: 12%">{{ $tarea->fecha_termino_original->format('d-M-Y') }}</td>
					<td style="width: 12%">
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
                    <td>{{$tarea->porcentajeAtraso}}</td>
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
					</td>
					@endcan
				</tr>
				@endforeach
			</tbody>
		</table>
	@else
		<hr>
		<h3 class="text-center">No hay tareas</h3>
	@endif
@else
	{{-- Para tareas archivadas --}}
	<div class="row">
		<div class="col-10">
			<h4><b>Tareas</b> - Total: {{count($proyecto->tareas()->withTrashed()->get())}}</h4>
		</div>
	</div>
	@if(count($proyecto->tareas()->withTrashed()->get())>0)
		<table id="tablaTareas" class="table table-hover mt-2">
			<thead class="thead-light" style="position: sticky; top: 0;">
				<tr>
					<th>NOMBRE<br>TAREA</th>
					<th>FIT<br>&nbsp;</th>
					<th>FTT<br>Original</th>
					<th>FTT<br>Modificada</th>
					<th>ATRASO<br>[días]</th>
					<th>AVANCE<br>[%]</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($tareas as $tarea)
				<tr id="{{$tarea->id}}">
					@if($tarea->colorAtraso == "VERDE" || $tarea->avance == 100)
						<td class="bg-success"><a class="text-dark" {{-- href="{{action('TareasController@show', $tarea['id'])}}" --}}>{{$tarea->nombre}}</a>
					@elseif($tarea->colorAtraso == "AMARILLO")
						<td class="fondo-amarillo"><a class="text-dark" >{{$tarea->nombre}}</a>
					@elseif($tarea->colorAtraso == "NARANJO")
						<td class="fondo-naranjo"><a class="text-dark" >{{$tarea->nombre}}</a>
					@elseif($tarea->colorAtraso == "ROJO")
						<td class="bg-danger"><a class="text-dark" >{{$tarea->nombre}}</a>
					@endif
					@if($tarea->critica)
						<span class="badge badge-pill badge-warning">Crítica</span>
					@endif
					</td>
					<td style="width: 12%">{{ $tarea->fecha_inicio->format('d-M-Y')}}</td>
					<td style="width: 12%">{{ $tarea->fecha_termino_original->format('d-M-Y') }}</td>
					<td style="width: 12%">
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
				</tr>
				@endforeach
			</tbody>
		</table>
	@else
		<hr>
		<h3 class="text-center">No hay tareas</h3>
	@endif
@endif
<link rel="stylesheet" type="text/css" href="/css/fixedHeader.dataTables.min">
<script src="/js/dataTables.fixedHeader.min.js"></script>
<script src="/js/jquery.stickytableheaders.min.js"></script>

<script>
	$(document).ready(function() {
		$('#tablaTareas').stickyTableHeaders();
    	$('#tablaTareas').DataTable( {
    		//"order": [[ 1, 'asc' ], [ 2, 'asc' ]],
    		//"fixedHeader": true,
    		"ordering": false,
    		"paging":   false,
	        "language": {
	            "url": "/js/locales/datatables.net_plug-ins_1.10.19_i18n_Spanish.json"
	        }
    	} );
	} );
	@if (session('idTareaMod'))
		window.scrollTo(0, $("#{{session('idTareaMod')}}").offset().top-100);
		$(document).ready(function(){
			$("#{{session('idTareaMod')}}").effect("highlight", {}, 3000);
		});
	@endif
</script>
