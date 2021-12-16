@extends('layouts.master')
@section('content')
<div class="row justify-content-between">
    <h1>Crear tarea</h1>
    <div class="col-4">
        <a type="button" class="btn btn-primary float-right" href="/proyectos/{{$proyecto->id}}">Atrás <i class="fas fa-arrow-left "></i></a>
    </div>
</div>
<hr>
@include('layouts.errors')
<form class="form-horizontal" method="POST" action="/tareas">
    <input type="hidden" id="proyecto_id" name="proyecto_id" value="{{$proyecto->id}}">
    {{csrf_field()}}
    <h4><p class="alert alert-primary"><b>Proyecto</b>: {{$proyecto->nombre}}</p></h4>
    <div class="form-row">
        <div class="form-group col-8">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" required name="nombre">
        </div>        
        <div class="form-group col-4">
            <label for="area_id">Nro. Documento</label>
            <input type="text" class="form-control" id="nro_documento" name="nro_documento" value="">
        </div>        
    </div>
    <div class="form-row">
        <div class="form-group col-8">
            <label for="area_id">Área</label>
            <select class="form-control" id="area_id" required name="area_id">
                <option value="" disabled selected>Elija una opción</option>
                @foreach ($areas as $area)
                <option value="{{$area->id}}">{{$area->nombrearea}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-4">
            <label for="switches">Opciones</label>
            <div class="mx-auto d-flex align-items-center">
                <div class="custom-control custom-switch custom-control-inline">
                    <input type="checkbox" class="custom-control-input" id="critica" name="critica">
                    <label class="custom-control-label" for="critica">¿Ruta crítica?</label>
                </div>
                <div class="custom-control custom-switch custom-control-inline">
                    <input type="checkbox" class="custom-control-input" id="trabajo_externo" name="trabajo_externo">
                    <label class="custom-control-label" for="trabajo_externo">¿Trabajo ASMAR?</label>
                </div>
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-6">
            <label for="fecha_inicio">FIT</label>
            <input class="form-control" type="date" id="fecha_inicio" required name="fecha_inicio" value="">
        </div>
        <div class="form-group col-6">
            <label for="fecha_termino">FTT original</label>
            <input class="form-control" type="date" id="fecha_termino_original" required name="fecha_termino_original" value="">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-12">
            <label for="observaciones">Observaciones</label>
            <button id="agregaObs" type="button" class="btn btn-success btn-sm ml-2 mb-2">Agregar <i class="fas fa-plus"></i></button>
            <div id="listaObservaciones" class="form-group">                
                <div id="fila_0" class="fila col-12 row form-group pr-0">
                    <input id="observacion_0" name="observaciones[]" value="" class="texto form-control col-11">
                    <button disabled="true" id="quitaObs_0" type="button" class="quitar btn btn-danger btn-sm ml-3">Quitar <i class="fas fa-minus" ></i></button>
                </div>                
            </div>
            {{-- Fila dummy --}}
            <div id="fila_" class="fila col-12 row form-group pr-0" style="display: none;">
                <input disabled="true" id="observacion_" name="observaciones[]" value="" class="texto form-control col-11">
                <input disabled="true" type="hidden" id="id_observacion_" name="ids_observaciones[]" value="" class="form-control">
                <button id="quitaObs_" type="button" class="quitar btn btn-danger btn-sm ml-3">Quitar <i class="fas fa-minus"></i></button>
            </div>                  
        </div>          
    </div>
    <div class="form-row">
        <div class="form-group col-6">
            <label for="tipo_tarea">Tipo tarea</label>
            <select class="form-control" id="tipo_tarea" required name="tipo_tarea">
                <option value="" disabled selected>Elija una opción</option>
                @foreach($tipo_tareas as $tipo_tarea)
                <option value="{{$tipo_tarea->id}}">{{$tipo_tarea->descripcion}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-6">
            <label for="tipo_tarea">Tipo proyecto</label>
            <select class="form-control" id="categoria_id" required name="categoria_id">
                <option value="" disabled selected>Elija una opción</option>
                @foreach($categorias as $categoria)
                <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-row" id="fila_avance" style="display: none;">
        <div class="form-group col-12">
            <label for="avance">Porcentaje avance</label>
            <select class="form-control" id="avance" required name="avance">                
            </select>
        </div>
    </div>
    <div class="form-group text-center mt-4">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>
<script>
    $(document).ready(function(){        
        var nroObservaciones = $("#listaObservaciones").children().length;
        if(nroObservaciones<=1){
            $("#quitaObs").prop('disabled', true);
        }
        else{
            $("#quitaObs").prop('disabled', false);
        }       
    
        $("#agregaObs").click(function(){
            var nroObservaciones = $("#listaObservaciones").children().length;
            let fila_dummy = $("#fila_").clone(true, true);
            let id_original = fila_dummy.attr('id');
            fila_dummy.attr('id',id_original+nroObservaciones); 
            fila_dummy.removeAttr('style'); 
            fila_dummy.children().prop('disabled', false);
            fila_dummy.children().each(function(){          
                $(this).attr('id',$(this).attr('id')+nroObservaciones);
            });
            fila_dummy.appendTo("#listaObservaciones")
            nroObservaciones = $("#listaObservaciones").children().length;
            if(nroObservaciones<=1){
                $("#listaObservaciones .fila:first").children(".quitar").prop('disabled', true);
            }
            else{
                $("#listaObservaciones .fila:first").children(".quitar").prop('disabled', false);
            }       
        });

        $(".quitar").click(function(){
            $(this).parent().remove();
            var nroObservaciones = $("#listaObservaciones").children().length;
            if(nroObservaciones<=1){
                $("#listaObservaciones .fila:first").children(".quitar").prop('disabled', true);
            }
            else{
                $("#listaObservaciones .fila:first").children(".quitar").prop('disabled', false);
            }
        });

        $("#tipo_tarea").on('change', function(){
            var ruta = '/tareas/consultaAvances';
            let tipo_tarea = $("#tipo_tarea").val();            
            let datos = {
                "tipo_tarea": tipo_tarea
            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            })    
            $.post(ruta, datos, function(data){
                $("#avance").empty();
                if(data && data.length){                    
                    $.each(data, function(key, value){                    
                        $("#avance").append('<option value="'+value.porcentaje+'">'+value.porcentaje+'% - '+value.glosa+'</option>');
                    });
                    $("#fila_avance").show();
                } else {
                    $("#avance").append('<option disabled selected value="">Tipo tarea no registra nomenclaturas de avance</option>');
                }
            });
        });
        $("#tipo_tarea").trigger("change");
    });
</script>
@endsection
