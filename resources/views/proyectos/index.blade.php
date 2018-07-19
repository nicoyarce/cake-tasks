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
			<th>NOMBRE</th>
			<th>FIR<br>Original</th>
			<th>FTR<br>Original</th>
			<th>FTR<br>Modificada</th>
			<th>ATRASO<br>[días]</th>
			<th>AVANCE<br>[%]</th>
			<th>Ver gráfico</th>
			<th>Editar</th>
			<th>Borrar</th>
		</tr>
	</thead>
	
	<tbody>
		@foreach ($proyectos as $proyecto)
		<tr>
			<td><a href="{{action('ProyectosController@show', $proyecto['id'])}}">{{$proyecto->nombre}}</a></td>
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
				@if(Carbon::parse($proyecto->fecha_termino_original)->gte(Carbon::parse($proyecto->fecha_termino)))
				-
				@else
				{{$proyecto->atraso}}
				@endif
			</td>
			<td>{{$proyecto->avance}}</td>
			<td>
				<a href="{{action('GraficosController@show', $proyecto['id'])}}" type="button" class="btn btn-primary" >
					<i class="fas fa-chart-pie"></i>
				</a>
			</td>
			<td>
				<a href="{{action('ProyectosController@edit', $proyecto['id'])}}" type="button" class="btn btn-primary" >
					<i class="fas fa-edit"></i>
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
{{$proyectos->links()}}
@else
<h1 align="center">No hay proyectos</h1>
@endif
@endsection
