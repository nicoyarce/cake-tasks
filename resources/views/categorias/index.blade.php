@extends('layouts.master')
@section('content')
@section('tituloPagina', 'Tipo Proyectos')
<div class="row">
	<div class="col-10">
		<h1>Tipo Proyectos</h1>
	</div>
	<div class="col-2">
		<a type="button" class="btn btn-success float-right"
		@if(empty($editar))
		data-toggle="modal" data-target="#exampleModal" href=""
		@else
		href="{{action('CategoriasController@indexConModal')}}"
		@endif
		role="button" >Crear tipo proyecto<i class="fas fa-plus"></i>
		</a>
	</div>
</div>
@include('layouts.errors')
@if(count($categorias)>0)
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Nombre tipo</th>
					<th>Fecha Creación</th>
					<th>Editar</th>					
					<th>Eliminar</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($categorias as $categoria)
				<tr id="{{$categoria->id}}">
					<td>{{$categoria->nombre}}</td>
					<td>@if (!is_null($categoria->created_at)){{$categoria->created_at->format('d-M-Y')}}@else-@endif</td>					
					<td>
						<a href="{{action('CategoriasController@edit', $categoria['id'])}}" type="button" class="btn btn-primary">
						<i class="fas fa-edit"></i>
						</a>
					</td>					
					<td>
						@if($categoria->habilitaBorrado)
						<form method="POST" action="{{action('CategoriasController@destroy', $categoria->id)}}">
							{{csrf_field()}}
							{{method_field('DELETE')}}
							<button type="submit" class="btn btn-danger" onclick="return confirm('¿Desea eliminar el tipo?')"><i class="fas fa-trash-alt"></i></button>
						</form>
						@else
						<span data-toggle="tooltip" data-placement="bottom" data-html="true" title="Existen tareas asociadas a este tipo">
							<button class="btn btn-danger" disabled="true" style="pointer-events: none;"><i class="fas fa-trash-alt"></i></button>
						</span>
						@endif
					</td>					
				</tr>
				@endforeach
			</tbody>
		</table>
	{{$categorias->links()}}
	@else
		<hr>
		<h3 class="text-center">No hay Tipos de Proyecto</h3>
	@endif	
@endsection

{{-- Modal --}}
@if(empty($editar))
{{-- Crear categoria --}}
@section('modal-title')
Crear Tipo Proyecto
@endsection
@section('modal-content')
@include('layouts.errors')
<form class="form-horizontal" method="POST" action="{{action('CategoriasController@store')}}">
    {{csrf_field()}}
    <div class="form-group">
        <label for="nombre">Nombre tipo proyecto</label>       
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
{{-- Modificar categoria --}}
@section('modal-title')
Modificar Tipo Proyecto
@endsection
@section('modal-content')
@include('layouts.errors')
<form class="form-horizontal" method="POST" action="{{action('CategoriasController@update', $editar->id)}}">
    {{csrf_field()}}
    {{method_field('PUT')}}
    <div class="form-group">
        <label for="nombre">Nombre tipo proyecto</label>       
        <input type="text" class="form-control" id="nombre" required name="nombre" value="{{$editar->nombre}}">        
    </div>       
    <div class="form-group text-center">
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </div>
</form>
<script type="text/javascript">
	$(document).ready(function(){
		$('#exampleModal').modal('toggle')
	});	
</script>
@endsection
@endif
