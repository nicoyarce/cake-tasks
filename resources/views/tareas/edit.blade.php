@extends('layout')
@section('content')
<h1>Editar tarea</h1>
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
<form method="POST" action="{{action('TareasController@update', $tarea)}}">
	{{csrf_field()}}
	{{method_field('PUT')}}
	<div class="form-group">
		<label for="nombre" class="col-2 col-form-label">Nombre</label>
		<div class="col-10">
			<input type="text" class="form-control" id="nombre" required name="nombre" value={{$tarea->nombre}}>
		</div>
	</div>
	
	<div class="form-group">
		<label for="fechainicio" class="col-2 col-form-label">Fecha inicio</label>
		<div class="col-10">
			<input class="form-control" type="date" id="fechainicio" required name="fechainicio" value={{$tarea->fechainicio}}>
		</div>
	</div>
	<div class="form-group">
		<label for="fechatermino" class="col-2 col-form-label">Fecha termino</label>
		<div class="col-10">
			<input class="form-control" type="date" id="fechatermino" required name="fechatermino" value={{$tarea->fechatermino}}>
		</div>
	</div>
	<div class="form-group" >
		<label for="avance" class="col-2 col-form-label">Porcentaje avance</label>
		<div class="col-10">
			<select class="form-control" id="avance" required name="avance">
				@for($i=0;$i<=100;$i+=5)
				@if($i == $tarea->avance)
					<option selected>{{$tarea->avance}}</option>			
				@else
					<option>{{$i}}</option>
				@endif
				@endfor
			</select>
		</div>
		
	</div>
	<div class="form-group text-center">
		<button type="submit" class="btn btn-primary">Actualizar</button>
	</div>
</form>
@endsection
