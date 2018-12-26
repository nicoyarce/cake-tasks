@extends('layouts.master')
@section('content')
<meta name="_token" content="{!! csrf_token() !!}" />
<div class="row justify-content-between">
    <h4>{{$proyecto->nombre}}</h4>
    <a type="button" class="btn btn-primary btn-sm float-right" href="{{url()->previous()}}">Atrás <i class="fas fa-arrow-left "></i></a>
</div>
<hr>
<div class="row" id="graficoBotones">
    <div id="grafico" class="col-6">
    </div>
    {{-- <div id="zoom" class="col-6 p-1">
        <div class="small">
            
        </div>
    </div> --}}
    <div id="botones" class="col-6">
        <div class="row form-group">
            <div class="col-3">
                <p class="m-0 text-center font-weight-bold">AVANCE PROYECTO</p>
                <p class="m-0 text-center text-primary font-weight-bold" style="font-size:30px">{{$proyecto->avance}}%</p>
                <div class="text-center">
                    <h3><span id="critica" class="badge badge-pill badge-warning" style="display: none;">Crítica</span></h3>
                </div>
            </div>
            <div class="col-9">                            
                <svg id="simbologia" class="w-100">
                    <defs>
                        <marker
                        id="arrow"
                        markerUnits="strokeWidth"
                        markerWidth="12"
                        markerHeight="12"
                        viewBox="0 0 12 12"
                        refX="6"
                        refY="6"
                        orient="auto">
                        <path d="M2,2 L10,6 L2,10 L6,6 L2,2" style="fill: #000;"></path>
                        </marker>
                    </defs>
                </svg>                              
            </div>
        </div>
        <div class="row">
            <div class="form-group col-6">
                <label for="opcion">Filtro cargo:</label>
                <select data-id="{{$proyecto->id}}" class="form-control" id="opcionArea" name="opcionArea">
                    <option selected value="0">Todas</option>
                    @foreach ($areas as $area)
                    <option value="{{$area->id}}">{{$area->nombrearea}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-6">
                <label for="opcion">Filtro color:</label>
                <select data-id="{{$proyecto->id}}" class="form-control" id="opcionColor" name="opcionColor">
                    <option selected value="TODAS">Todas</option>
                    <option value="VERDE">Verde</option>
                    <option value="AMARILLO">Amarillo</option>
                    <option value="NARANJO">Naranjo</option>
                    <option value="ROJO">Rojo</option>
                </select>
            </div>
        </div>
        
        <div class="row" style="height: 80px" >
            <ul class="detallesTarea list-group w-100 mb-2" style="display: none;">
                <li  class="list-group-item"><span class="titulospan">Nombre tarea:</span><span id="nombre"><br></span></li>
            </ul>
        </div>
        <div class="row">
            <ul class="detallesTarea list-group w-50" style="display: none;">
                <li class="list-group-item"><span class="titulospan">Área:</span><br><span id="area"></span></li>
                <li class="list-group-item"><span class="titulospan">FIT:</span><span id="fir"></span></li>
                <li class="list-group-item"><span class="titulospan">FTT original:</span><span id="ftro"></span></li>
                <li class="list-group-item"><span class="titulospan">FTT modificada:</span><span id="ftrm"></span></li>
                <li class="list-group-item"><span class="titulospan">Atraso [días]:</span><span id="atraso"></span></li>
                <li class="list-group-item"><span class="titulospan">Avance [%]:</span><span id="avance"></span></li>
            </ul>
            <ul class="detallesTarea list-group w-50" style="display: none;">
                <li class="list-group-item"><span class="titulospan">Observaciones:</span><br><span id="observaciones"></span></li>
            </ul>
        </div>
    </div>
<br>
</div>
<link rel="stylesheet" href="/css/estiloGrafico.css">
<script src="/js/d3.v3.min.js"></script>
<script src="/js/d3-time-format.v2.min.js"></script>
<script src="/js/moment.js"></script>
<script src="/js/dibujarGrafico.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$("#barra").hide();
$("#footer").hide();
dibujarGrafico({!!$tareas!!});
dibujarSimbologia();
});
</script>
@endsection
