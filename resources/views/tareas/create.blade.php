@extends('layouts.master')
@section('content')
<div class="row justify-content-between">
	<h1>Crear tarea</h1>
	<div class="col-4">
		<a type="button" class="btn btn-primary float-right" href="/proyectos/{{$proyecto->id}}">Atrás <i class="fas fa-arrow-left "></i></a>
	</div>
</div>
<hr>
@include('layouts.errors')
<form class="form-horizontal" method="POST" action="/tareas">
	<div class="form-group">
		<input type="hidden" id="proyecto_id" name="proyecto_id" value="{{$proyecto->id}}">
	</div>
	{{csrf_field()}}
	<p class="alert alert-primary">Pertenece a proyecto: {{$proyecto->nombre}}</p>
	<div class="form-row">
		<div class="form-group col-6">
			<label for="nombre">Nombre</label>
			<input type="text" class="form-control" id="nombre" required name="nombre">
		</div>
		<div class="form-group col-4">
			<label for="area_id">Área</label>
			<select class="form-control" id="area_id" required name="area_id">
				<option value="" disabled selected>Elija una opción</option>
				@foreach ($areas as $area)
				<option value="{{$area->id}}">{{$area->nombrearea}}</option>
				@endforeach
			</select>
		</div>
		<div class="mx-auto d-flex align-items-center">
			<div class="form-check ">
				<input class="form-check-input" type="checkbox" id="critica" name="critica">
	  			<label class="form-check-label" for="critica">
	    			¿Es ruta crítica?
	  			</label>
  			</div>
		</div>
	</div>
	<div class="form-row">
		<div class="form-group col-6">
			<label for="fecha_inicio">FIT</label>
			<input class="form-control" type="date" id="fecha_inicio" required name="fecha_inicio" value="">
		</div>
		<div class="form-group col-6">
			<label for="fecha_termino">FTT original</label>
			<input class="form-control" type="date" id="fecha_termino" required name="fecha_termino" value="">
		</div>
	</div>
	<div class="form-row">			
		<div class="form-group col-12">
				<label for="observaciones">Observaciones</label>
				<button id="agregaObs" type="button" class="btn btn-success btn-sm ml-2"><i class="fas fa-plus"></i></button>
				<button id="quitaObs" type="button" class="btn btn-danger btn-sm ml-2"><i class="fas fa-minus"></i></button>
			</div>
		<div id="listaObservaciones" class="form-group col-12">	
			<input id="observacion" name="observaciones[]" value="" class="form-control">							
		</div>	
	</div>	
	<div class="form-group">
		<label for="avance">Porcentaje avance</label>
		<select class="form-control" id="avance" required name="avance">
			{{-- <option value="" disabled selected>Elija una opción</option> --}}
			@foreach($avances as $avance)
			<option value="{{$avance->porcentaje}}">{{$avance->porcentaje}}% - {{$avance->glosa}}</option>
			@endforeach
		</select>
	</div>
	<div class="form-group text-center">
		<button type="submit" class="btn btn-primary">Guardar</button>
	</div>
</form>
<script>
	$(document).ready(function(){
		var nroObservaciones = $("#listaObservaciones").children().length;
		console.log(nroObservaciones);
		if(nroObservaciones<=1){
			$("#quitaObs").prop('disabled', true);
		}
		else{
			$("#quitaObs").prop('disabled', false);
		}
		
		$("#agregaObs").click(function(){							
			$("#listaObservaciones #observacion:last").clone().appendTo("#listaObservaciones").val("");	
			var nroObservaciones = $("#listaObservaciones").children().length;
			console.log(nroObservaciones);
			if(nroObservaciones<=1){
				$("#quitaObs").prop('disabled', true);
			}
			else{
				$("#quitaObs").prop('disabled', false);
			}
		});

		$("#quitaObs").click(function(){
			var nroObservaciones = $("#listaObservaciones").children().length;
			if(nroObservaciones>1){
				console.log("Quita");
				$("#listaObservaciones #observacion:last-child").remove();
				var nroObservaciones = $("#listaObservaciones").children().length;
			}
			console.log(nroObservaciones);
			if(nroObservaciones<=1){
				$(this).prop('disabled', true);
			}
			else{
				$(this).prop('disabled', false);
			}
		});
	});
</script>
@endsection
