@extends('layouts.master')
@section('content')
<h1>Crear tarea</h1>
<hr>
@include('layouts.errors')
<form method="POST" action="/tareas">
	{{csrf_field()}}
	
	<div class="form-group">
		<label for="nombre" class="col-2 col-form-label">Nombre</label>
		<div class="col-10">
			<input type="text" class="form-control" id="nombre" required name="nombre">
		</div>
	</div>

	<div class="form-group">
		<label for="area_id" class="col-2 col-form-label">Área</label>
		<div class="col-10">
			<select class="form-control" id="area_id" required name="area_id">					
				@foreach ($areas as $area)
					<option value="{{$area->id}}">{{$area->nombrearea}}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="form-group">
		<label for="proyecto_id" class="col-2 col-form-label">Pertenece a proyecto</label>
		<div class="col-10">
			<select class="form-control" id="proyecto_id" name="proyecto_id">
				@foreach ($listaProyectos as $listaProyecto)
					<option value="{{$listaProyecto->id}}">{{$listaProyecto->nombre}}</option>
				@endforeach
			</select>
		</div>
	</div>
	
	<div class="form-group">
		<label for="fecha_inicio" class="col-2 col-form-label">Fecha inicio</label>
		<div class="col-10">
			<input class="form-control" type="date" id="fecha_inicio" required name="fecha_inicio">
		</div>
	</div>

	<div class="form-group">
		<label for="fecha_termino" class="col-2 col-form-label">Fecha termino</label>
		<div class="col-10">
			<input class="form-control" type="date" id="fecha_termino" required name="fecha_termino">
		</div>
	</div>

	<div class="form-group">
		<label for="avance" class="col-2 col-form-label">Porcentaje avance</label>
		<div class="col-10">
			<select class="form-control" id="avance" required name="avance">
				@foreach($avances as $avance)
					<option value="{{$avance->porcentaje}}">{{$avance->porcentaje}}% - {{$avance->glosa}}</option>
				@endforeach
			</select>
		</div>		
	</div>

	<div class="form-group text-center">
		<button type="submit" class="btn btn-primary">Guardar</button>
	</div>
</form>
@endsection
