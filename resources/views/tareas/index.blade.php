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
					<th class="text-center">AVANCE<br>REAL<br>[%]</th>
                    <th class="text-center">AVANCE<br>PROGRAMADO<br>[%]</th>
					@if(Auth::user()->can('modificar_tareas') || Auth::user()->can('modificar_avance_tareas'))
					<th>Editar</th>
					@endif
					@can('borrar_tareas')
					<th>Eliminar</th>
					@endcan
				</tr>
			</thead>
			<tbody>
				@foreach ($tareas as $tarea)
				<tr id="{{$tarea->id}}">
					@if($tarea->colorAtraso == $propiedades[0]->color || $tarea->avance == 100)
                        <td style="background-color: {{$propiedades[0]->color}};">
					@elseif($tarea->colorAtraso == $propiedades[1]->color)
						<td style="background-color: {{$propiedades[1]->color}};">
					@elseif($tarea->colorAtraso == $propiedades[2]->color)
						<td style="background-color: {{$propiedades[2]->color}};">
					@elseif($tarea->colorAtraso == $propiedades[3]->color)
						<td style="background-color: {{$propiedades[3]->color}};">
                    @endif
                    <a class="text-dark" href="{{action('TareasController@show', $tarea['id'])}}" 
						data-toggle="popover" data-placement="left" data-html="true" data-trigger="hover" data-title="Detalles Tarea"
						data-content="
						<ul>
							<li><b>Nro. Cotización</b>: @if(is_null($tarea->nro_documento))-@else{{$tarea->nro_documento}}@endif</li>
							<li><b>Área</b>: @if(!empty($tarea->area->nombrearea)){{$tarea->area->nombrearea}}@else-@endif</li>
							<li><b>Tipo Tarea</b>:  @if(!empty($tarea->tipoTarea->descripcion)){{$tarea->tipoTarea->descripcion}}@else-@endif</li>	
							<li><b>Tipo Proyecto</b>: @if(!empty($tarea->categoria->nombre)){{$tarea->categoria->nombre}}@else-@endif</li>
						</ul>						
						">
						{{$tarea->nombre}}
					</a>
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
							@if(empty($tarea->getNombreAutorUltimoCambioFtt()))
								{{ $tarea->fecha_termino->format('d-M-Y')}}
							@else
								<a data-toggle="tooltip" data-placement="bottom" data-html="true"
									title="Modificado por: {{$tarea->getNombreAutorUltimoCambioFtt()}} <br>
									Fecha:
									{{$tarea->fecha_ultimo_cambio_ftt->format('d-M-Y H:i:s')}}">
									{{ $tarea->fecha_termino->format('d-M-Y')}}
								</a>
							@endif
						@endif
					</td>
					<td>
						@if($tarea->atraso==0)
						-
						@else
						{{$tarea->atraso}}
						@endif
					</td>
					<td>
						@if(empty($tarea->getNombreAutorUltimoCambioAvance()))
							{{$tarea->avance}}
						@else
							<a data-toggle="tooltip" data-placement="bottom" data-html="true"
							title="Autor ultimo cambio: {{$tarea->getNombreAutorUltimoCambioAvance()}} <br> Fecha ultimo cambio: <br> {{$tarea->fecha_ultimo_cambio_avance->format('d-M-Y H:i:s')}}">
								{{$tarea->avance}}
							</a>
						@endif
					</td>
                    <td>{{$tarea->porcentajeAtraso}}</td>					
					@if(Auth::user()->can('modificar_tareas') || Auth::user()->can('modificar_avance_tareas'))
					<td>
						<a href="{{action('TareasController@edit', $tarea)}} "type="button" class="btn btn-primary">
						<i class="fas fa-pen"></i></a>
					</td>
					@endif
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
					<th class="text-center">AVANCE<br>REAL<br>[%]</th>
                    <th class="text-center">AVANCE<br>PROGRAMADO<br>[%]</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($tareas as $tarea)
				<tr id="{{$tarea->id}}">
					@if($tarea->colorAtraso == $propiedades[0]->color || $tarea->avance == 100)
                        <td style="background-color: {{$propiedades[0]->color}};">
                    @elseif($tarea->colorAtraso == $propiedades[1]->color)
                        <td style="background-color: {{$propiedades[1]->color}};">
                    @elseif($tarea->colorAtraso == $propiedades[2]->color)
                        <td style="background-color: {{$propiedades[2]->color}};">
                    @elseif($tarea->colorAtraso == $propiedades[3]->color)
                        <td style="background-color: {{$propiedades[3]->color}};">
                    @endif
                    <a class="text-dark" href="{{action('TareasController@showArchivadas', $tarea['id'])}}">{{$tarea->nombre}}</a>
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
							@if(empty($tarea->autorUltimoCambioFtt()->withTrashed()->first()))
								{{ $tarea->fecha_termino->format('d-M-Y')}}
							@else
								<a data-toggle="tooltip" data-placement="bottom" data-html="true"
									title="Modificado por: {{array_get($tarea->autorUltimoCambioFtt()->withTrashed()->first(), 'nombre')}} <br>
									Fecha: <br>
									{{$tarea->fecha_ultimo_cambio_ftt->format('d-M-Y H:i:s')}}">
									{{ $tarea->fecha_termino->format('d-M-Y')}}
								</a>
							@endif
						@endif
					</td>
					<td>
						@if($tarea->atraso==0)
						-
						@else
						{{$tarea->atraso}}
						@endif
					</td>
					<td>
						@if(empty($tarea->autorUltimoCambioAvance()->withTrashed()->first()))
							{{$tarea->avance}}
						@else
							<a data-toggle="tooltip" data-placement="bottom" data-html="true"
							title="Autor ultimo cambio: {{array_get($tarea->autorUltimoCambioAvance()->withTrashed()->first(), 'nombre')}} <br> Fecha ultimo cambio: <br> {{$tarea->fecha_ultimo_cambio_avance->format('d-M-Y H:i:s')}}">
								{{$tarea->avance}}
							</a>
						@endif
					</td>
					<td>{{$tarea->porcentajeAtraso}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	@else
		<hr>
		<h3 class="text-center">No hay tareas</h3>
	@endif
@endif

<link rel="stylesheet" type="text/css" href="/css/fixedHeader.dataTables.min.css">
<script src="/js/dataTables.fixedHeader.min.js"></script>
<script src="/js/jquery.stickytableheaders.min.js"></script>	

@if(session('idTareaMod'))
	<script>		
		$(document).ready(function(){			
			$('#tablaTareas').stickyTableHeaders();
			$('#tablaTareas').DataTable( {    
				"fixedHeader": false,
				"ordering": false,
				"paging":   false,
				"searching": true,
				"language": {
					"url": "/js/locales/datatables.net_plug-ins_1.10.19_i18n_Spanish.json"
				}
			} );
			window.scrollTo(0, $("#{{session('idTareaMod')}}").offset().top-100);
			$("#{{session('idTareaMod')}}").effect("highlight", {}, 3000);		
		});	
	</script>
@else
	<script>
		$(document).ready(function() {
			$('#tablaTareas').stickyTableHeaders();
			$('#tablaTareas').DataTable( {    		
				"fixedHeader": false,
				"ordering": false,
				"paging":   false,
				"searching": true,
				"language": {
					"url": "/js/locales/datatables.net_plug-ins_1.10.19_i18n_Spanish.json"
				}
			} );
		} );
	</script>	
@endif
