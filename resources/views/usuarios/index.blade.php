@extends('layouts.master')
@section('content')
@include('layouts.errors')
    <div class="row justify-content-between">
    <div class="col-4">
        <h1>Usuarios</h1>
    </div>
    <div class="col-4">        
        <a type="button" class="btn btn-success float-right" href="{{action('RegistrationController@create')}}" role="button">Crear usuario
            <i class="fas fa-plus"></i>
        </a>
    </div>
</div>
@if(count($usuarios)>0)
<table class="table">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>RUT</th>            
            <th>Rol</th>
            <th>Ver proyectos</th>           
            <th>Editar</th>
            <th>Borrar</th>
        </tr>
    </thead>
    
    <tbody>
        @foreach ($usuarios as $usuario)
        <tr>
            <td>{{$usuario->nombre}}</td>
            <td>{{$usuario->rut}}</td>            
            <td>{{$usuario->role->descripcion}}</td>
            <td>
                <a href="{{action('UsersController@show', $usuario['id'])}}" type="button" class="btn btn-primary" >
                    <i class="fas fa-eye"></i>
                </a>
            </td>
            <td>
                <a href="{{action('UsersController@edit', $usuario['id'])}}" type="button" class="btn btn-primary" >
                    <i class="fas fa-edit"></i>
                </a>
            </td>
            <td>
                <form method="POST" action="{{action('UsersController@destroy', $usuario)}}">
                    {{csrf_field()}}
                    {{method_field('DELETE')}}
                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Desea eliminar el usuario?')">
                <i class="fas fa-trash-alt"></i></a>
                </button>
            </form>
        </td>
    </tr>
    @endforeach 
</tbody>
</table>
@else
<h1 align="center">No hay usuarios</h1>
@endif
@endsection
