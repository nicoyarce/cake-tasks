@extends('layouts.master')
@section('content')
<div class="row justify-content-between">
	<h1>Editar proyecto</h1>
	<div class="col-4">
		<a type="button" class="btn btn-primary float-right" href="/proyectos">Atrás <i class="fas fa-arrow-left "></i></a>
	</div>
</div>
<hr>
@include('layouts.errors')
<form class="form-horizontal" method="POST" action="{{action('ProyectosController@update', $proyecto)}}">
	{{csrf_field()}}
	{{method_field('PUT')}}
	<div class="form-group">
		<label for="nombre">Nombre</label>
		<input type="text" class="form-control" id="nombre" readonly name="nombre" value="{{$proyecto->nombre}}">
	</div>
	<div class="form-row">
		<div class="form-group col-4">
			<label for="fecha_inicio">FIR</label>
			<input class="form-control" type="date" id="fecha_inicio" readonly name="fecha_inicio" value={{$proyecto->fecha_inicio}}>
		</div>
		<div class="form-group col-4">
			<label>FTR original</label>
			<input class="form-control" type="date" readonly value={{$proyecto->fecha_termino_original}}>
		</div>
		<div class="form-group col-4">
			<label for="fecha_termino">FTR modificada</label>
			<input class="form-control" type="date" id="fecha_termino" required name="fecha_termino" value="">
		</div>
	</div>
	<div class="form-group text-center">
		<button type="submit" class="btn btn-primary">Actualizar</button>
	</div>
</form>
@endsection
