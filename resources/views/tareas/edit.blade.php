@extends('layouts.master')
@section('content')
<div class="row justify-content-between">
	<h1>Editar tarea</h1>
	<div class="col-4">
		<a type="button" class="btn btn-primary float-right" href="/proyectos/{{$tarea->proyecto_id}}">Atrás <i class="fas fa-arrow-left "></i></a>
	</div>
</div>
@include('layouts.errors')
<form id="formulario" method="POST" action="{{action('TareasController@update', $tarea)}}">
	{{csrf_field()}}
	{{method_field('PUT')}}
	{{-- Formulario para Admin y OCR --}}
	@if(!Auth::user()->hasRole('Usuario'))
		<hr>
		@foreach ($listaProyectos as $listaProyecto)
			@if($listaProyecto->id == $tarea->proyecto_id)
				<p class="alert alert-primary">Pertenece a <b>proyecto</b>: {{$listaProyecto->nombre}}</p>
			@endif
		@endforeach
		<div class="form-row">
			<div class="form-group col-6">
				<label for="nombre">Nombre</label>
				<input type="text" class="form-control" id="nombre" name="nombre" value="{{$tarea->nombre}}">
			</div>
			<div class="form-group col-4">
				<label for="area_id">Área</label>
				<select class="form-control" id="area_id" name="area_id"">
					@foreach ($areas as $area)
						@if($area->id == $tarea->area->id)
							<option selected value="{{$area->id}}">{{$area->nombrearea}}</option>
						@else
							<option value="{{$area->id}}">{{$area->nombrearea}}</option>
						@endif
					@endforeach
				</select>
			</div>
			<div class="col-2 mx-auto d-flex align-items-center">
				<div class="form-check ">
					<input class="form-check-input" type="checkbox" @if($tarea->critica) checked @endif id="critica" name="critica">
		  			<label class="form-check-label" for="critica">
		    			¿Es ruta crítica?
		  			</label>
	  			</div>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-4">
				<label for="fecha_inicio">FIT</label>
				<input class="form-control" type="date" readonly value={{$tarea->fecha_inicio}}>	
			</div>
			<div class="form-group col-4">
				<label>FTT original</label>			
				<input class="form-control" id="fecha_termino_original" name="fecha_termino_original" type="date" readonly value={{$tarea->fecha_termino_original}}>			
			</div>
			<div class="form-group col-4">
				<label for="fecha_termino">FTT modificada</label>			
				<input class="form-control" type="date" id="fecha_termino" name="fecha_termino" @if($tarea->fecha_termino_original != $tarea->fecha_termino) value={{$tarea->fecha_termino}} @endif>			
			</div>
		</div>
		<div class="form-row">			
			<div class="form-group col-12">
					<label for="observaciones">Observaciones</label>
					<button id="agregaObs" type="button" class="btn btn-success btn-sm ml-2"><i class="fas fa-plus"></i></button>
					<button id="quitaObs" type="button" class="btn btn-danger btn-sm ml-2"><i class="fas fa-minus"></i></button>
				</div>
			<div id="listaObservaciones" class="form-group col-12">
				@if(count($tarea->observaciones)>0)
					@foreach ($tarea->observaciones as $observacion)
						<input id="observacion" name="observaciones[]" value="{{$observacion}}" class="form-control">
					@endforeach
				@else
					<input id="observacion" name="observaciones[]" value="" class="form-control">											
				@endif
			</div>	
		</div>
	@else
	{{-- Formulario para usuarios --}}
		<table class="table table-hover">
			<thead>
				<tr>
					<th>NOMBRE<br>TAREA</th>
					<th>ÁREA<br>&nbsp;</th>
					<th>FIT<br>&nbsp;</th>
					<th>FTT<br>Original</th>
					<th>FTT<br>Modificada</th>
					<th>ATRASO<br>[días]</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					@if($tarea->colorAtraso == "VERDE" || $tarea->avance == 100)
					<td class="bg-success">{{$tarea->nombre}}</td>
					@elseif($tarea->colorAtraso == "AMARILLO")
					<td class="fondo-amarillo">{{$tarea->nombre}}</td>				
				@elseif($tarea->colorAtraso == "NARANJO")
					<td class="fondo-naranjo">{{$tarea->nombre}}</td>
				@elseif($tarea->colorAtraso == "ROJO")
					<td class="bg-danger">{{$tarea->nombre}}</td>
				@endif	
					<td>{{$tarea->area->nombrearea}}</td>
					<td>{{ $tarea->fecha_inicio->format('d-M-Y')}}</td>
					<td >{{ $tarea->fecha_termino_original->format('d-M-Y') }}</td>
					<td>
						@if($tarea->fecha_termino_original == $tarea->fecha_termino)
						-
						@else
						{{ $tarea->fecha_termino->format('d-M-Y')}}
						@endif
					</td>
					<td>
						@if($tarea->fecha_termino_original->gte($tarea->fecha_termino))
						-
						@else
						{{$tarea->atraso}}
						@endif
					</td>
				</tr>
			</tbody>
		</table>
		<div class="form-row">
			<div class="form-group col-12">
				<label for="observaciones">Observaciones</label>
				<ul>
				@if(count($tarea->observaciones)>0)					
					@foreach ($tarea->observaciones as $observacion)
						<li>{{$observacion}}</li>
					@endforeach										
				@else
					<li><h5>No hay datos.</h5></li>
				@endif
				</ul>	
			</div>
		</div>
	@endif	
	<div class="form-group">
		<label for="avance">Porcentaje avance</label>		
			<select class="form-control" id="avance" required name="avance" @role('Usuario') onchange="formulario.submit()" @endrole>
				@foreach($avances as $avance)
				@if($avance->porcentaje == $tarea->avance)
				<option selected value="{{$avance->porcentaje}}">{{$avance->porcentaje}}% - {{$avance->glosa}}</option>
				@else
				<option value="{{$avance->porcentaje}}">{{$avance->porcentaje}}% - {{$avance->glosa}}</option>
				@endif
				@endforeach
			</select>
	</div>
	@if(!Auth::user()->hasRole('Usuario'))
		<div class="form-group text-center">
	        <button class="btn btn-primary" type="submit">Confirmar</button>
	    </div>	
    @endif
</form>
@if(Auth::user()->hasRole('Usuario'))
	<script>
		$("#avance").on('change', function(){
			$("#carga").show();
		});
	</script>
@endif
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

