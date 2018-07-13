@extends('layout')
@section('content')
<h1>Crear tarea</h1>
<hr>
@if(count($errors))
<div class="form-group">
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{$error}}</li>
			@endforeach
		</ul>
	</div>
</div>
@endif
<form method="POST" action="/tareas">
	{{csrf_field()}}
	<div class="form-group">
		<label for="nombre" class="col-2 col-form-label">Nombre</label>
		<div class="col-10">
			<input type="text" class="form-control" id="nombre" required name="nombre">
		</div>
	</div>

	<div class="form-group">
		<label for="proyecto_id" class="col-2 col-form-label">Pertenece a proyecto</label>
		<div class="col-10">
			<select class="form-control" id="proyecto_id" required name="proyecto_id">
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
	<div class="form-group" >
		<label for="avance" class="col-2 col-form-label">Porcentaje avance</label>
		<div class="col-10">
			<select class="form-control" id="avance" required name="avance">
				@for($i=0;$i<=100;$i+=5)
					<option>{{$i}}</option>
				@endfor
			</select>
		</div>
		
	</div>
	<div class="form-group text-center">
		<button type="submit" class="btn btn-primary">Guardar</button>
	</div>
</form>
@endsection
