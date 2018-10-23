@extends('layouts.master')
@section('content')
<meta name="_token" content="{!! csrf_token() !!}" />

<div class="row justify-content-between">
    <h4>{{$proyecto->nombre}}</h4>
    <a type="button" class="btn btn-primary btn-sm float-right" href="{{url()->previous()}}">Atrás <i class="fas fa-arrow-left "></i></a>
</div>
<hr>
<div class="row" id="graficoBotones">
    <div id="zoom" class="col-6">
        <div class="small">
            <div id="grafico"></div>
        </div>
    </div>
    <div id="botones" class="col-6">
        <div class="row form-group">
            <img class="mx-auto" src="/simbologia.jpg" alt="" width="auto" height="100px">
        </div>
        <div class="row form-group">
            <label for="opcion">Filtro Área:</label>
            <select data-id="{{$proyecto->id}}" class="form-control" id="opcion" name="opcion">
                <option selected value="0">Todas</option>
                @foreach ($areas as $area)
                <option value="{{$area->id}}">{{$area->nombrearea}}</option>
                @endforeach
            </select>
        </div>
        <div class="row" style="height: 80px" >
            <ul class="detallesTarea list-group w-100 mb-2" style="display: none;">
                <li  class="list-group-item"><span class="titulospan">Nombre tarea:</span><span id="nombre"><br></span></li>
            </ul>
        </div>
        <div class="row">
            <ul class="detallesTarea list-group w-50" style="display: none;">                
                <li class="list-group-item"><span class="titulospan">Área:</span><br/><span id="area"></span></li>
                <li class="list-group-item"><span class="titulospan">FIT:</span><span id="fir"></span></li>
                <li class="list-group-item"><span class="titulospan">FTT original:</span><span id="ftro"></span></li>
                <li class="list-group-item"><span class="titulospan">FTT modificada:</span><span id="ftrm"></span></li>
                <li class="list-group-item"><span class="titulospan">Atraso [días]:</span><span id="atraso"></span></li>
                <li class="list-group-item"><span class="titulospan">Avance [%]:</span><span id="avance"></span></li>
            </ul>
            <ul class="detallesTarea list-group w-50" style="display: none;">
                <li class="list-group-item"><span class="titulospan">Observaciones:</span><span id="observaciones"></span></li>
            </ul>
        </div>        
    </div>
    <br>    
</div>
{{-- <div class="row mt-3 col-auto mr-auto">
    <button value="0" id="activar" class="btn btn-primary"> <i class="fas fa-search"></i> <span id="botonZoom">Activar zoom</span></button>
</div> --}}

<link rel="stylesheet" href="/css/estiloGrafico.css">
<script src="/js/d3.v3.min.js"></script>
<script src="/js/d3-time-format.v2.min.js"></script>
<script src="/js/moment.js"></script>
<script src="/js/dibujarGrafico.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#barra").hide();
        $("#footer").hide();
        dibujarGrafico({!!$tarea!!});              
    });     
</script>
@endsection
