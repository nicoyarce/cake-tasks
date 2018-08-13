@extends('layouts.master')
@section('content')
@include('layouts.errors')
<div class="row justify-content-between">
    <div class="col-4">
        <h1>Usuarios</h1>
    </div>
    <div class="col-4">
        <a type="button" class="btn btn-success float-right" href="{{action('UsersController@create')}}" role="button">Crear usuario
            <i class="fas fa-plus"></i>
        </a>
    </div>
</div>
@if(count($usuarios)>0)
<table class="table">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>RUN</th>
            <th>Rol</th>            
            <th>Editar</th>
            <th>Borrar</th>
        </tr>
    </thead>
    
    <tbody>
        @foreach ($usuarios as $usuario)
        <tr>
            <td>{{$usuario->nombre}}</td>
            <td>{{$usuario->run}}</td>
            <td>{{$usuario->getRoleNames()->first()}}</td>
            @if(Auth::user()->id != $usuario->id)            
            <td> 
                <a href="{{action('UsersController@edit', $usuario['id'])}}" type="button" class="btn btn-primary" >
                    <i class="fas fa-edit"></i>
                </a>
            </td>
            <td>
                <form method="POST" action="{{route('users.destroy', $usuario->id)}}">
                    {{csrf_field()}}
                    {{method_field('DELETE')}}
                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Desea eliminar el usuario?')"><i class="fas fa-trash-alt"></i></button>
                </button>
                </form>
            </td>
            @else
            <td>-</td>
            <td>-</td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>
{{$usuarios->links()}}
@else
<h1 align="center">No hay usuarios</h1>
@endif
@endsection
