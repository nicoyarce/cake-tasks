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
<table id="tablaUsuarios" class="table table-hover">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Cargo</th>
            <th>RUN</th>
            <th>Rol</th>
            <th>Editar</th>
            <th>Borrar</th>
        </tr>
    </thead>    
    <tbody>
        @foreach ($usuarios as $usuario)
        <tr id="{{$usuario->id}}">
            <td>{{$usuario->nombre}}</td>
            <td>{{$usuario->cargo}}</td>
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
            <td>
                <button data-toggle="tooltip" data-placement="bottom" data-html="true" title="No puede editarse a si mismo" class="btn btn-primary" disabled="true"><i class="fas fa-edit"></i></button>
            </td>
            <td>
                <button data-toggle="tooltip" data-placement="bottom" data-html="true" title="No puede eliminarse a si mismo" class="btn btn-danger" disabled="true"><i class="fas fa-trash-alt"></i></button>
            </td>
        @endif
        </tr>
        @endforeach
    </tbody>
</table>
{{$usuarios->links()}}
@else
<hr>
<h3 class="text-center">No hay usuarios</h3>
@endif
<link rel="stylesheet" type="text/css" href="/css/fixedHeader.dataTables.min">
<script src="/js/dataTables.fixedHeader.min.js"></script>
<script src="/js/jquery.stickytableheaders.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tablaUsuarios').stickyTableHeaders();
        $('#tablaUsuarios').DataTable( {
            //"order": [[ 1, 'asc' ], [ 2, 'asc' ]],
            "fixedHeader": false,
            "ordering": false,
            "paging":   false,
            "language": {
                "url": "/js/locales/datatables.net_plug-ins_1.10.19_i18n_Spanish.json"
            }
        } );
    } );    
    @if (session('idUserMod'))
        window.scrollTo(0, $("#{{session('idUserMod')}}").offset().top-100);
        $(document).ready(function(){
            $("#{{session('idUserMod')}}").effect("highlight", {}, 3000);
        });
    @endif
</script>
@endsection
