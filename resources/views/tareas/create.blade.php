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
    <p class="alert alert-primary">Pertenece a <b>proyecto</b>: {{$proyecto->nombre}}</p>
    <div class="form-row">
        <div class="form-group col-5">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" required name="nombre">
        </div>        
        <div class="form-group col-2">
            <label for="area_id">Nro. Documento</label>
            <input type="text" class="form-control" id="nro_documento" name="nro_documento" value="">
        </div>
        <div class="form-group col-3">
            <label for="area_id">Área</label>
            <select class="form-control" id="area_id" required name="area_id">
				<option value="" disabled selected>Elija una opción</option>
				@foreach ($areas as $area)
				<option value="{{$area->id}}">{{$area->nombrearea}}</option>
				@endforeach
            </select>
        </div>
        <div class="mx-auto d-flex align-items-center">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="critica" name="critica">
                <label class="custom-control-label" for="critica">¿Es ruta crítica?</label>
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
            <div id="listaObservaciones" class="form-group">                
                <div id="fila_0" class="fila col-12 row form-group pr-0">
                    <input id="observacion_" name="observaciones[]" value="" class="texto form-control col-11 mr-1">
                    <button disabled="true" id="quitaObs_" type="button" class="quitar btn btn-danger btn-sm float-right"><i class="fas fa-minus" ></i></button>
                </div>                
			</div>
		<div id="listaObservaciones" class="form-group col-12">	
            {{-- Fila dummy --}}
            <div id="fila_" class="fila col-12 row form-group pr-0" style="display: none;">
                <input disabled="true" id="observacion_" name="observaciones[]" value="" class="form-control col-11 mr-1">
                <input disabled="true" type="hidden" id="id_observacion_" name="ids_observaciones[]" value="" class="form-control">
                <button id="quitaObs_" type="button" class="quitar btn btn-danger btn-sm float-right"><i class="fas fa-minus"></i></button>
            </div>                  
		</div>	
	</div>	
	<div class="form-group">
    <div class="form-row">
        <div class="form-group col-12">
            <label for="avance">Porcentaje avance</label>
            <select class="form-control" id="avance" required name="avance">
                {{-- <option value="" disabled selected>Elija una opción</option> --}}
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
            var nroObservaciones = $("#listaObservaciones").children().length;
            let fila_dummy = $("#fila_").clone(true, true);
            let id_original = fila_dummy.attr('id');
            fila_dummy.attr('id',id_original+nroObservaciones); 
            fila_dummy.removeAttr('style'); 
            fila_dummy.children().prop('disabled', false);
            fila_dummy.children().each(function(){          
                $(this).attr('id',$(this).attr('id')+nroObservaciones);
            });
            fila_dummy.appendTo("#listaObservaciones")
            nroObservaciones = $("#listaObservaciones").children().length;
            console.log(nroObservaciones);
            if(nroObservaciones<=1){
                $("#listaObservaciones .fila:first").children(".quitar").prop('disabled', true);
            }
            else{
                $("#listaObservaciones .fila:first").children(".quitar").prop('disabled', false);
            }       
        });

        $(".quitar").click(function(){
            $(this).parent().remove();
            var nroObservaciones = $("#listaObservaciones").children().length;
            console.log(nroObservaciones);
            if(nroObservaciones<=1){
                $("#listaObservaciones .fila:first").children(".quitar").prop('disabled', true);
            }
            else{
                $("#listaObservaciones .fila:first").children(".quitar").prop('disabled', false);
            }
        });
	});
</script>
@endsection
