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
        <div class="form-group col-4">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="form-control" value="{{$usuario->nombre}}">
        </div>
        <div class="form-group col-4">
            <label for="nombre">UU.RR.</label>
            <input type="text" id="cargo" name="cargo" class="form-control" value="{{$usuario->cargo}}">
        </div>
        <div class="form-group col-4">
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
        <div class="form-group col-6" id="divProyectos">                
            <label for="listaProyectos">Lista de proyectos</label>
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
<script>
    // Este codigo revisa el combobox con el tipo de usuario para cambiar el tipo de lista de proyectos
    $(document).ready(function(){
        if($("#role_id").val() == "1") { //es admin
            $("#divProyectos").hide();
            $('select[multiple]').multiselect('unload');
            $("#listaProyectos").removeAttr('multiple');
        } else if ($("#role_id").val() == "3") { //es usuario       
            $("#divProyectos").show();
            $('select[multiple]').multiselect('unload');
            $("#listaProyectos").removeAttr('multiple');
        } else {                
            $("#divProyectos").show();
            $("#listaProyectos").attr('multiple', 'multiple');
            iniciarMultiSelect();
        }
        
        $("#role_id").change(function(){      
            $('select[multiple]').multiselect('reset');      
            if($(this).val() == "1") { //es admin
                $("#divProyectos").hide();
            } else if ($(this).val() == "3") { //es usuario       
                $("#divProyectos").show();         
                $('select[multiple]').multiselect('unload');
                $("#listaProyectos").removeAttr('multiple');
            } else {                
                $("#divProyectos").show();
                $("#listaProyectos").attr('multiple', 'multiple');
                iniciarMultiSelect();
            }
        });
    });
</script>
@endsection
