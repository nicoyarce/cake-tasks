@extends('layouts.master')
@section('content')
<div class="row justify-content-between">
    <h1>Crear proyecto</h1>
    <div class="col-4">
        <a type="button" class="btn btn-primary float-right" href="/proyectos">Atrás <i class="fas fa-arrow-left "></i></a>
    </div>    
</div>
<hr>
@include('layouts.errors')
<form class="form-horizontal" method="POST" action="/proyectos">
    {{csrf_field()}}
    <div class="form-row">
        <div class="form-group col-6">
            <label for="nombre">Nombre</label>       
            <input type="text" class="form-control" id="nombre" required name="nombre">     
        </div>        
        <div class="form-group col-6" id="divCategorias">
            <label for="nombre">Tipos sub-proyecto</label>
            <select multiple class="form-control" id="listaCategorias" name="listaCategorias[]">
                @foreach ($categorias as $categoria)                          
                    <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
                @endforeach
            </select>               
        </div>            
    </div>
    <div class="form-row">
        <div class="form-group col-6">
            <label for="fecha_inicio">FIR</label>            
            <input class="form-control" type="date" id="fecha_inicio" required name="fecha_inicio">
        </div>
        <div class="form-group col-6">
            <label for="fecha_termino">FTR</label>
            <input class="form-control" type="date" id="fecha_termino_original" required name="fecha_termino_original">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-12">
            <label for="observaciones">Observaciones</label>
            <button id="agregaObs" type="button" class="btn btn-success btn-sm ml-2 mb-2">Agregar <i class="fas fa-plus"></i></button>
            <div id="listaObservaciones" class="form-group">                
                <div id="fila_0" class="fila col-12 row form-group pr-0">
                    <input id="observacion_" name="observaciones[]" value="" class="texto form-control col-11">
                    <button disabled="true" id="quitaObs_" type="button" class="quitar btn btn-danger btn-sm ml-3">Quitar <i class="fas fa-minus" ></i></button>
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
    <div class="form-group text-center">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>
<script>
    $(document).ready(function(){
        iniciarMultiSelect();
        var nroObservaciones = $("#listaObservaciones").children().length;
        console.log(nroObservaciones);
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
            console.log(nroObservaciones);
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
            console.log(nroObservaciones);
            if(nroObservaciones<=1){
                $("#listaObservaciones .fila:first").children(".quitar").prop('disabled', true);
            }
            else{
                $("#listaObservaciones .fila:first").children(".quitar").prop('disabled', false);
            }
        });
    });
</script>
@endsection
