@extends('layouts.master')
@section('content')
<h1>Editar usuario</h1>
<hr>
@include('layouts.errors')
<form method="POST" action="{{action('UsersController@update', $usuario)}}">
    {{csrf_field()}}
    {{method_field('PUT')}}
    <div class="form-group">
        <label for="nombre" class="col-10 col-form-label">Nombre</label>
        <div class="col-10">
            <input type="text" id="nombre" name="nombre" class="form-control" readonly value="{{$usuario->nombre}}">
        </div>
    </div>
    <div class="form-group">
        <label for="run" class="col-10 col-form-label">RUN</label>
        <div class="col-10">
            <input type="text" id="run" name="run" class="form-control" readonly value="{{$usuario->run}}">
        </div>
    </div>
    <div class="form-group">
        <label for="password" class="col-10 col-form-label">Contraseña (dejar en blanco si no es necesario el cambio)</label>
        <div class="col-10">
            <input type="text" id="password" name="password" class="form-control" value="">
        </div>
    </div>
    <div class="form-group">
        <label for="role_id" class="col-10 col-form-label">Rol de usuario</label>
        <div class="col-10">
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
    </div>
    @if($usuario->role_id==1)
    <div class="form-group" id="divProyectos" style="display: none;">
    @else    
    <div class="form-group" id="divProyectos">
        @endif
        <label for="listaProyectos" class="col-10 col-form-label">Lista de proyectos (mantenga pulsado Ctrl para seleccionar varios)</label>
        <div class="col-10">                        
            <select multiple class="form-control" id="listaProyectos" name="listaProyectos[]">
                @foreach ($proyectos as $proyecto)
                    @if($usuario->proyectos->contains($proyecto))
                    <option selected value="{{$proyecto->id}}">{{$proyecto->nombre}}</option>
                    @else
                    <option value="{{$proyecto->id}}">{{$proyecto->nombre}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group text-center">
        <button class="btn btn-primary" type="submit">Modificar usuario</button>
    </div>
</form>
<script src="/js/jquery-3.3.1.min.js"></script>
<script>
    $(document).ready(function(){    
        $("#role_id").change(function(){            
            if($(this).val()==1){                
                $("#divProyectos").hide();
            }
            else{                
                $("#divProyectos").show();
            }
        });
    });
</script>
@endsection
