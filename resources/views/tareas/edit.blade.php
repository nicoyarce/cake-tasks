@extends('layouts.master')
@section('content')
<div class="row justify-content-between">
	<h1>Editar tarea</h1>
	<div class="col-4">
		<a type="button" class="btn btn-primary float-right" href="/proyectos/{{$tarea->proyecto_id}}">Atrás <i class="fas fa-arrow-left "></i></a>
	</div>
</div>
@include('layouts.errors')
<form method="POST" action="{{action('TareasController@update', $tarea)}}">
	{{csrf_field()}}
	{{method_field('PUT')}}
	@if(!Auth::user()->hasRole('Usuario'))
	<hr>
	@foreach ($listaProyectos as $listaProyecto)
	@if($listaProyecto->id == $tarea->proyecto_id)
	<p class="alert alert-primary">Pertenece a proyecto: {{$listaProyecto->nombre}}</p>
	@endif
	@endforeach
	<div class="form-row">
		<div class="form-group col-6">
			<label for="nombre">Nombre</label>
			<input readonly type="text" class="form-control" value="{{$tarea->nombre}}">
		</div>
		<div class="form-group col-6">
			<label for="area_id">Área</label>
			<select @role('Usuario') readonly @endrole class="form-control" id="area_id" name="area_id" onchange="this.form.submit()">
				@foreach ($areas as $area)
				@if($area->id == $tarea->id)
				<option selected value="{{$area->id}}">{{$area->nombrearea}}</option>
				@else
				<option value="{{$area->id}}">{{$area->nombrearea}}</option>
				@endif
				@endforeach
			</select>
		</div>
	</div>
	<div class="form-row">
		<div class="form-group col-4">
			<label for="fecha_inicio">FIT</label>
			<input class="form-control" type="date"  readonly value={{$tarea->fecha_inicio}}>	
		</div>
		<div class="form-group col-4">
			<label>FTT original</label>			
			<input class="form-control" id="fecha_termino_original" name="fecha_termino_original" type="date" readonly value={{$tarea->fecha_termino_original}}>			
		</div>
		<div class="form-group col-4">
			<label for="fecha_termino">FTT modificada</label>			
			<input class="form-control" type="date" id="fecha_termino" name="fecha_termino" onchange="this.form.submit()">			
		</div>
	</div>
	@else
	<table class="table">
		<thead>
			<tr>
				<th>NOMBRE<br>&nbsp;</th>
				<th>ÁREA<br>&nbsp;</th>
				<th>FIT<br>&nbsp;</th>
				<th>FTT<br>Original</th>
				<th>FTT<br>Modificada</th>
				<th>ATRASO<br>[días]</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				@if($tarea->colorAtraso == "ROJO")
				<td class="table-danger"><p class="text-warning">{{$tarea->nombre}}</p></td>				
				@elseif($tarea->colorAtraso == "NARANJO")
				<td class="table-warning">{{$tarea->nombre}}</td>
				@elseif($tarea->colorAtraso == "VERDE")
				<td class="table-success">{{$tarea->nombre}}</td>
				@endif
				<td>{{$tarea->area->nombrearea}}</td>
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
			</tr>
		</tbody>
	</table>
	@endif
	<div class="form-group" >
		<label for="avance">Porcentaje avance</label>		
			<select class="form-control" id="avance" required name="avance" onchange="this.form.submit()">
				@foreach($avances as $avance)
				@if($avance->porcentaje == $tarea->avance)
				<option selected value="{{$avance->porcentaje}}">{{$avance->porcentaje}}% - {{$avance->glosa}}</option>
				@else
				<option value="{{$avance->porcentaje}}">{{$avance->porcentaje}}% - {{$avance->glosa}}</option>
				@endif
				@endforeach
			</select>
	</div>	
</form>
@endsection
