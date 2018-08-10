@extends('layouts.master')
@section('content')
<h1>Editar tarea</h1>
<hr>
@include('layouts.errors')
<form method="POST" action="{{action('TareasController@update', $tarea)}}">
	{{csrf_field()}}
	{{method_field('PUT')}}

	@foreach ($listaProyectos as $listaProyecto)
		@if($listaProyecto->id == $tarea->proyecto_id)
			<p>Pertenece a proyecto: {{$listaProyecto->nombre}}</p>
		@endif
	@endforeach
	<div class="form-group">
		<label for="nombre" class="col-2 col-form-label">Nombre</label>
		<div class="col-10">
			<input @role('Usuario') readonly @endrole type="text" class="form-control" id="nombre" required name="nombre" value="{{$tarea->nombre}}">
		</div>
	</div>

	<div class="form-group">
		<label for="area_id" class="col-2 col-form-label">Área</label>
		<div class="col-10">
			<select @role('Usuario') readonly @endrole class="form-control" id="area_id" required name="area_id">
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
		<label for="fecha_inicio" class="col-6 col-form-label">FIT</label>
		<div class="col-10">
			<input class="form-control" type="date" id="fecha_inicio" readonly required name="fecha_inicio" value={{$tarea->fecha_inicio}}>
		</div>
	</div>	

	<div class="form-group">
		<label class="col-6 col-form-label">FTT original</label>
		<div class="col-10">			
			<input class="form-control" type="date" readonly value={{$tarea->fecha_termino_original}}>			
		</div>
	</div>

	<div class="form-group">
		<label for="fecha_termino" class="col-6 col-form-label">FTT modificada</label>
		<div class="col-10">
			<input @role('Usuario') readonly @endrole class="form-control" type="date" id="fecha_termino" name="fecha_termino" value="">
		</div>
	</div>	

	<div class="form-group" >
		<label for="avance" class="col-6 col-form-label">Porcentaje avance</label>
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
