@extends('layouts.master')
@section('content')
<div class="row justify-content-between">
    <h1>Crear usuario</h1>
    <div class="col-4">
        <a type="button" class="btn btn-primary float-right" href="/users/">Atrás <i class="fas fa-arrow-left "></i></a>
    </div>
</div>
<hr>
@include('layouts.errors')
<form class="form-horizontal" method="POST" action="{{action('UsersController@store')}}">
    {{csrf_field()}}
    <div class="row">
        <div class="form-group col-4">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="form-control" required>
        </div>
        <div class="form-group col-4">
            <label for="nombre">UU.RR.</label>
            <input type="text" id="cargo" name="cargo" class="form-control">
        </div>
        <div class="form-group col-4">
            <label for="run">RUN</label>
            <input type="text" id="run" name="run" class="form-control" required>
        </div>
    </div>
    <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="password_confirmation">Confirme Contraseña</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
    </div>
    <div class="row">
        <div class="form-group col-6">
            <label for="role_id">Rol de usuario</label>
            <select class="form-control" name="role_id" id="role_id">
                <option value="" disabled selected>Elija una opción</option>
                @foreach ($roles as $rol)
                <option value="{{$rol->id}}">{{$rol->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-6" id="divProyectos">
            <label for="listaProyectos">Lista de proyectos</label>
            <select multiple class="form-control" id="listaProyectos" name="listaProyectos[]">                                           
                @foreach ($proyectos as $proyecto)
                    <option value="{{$proyecto->id}}">{{$proyecto->nombre}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group text-center">
        <button class="btn btn-primary" type="submit">Crear usuario</button>
    </div>
</form>
<script>
    $(document).ready(function(){
        $("#run").rut().on('rutValido', function(e, rut, dv) {
            alert("El run " + rut + "-" + dv + " es correcto");
        }, { minimumLength: 7} );
        
        if($("#role_id").val() == 1 || $("#role_id").val() == ""){ //es admin
            $("#divProyectos").hide();
        } else{ //es ocr o usuario
            $("#divProyectos").show();
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
