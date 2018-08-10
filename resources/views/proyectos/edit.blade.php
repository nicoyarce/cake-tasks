@extends('layouts.master')
@section('content')
<h1>Editar proyecto</h1>
<hr>
@include('layouts.errors')
<form method="POST" action="{{action('ProyectosController@update', $proyecto)}}">
	{{csrf_field()}}
	{{method_field('PUT')}}		
	<div class="form-group">
		<label for="nombre" class="col-6 col-form-label">Nombre</label>
		<div class="col-10">
			<input type="text" class="form-control" id="nombre" readonly name="nombre" value="{{$proyecto->nombre}}">
		</div>
	</div>

	<div class="form-group">
		<label for="fecha_inicio" class="col-6 col-form-label">FIR</label>
		<div class="col-10">			
			<input class="form-control" type="date" id="fecha_inicio" readonly name="fecha_inicio" value={{$proyecto->fecha_inicio}}>			
		</div>
	</div>

	<div class="form-group">
		<label class="col-6 col-form-label">FTR original</label>
		<div class="col-10">			
			<input class="form-control" type="date" readonly value={{$proyecto->fecha_termino_original}}>			
		</div>
	</div>

	<div class="form-group">
		<label for="fecha_termino" class="col-6 col-form-label">FTR modificada</label>
		<div class="col-10">
			<input class="form-control" type="date" id="fecha_termino" required name="fecha_termino" value="">
		</div>
	</div>	
	
	<div class="form-group text-center">
		<button type="submit" class="btn btn-primary">Actualizar</button>
	</div>
</form>
@endsection
