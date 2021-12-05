@extends('layouts.master')
@section('content')
@section('tituloPagina', 'Roles y Permisos')
<div class="row">
	<div class="col-10">
		<h1>Roles y Permisos</h1>
	</div>
	<div class="col-2">
		<a type="button" class="btn btn-success float-right"
		@if(empty($editar))
		data-toggle="modal" data-target="#exampleModal" href=""
		@else
		href="{{action('RolesController@indexConModal')}}"
		@endif
		role="button">Crear Rol <i class="fas fa-plus"></i>
		</a>
	</div>
</div>
@include('layouts.errors')
@if(count($roles)>0)
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Nombre Rol</th>
                <th>Fecha Creación</th>
                <th>Editar Rol</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $rol)
            <tr id="{{$rol->id}}">
                <td>{{$rol->name}}</td>
                <td>{{$rol->created_at->format('d-M-Y')}}</td>
                <td>
                    <a href="{{action('RolesController@edit', $rol['id'])}}" type="button" class="btn btn-primary">
                    <i class="fas fa-edit"></i>
                    </a>
                </td>                
                <td>
                    @if($rol->habilitaBorrado)
                    <form method="POST" action="{{action('RolesController@destroy', $rol->id)}}">
                        {{csrf_field()}}
                        {{method_field('DELETE')}}
                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Desea eliminar el rol?')"><i class="fas fa-trash-alt"></i></button>
                    </form>
                    @else
                    <span data-toggle="tooltip" data-placement="bottom" data-html="true" title="Existen usuarios asociados a este rol">
                        <button class="btn btn-danger" disabled="true" style="pointer-events: none;"><i class="fas fa-trash-alt"></i></button>
                    </span>
                    @endif
                </td>					
            </tr>
            @endforeach
        </tbody>
    </table>	
@else
	<hr>
	<h3 class="text-center">No hay áreas</h3>
@endif	
@endsection

{{-- Modal --}}
@if(empty($editar))
    {{-- Crear Rol --}}
    @section('modal-title')
        Crear rol
    @endsection
    @section('modal-content')
        @include('layouts.errors')
        <form class="form-horizontal" method="POST" action="{{action('RolesController@store')}}">
            {{csrf_field()}}
            <div class="form-group">
                <label for="nombre">Nombre rol</label>       
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
    {{-- Modificar Rol --}}
    @section('modal-title')
        Modificar Rol
    @endsection
    @section('modal-content')
        @include('layouts.errors')
        <form class="form-horizontal" method="POST" action="{{action('RolesController@update', $editar->id)}}">
            {{csrf_field()}}
            {{method_field('PUT')}}
            <div class="form-group">
                <label for="nombre">Nombre rol</label>       
                <input type="text" class="form-control" id="nombre" required name="nombre" value="{{$editar->name}}">        
            </div>
            <hr>
            <label>Permisos</label> 
            @foreach ($permisos as $permiso)              
                <div class="form-group">                    
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="permiso_{{$permiso->name}}" name="permisos[]" value="{{$permiso->id}}"
                        @foreach ($actuales as $actual)
                            @if ($actual->id == $permiso->id)
                            checked     
                            @endif 
                        @endforeach                                               
                        >
                        <label class="custom-control-label" for="permiso_{{$permiso->name}}">{{ucwords(str_replace('_',' ',$permiso->name))}}</label>                    
                    </div>
                </div>
            @endforeach                     
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
