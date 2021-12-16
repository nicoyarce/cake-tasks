@extends('layouts.master')
@section('content')
@section('tituloPagina', 'Áreas')
<div class="row">
	<div class="col-10">
		<h1>Áreas</h1>
	</div>
	<div class="col-2">
		<a type="button" class="btn btn-success float-right"
		@if(empty($editar))
		data-toggle="modal" data-target="#exampleModal" href=""
		@else
		href="{{action('AreasController@indexConModal')}}"
		@endif
		role="button" >Crear área <i class="fas fa-plus"></i>
		</a>
	</div>
</div>
@include('layouts.errors')
@if(count($areas)>0)
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Nombre Área</th>
					<th>Fecha Creación</th>
					<th>Editar</th>					
					<th>Eliminar</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($areas as $area)
				<tr id="{{$area->id}}">
					<td>{{$area->nombrearea}}</td>
					<td>{{$area->created_at->format('d-M-Y')}}</td>					
					<td>
						<a href="{{action('AreasController@edit', $area['id'])}}" type="button" class="btn btn-primary">
						<i class="fas fa-edit"></i>
						</a>
					</td>					
					<td>
						@if($area->habilitaBorrado)
						<form method="POST" action="{{action('AreasController@destroy', $area->id)}}">
							{{csrf_field()}}
							{{method_field('DELETE')}}
							<button type="submit" class="btn btn-danger" onclick="return confirm('¿Desea eliminar la área?')"><i class="fas fa-trash-alt"></i></button>
						</form>
						@else
						<span data-toggle="tooltip" data-placement="bottom" data-html="true" title="Existen tareas asociadas a esta área">
							<button class="btn btn-danger" disabled="true" style="pointer-events: none;"><i class="fas fa-trash-alt"></i></button>
						</span>
						@endif
					</td>					
				</tr>
				@endforeach
			</tbody>
		</table>
	{{$areas->links()}}
	@else
		<hr>
		<h3 class="text-center">No hay áreas</h3>
	@endif	
@endsection

{{-- Modal --}}
@if(empty($editar))
{{-- Crear area --}}
@section('modal-title')
Crear área
@endsection
@section('modal-content')
@include('layouts.errors')
<form class="form-horizontal" method="POST" action="{{action('AreasController@store')}}">
    {{csrf_field()}}
    <div class="form-group">
        <label for="nombre">Nombre área</label>       
        <input type="text" class="form-control" id="nombre" required name="nombre">        
    </div>       
    <div class="form-group text-center">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>
@if(isset($abrir_modal))	
	<script type="text/javascript">
		$(document).ready(function(){
			$('#exampleModal').modal('toggle')
		});	
	</script>
@endif
@endsection
@else
{{-- Modificar area --}}
@section('modal-title')
Modificar área
@endsection
@section('modal-content')
@include('layouts.errors')
<form class="form-horizontal" method="POST" action="{{action('AreasController@update', $editar->id)}}">
    {{csrf_field()}}
    {{method_field('PUT')}}
    <div class="form-group">
        <label for="nombre">Nombre área</label>       
        <input type="text" class="form-control" id="nombre" required name="nombre" value="{{$editar->nombrearea}}">        
    </div>       
    <div class="form-group text-center">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>
<script type="text/javascript">
	$(document).ready(function(){
		$('#exampleModal').modal('toggle')
	});	
</script>
@endsection
@endif
