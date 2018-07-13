@extends('layout')
@section('content')
<h1>Crear proyecto</h1>
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
<form method="POST" action="/proyectos">
	{{csrf_field()}}
	<div class="form-group">
		<label for="nombre" class="col-2 col-form-label">Nombre</label>
		<div class="col-10">
			<input type="text" class="form-control" id="nombre" required name="nombre">
		</div>
	</div>

	<div class="form-group">
		<label for="fechainicio" class="col-2 col-form-label">Fecha inicio</label>
		<div class="col-10">
			<input class="form-control" type="date" id="fechainicio" required name="fechainicio">
		</div>
	</div>
	<div class="form-group">
		<label for="fechatermino" class="col-2 col-form-label">Fecha termino</label>
		<div class="col-10">
			<input class="form-control" type="date" id="fechatermino" required name="fechatermino">
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
