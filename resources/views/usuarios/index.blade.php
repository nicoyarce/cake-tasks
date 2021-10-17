@extends('layouts.master')
@section('content')
@include('layouts.errors')
<form method="POST">
    {{csrf_field()}}
    {{method_field('DELETE')}}
    <div class="row justify-content-between">
        <div class="col-8">
            <h1>Usuarios</h1>
        </div>
        <div class="col-2 mb-2">
            <button formaction="{{route('users.destroySelected')}}" id="borraSelec" type="submit" disabled class="btn btn-danger float-right" onclick="return confirm('¿Desea eliminar los usuarios seleccionados?')">Eliminar marcados
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
        <div class="btn-group col-2 mb-2">
            <button type="button" class="btn btn-success dropdown-toggle float-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Agregar <i class="fas fa-plus"></i>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{action('UsersController@create')}}">Usuario</a>
                <a class="dropdown-item" href="/users/cargarXLS">Excel / XLS</a>
            </div>
        </div>
    </div>
    @if(count($usuarios)>0)    
        <table id="tablaUsuarios" class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>UU.RR.</th>
                    <th>RUN</th>
                    <th>Rol</th>
                    <th>Editar</th>
                    <th>Borrar</th>
                    <th width="1%">Borrado</th>
                </tr>
            </thead>
            
            <tbody>
                @foreach ($usuarios as $usuario)
                <tr id="{{$usuario->id}}">
                    <td>{{$usuario->nombre}}</td>
                    <td>{{$usuario->cargo}}</td>
                    <td>{{$usuario->run}}</td>
                    <td>{{$usuario->getRoleNames()->first()}}</td>
                    <td> 
                        <a href="{{action('UsersController@edit', $usuario['id'])}}" type="button" class="btn btn-primary" >
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                    @if(Auth::user()->id != $usuario->id)                    
                        <td>
                            <button formaction="{{route('users.destroy', $usuario->id)}}" type="submit" class="btn btn-danger" onclick="return confirm('¿Desea eliminar el usuario?')"><i class="fas fa-trash-alt"></i></button>                            
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="eliminar" name="eliminar[]" value="{{$usuario->id}}">
                                <label class="form-check-label" for="eliminar"></label>
                            </div>                    
                        </td>
                    @else                    
                        <td>
                            <span data-toggle="tooltip" data-placement="bottom" data-html="true" title="No puede eliminarse a si mismo">
                                <button class="btn btn-danger" disabled="true" style="pointer-events: none;"><i class="fas fa-trash-alt"></i></button>
                            </span>
                        </td>
                        <td>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" disabled>
                              <label class="form-check-label" for="defaultCheck1"></label>
                            </div>  
                        </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
    <hr>
    <h3 class="text-center">No hay usuarios</h3>
    @endif
</form>
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
        });
        $("input[type='checkbox']").on('click', function (){
            if( $("input[type='checkbox']:checked").length > 0) {
                $("#borraSelec").prop('disabled', false);
            } else {
                $("#borraSelec").prop('disabled', true);
            }
        });
    });    
    @if (session('idUserMod'))
        window.scrollTo(0, $("#{{session('idUserMod')}}").offset().top-100);
        $(document).ready(function(){
            $("#{{session('idUserMod')}}").effect("highlight", {}, 3000);
        });
    @endif
</script>
@endsection
