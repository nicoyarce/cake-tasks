@extends('layouts.master')
@section('content')
@include('layouts.errors')
<form method="POST" action="{{action('UsersController@store')}}">
    {{csrf_field()}}
    <h1 class="h3 mb-3 font-weight-normal">Crear usuario</h1>
    <div class="form-group">
        <label for="nombre" class="col-10 col-form-label">Nombre</label>
        <div class="col-10">
            <input type="text" id="nombre" name="nombre" class="form-control" required>
        </div>
    </div>
    <div class="form-group">
        <label for="run" class="col-10 col-form-label">RUN</label>
        <div class="col-10">
            <input type="text" id="run" name="run" class="form-control" required>
        </div>
    </div>
    <div class="form-group">
        <label for="password" class="col-10 col-form-label">Contraseña</label>
        <div class="col-10">
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
    </div>
    <div class="form-group">
        <label for="password_confirmation" class="col-10 col-form-label">Confirme Contraseña</label>
        <div class="col-10">
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
        </div>
    </div>
    <div class="form-group">
        <label for="role_id" class="col-10 col-form-label">Rol de usuario</label>
        <div class="col-10">
            <select class="form-control" name="role_id" id="role_id">
                <option value="" disabled selected>Elija una opción</option>    
                @foreach ($roles as $rol)
                <option value="{{$rol->id}}">{{$rol->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group" id="divProyectos" style="display: none;">
        <label for="listaProyectos" class="col-form-label">Lista de proyectos (mantenga pulsado Ctrl para seleccionar varios)</label>
        <div class="col-10">
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
<script src="/js/jquery-3.3.1.min.js"></script>
<script src="/js/jquery.rut.min.js"></script>
<script>
    $(document).ready(function(){
        $("#run").rut().on('rutValido', function(e, rut, dv) {
            alert("El run " + rut + "-" + dv + " es correcto");
        }, { minimumLength: 7} );

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
