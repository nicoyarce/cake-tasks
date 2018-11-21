@extends('layouts.master')
@section('content')
<div class="row justify-content-between">
    <h1>Editar usuario</h1>
    <div class="col-4">
        <a type="button" class="btn btn-primary float-right" href="/users/">Atrás <i class="fas fa-arrow-left "></i></a>
    </div>
</div>
<hr>
@include('layouts.errors')
<form class="form-horizontal" method="POST" action="{{action('UsersController@update', $usuario)}}">
    {{csrf_field()}}
    {{method_field('PUT')}}
    <div class="row">
        <div class="form-group col-6">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="form-control" readonly value="{{$usuario->nombre}}">
        </div>
        <div class="form-group col-6">
            <label for="run">RUN</label>
            <input type="text" id="run" name="run" class="form-control" readonly value="{{$usuario->run}}">
        </div>
    </div>
    <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="text" id="password" name="password" class="form-control" value="">
        <small class="form-text text-muted">Dejar en blanco si no es necesario el cambio</small>
    </div>
    <div class="row">
        <div class="form-group col-6">
            <label for="role_id">Rol de usuario</label>
            <select class="form-control" name="role_id" id="role_id">
                @foreach ($roles as $rol)
                @if($usuario->hasRole($rol->name))
                <option selected value="{{$rol->id}}">{{$rol->name}}</option>
                @else
                <option value="{{$rol->id}}">{{$rol->name}}</option>
                @endif
                @endforeach
            </select>
        </div>
        @if($usuario->hasRole('Administrador'))
        <div class="form-group" id="divProyectos" style="display: none;">
            @else
            <div class="form-group col-6" id="divProyectos">
                @endif
                <label for="listaProyectos">Lista de proyectos</label>
                <select @if(!$usuario->hasRole('Usuario')) multiple @endif class="form-control" id="listaProyectos" name="listaProyectos[]">
                    @if($usuario->hasRole('Usuario'))
                    <option value="" disabled selected>Elija una opción</option>
                    @endif
                    @foreach ($proyectos as $proyecto)
                    @if($usuario->proyectos->contains($proyecto))
                    <option selected value="{{$proyecto->id}}">{{$proyecto->nombre}}</option>
                    @else
                    <option value="{{$proyecto->id}}">{{$proyecto->nombre}}</option>
                    @endif
                    @endforeach
                </select>               
                    <small id="sugerencia" class="form-text text-muted" @if($usuario->hasRole('Usuario')) style="display: none;" @endif>Mantenga pulsado Ctrl para seleccionar varios</small>
            </div>
        </div>
        <div class="form-group text-center">
            <button class="btn btn-primary" type="submit">Modificar usuario</button>
        </div>
    </form>
    <script src="/js/jquery-3.3.1.min.js"></script>
    <script>
    // Este codigo revisa el combobox con el tipo de usuario para cambiar el tipo de lista de proyectos
    $(document).ready(function(){
    $("#role_id").change(function(){
        if($(this).val()==1){
            $("#divProyectos").hide();
        }
        else{
            $("#divProyectos").show();
        }
        if($(this).val()==3){
            $("#listaProyectos").removeAttr('multiple');
            $("#sugerencia").hide();
            }
        else{
            $("#listaProyectos").attr('multiple', 'multiple');
            $("#sugerencia").show();
        }
    });
    });
    </script>
    @endsection
