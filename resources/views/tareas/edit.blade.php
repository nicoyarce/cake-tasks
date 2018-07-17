@extends('layouts.master')
@section('content')
<h1>Editar tarea</h1>
<hr>
@include('layouts.errors')
<form method="POST" action="{{action('TareasController@update', $tarea)}}">
	{{csrf_field()}}
	{{method_field('PUT')}}	
	<div class="form-group">
		<label for="nombre" class="col-2 col-form-label">Nombre</label>
		<div class="col-10">
			<input type="text" class="form-control" id="nombre" required name="nombre" value="{{$tarea->nombre}}">
		</div>
	</div>

	<div class="form-group">
		<label for="area_id" class="col-2 col-form-label">Área</label>
		<div class="col-10">
			<select class="form-control" id="area_id" required name="area_id">
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

	<div class="form-group">
		<label for="proyecto_id" class="col-2 col-form-label">Pertenece a proyecto</label>
		<div class="col-10">
			<select class="form-control" id="proyecto_id" name="proyecto_id">
				@foreach ($listaProyectos as $listaProyecto)
					@if($listaProyecto->id == $tarea->proyecto_id)
						<option selected value="{{$listaProyecto->id}}">{{$listaProyecto->nombre}}</option>
					@else
						<option value="{{$listaProyecto->id}}">{{$listaProyecto->nombre}}</option>
					@endif	
				@endforeach
			</select>
		</div>
	</div>
	
	<div class="form-group">
		<label for="fecha_inicio" class="col-2 col-form-label">Fecha inicio</label>
		<div class="col-10">
			<input class="form-control" type="date" id="fecha_inicio" required name="fecha_inicio" value="{{$tarea->fecha_inicio}}">
		</div>
	</div>

	<div class="form-group">
		<label for="fecha_termino" class="col-2 col-form-label">Fecha termino</label>
		<div class="col-10">
			<input class="form-control" type="date" id="fecha_termino" required name="fecha_termino" value="{{$tarea->fecha_termino}}">
		</div>
	</div>

	<div class="form-group" >
		<label for="avance" class="col-2 col-form-label">Porcentaje avance</label>
		<div class="col-10">
			<select class="form-control" id="avance" required name="avance">
				@foreach($avances as $avance)
					@if($avance->porcentaje == $tarea->avance)
						<option selected value="{{$avance->porcentaje}}">{{$avance->porcentaje}}% - {{$avance->glosa}}</option>		
					@else
						<option value="{{$avance->porcentaje}}">{{$avance->porcentaje}}% - {{$avance->glosa}}</option>		
					@endif
				@endforeach			
			</select>
		</div>
		
	</div>
	<div class="form-group text-center">
		<button type="submit" class="btn btn-primary">Actualizar</button>
	</div>
</form>
@endsection
