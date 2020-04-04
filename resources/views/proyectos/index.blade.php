@extends('layouts.master')
@section('content')
<div class="row">
	<div class="col-6">
		<h1>Proyectos Activos</h1>
	</div>
	@can('gestionar_proyectos')
	<div class="col-2">
		<a type="button" class="btn btn-primary" href="/proyectos/cargarHijas" role="button">Cargar tareas hijas
			<i class="fas fa-upload"></i>
		</a>
	</div>
	<div class="col-2">
		<a type="button" class="btn btn-primary" href="/proyectos/cargarXLS" role="button">Cargar archivo XLS
			<i class="fas fa-upload"></i>
		</a>
	</div>
	<div class="col-2">
		<a type="button" class="btn btn-success" href="{{action('ProyectosController@create')}}" role="button">Crear proyecto
			<i class="fas fa-plus"></i>
		</a>
	</div>
	@endcan
</div>
@if(count($proyectos)>0)
<div class="table-responsive">
	<table class="table table-hover">
		<colgroup>
			<col scope="col">
			<col scope="col" style="width: 50%;">
			<col scope="col" style="width: 50%;">
			<col scope="col" style="width: 50%;">
			<col scope="col">
			<col scope="col">
			<col scope="col">
			<col scope="col">
			<col scope="col">
			<col scope="col">
			<col scope="col">			
		</colgroup>
		<thead>
			<tr>
				<th style="vertical-align: top">NOMBRE PROYECTO</th>
				<th style="vertical-align: top">FIR</th>
				<th style="vertical-align: top">FTR Original</th>
				<th style="vertical-align: top">FTR Modificada</th>
				<th style="vertical-align: top">ATRASO [días]</th>
				<th style="vertical-align: top">AVANCE REAL[%]</th>
				<th style="vertical-align: top">AVANCE PROGRAM.[%]</th>
				@can('ver_graficos')
				<th style="vertical-align: top">Ver gráfico</th>
				@endcan
				@can('ver_informes')
				<th style="vertical-align: top">Ver informes</th>
				@endcan
				@can('gestionar_proyectos')
				<th style="vertical-align: top">Editar</th>				
				<th style="vertical-align: top">Archivar</th>
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
				<a class="text-dark" href="{{action('ProyectosController@show', $proyecto['id'])}}">{{$proyecto->nombre}}</a>
				</td>
				<td>{{ $proyecto->fecha_inicio->format('d-M-Y') }}</td>
				<td>{{ $proyecto->fecha_termino_original->format('d-M-Y') }}</td>
				<td>				
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
					<a href="{{action('GraficosController@vistaGrafico', $proyecto['id'])}}" type="button" class="btn btn-primary" >
						<i class="fas fa-chart-pie"></i>
					</a>
				</td>
				@endcan
				@can('ver_informes')
				<td>
					<a href="{{action('InformesController@vistaListaInformes', $proyecto['id'])}}" type="button" class="btn btn-info" >
						<i class="fas fa-file-alt"></i>
					</a>
				</td>
				@endcan
				@can('gestionar_proyectos')
				<td>
					<a href="{{action('ProyectosController@edit', $proyecto['id'])}}" type="button" class="btn btn-primary" >
						<i class="fas fa-edit"></i>
					</a>
				</td>				
				<td>
					<form method="POST" action="{{action('ProyectosController@destroy', $proyecto)}}">
						{{csrf_field()}}
						{{method_field('DELETE')}}
						<button type="submit" class="btn btn-warning" onclick="return confirm('¿Desea archivar el proyecto?. Esto también afectará las tareas del proyecto.')">
							<i class="fas fa-archive"></i></a>
						</button>
					</form>
				</td>
				@endcan
			</tr>
		@endforeach
		</tbody>
	</table>
</div>
{{$proyectos->links()}}
@else
<hr>
<h3 class="text-center">No hay proyectos activos</h3>
@endif
@endsection
