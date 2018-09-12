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
        <div class="row form-group">
            <ul id="detallesTarea" class="list-group w-100" style="display: none;">
                <li class="list-group-item"><span class="titulospan">Nombre tarea:</span><span id="nombre"></span></li>
                <li class="list-group-item"><span class="titulospan">Área:</span><span id="area"></span></li>
                <li class="list-group-item"><span class="titulospan">FIT:</span><span id="fir"></span></li>
                <li class="list-group-item"><span class="titulospan">FTT original:</span><span id="ftro"></span></li>
                <li class="list-group-item"><span class="titulospan">FTT modificada:</span><span id="ftrm"></span></li>
                <li class="list-group-item"><span class="titulospan">Atraso [días]:</span><span id="atraso"></span></li>
                <li class="list-group-item"><span class="titulospan">Avance [%]:</span><span id="avance"></span></li>
            </ul>
        </div>        
    </div>
    <br>    
</div>
{{-- <div class="row mt-3 col-auto mr-auto">
    <button value="0" id="activar" class="btn btn-primary"> <i class="fas fa-search"></i> <span id="botonZoom">Activar zoom</span></button>
</div> --}}
<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">    
    <div class="modal-dialog modal-full" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{$proyecto->nombre}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="gantt_here" style='width:100%; height:100%;'></div>                
            </div>            
        </div>
    </div>
</div>
<link rel="stylesheet" href="/css/estiloGrafico.css">
<link rel="stylesheet" href="/css/anythingzoomer.css">
<script src="/js/jquery.anythingzoomer.min.js"></script>
<script src="/dhtmlxGantt/codebase/dhtmlxgantt.js"></script>
<link href="/dhtmlxGantt/codebase/dhtmlxgantt.css" rel="stylesheet">
<script src="/dhtmlxGantt/codebase/locale/locale_es.js" charset="utf-8"></script>
<script src="/js/d3.v3.min.js"></script>
<script src="/js/d3.tip.v0.6.3.js"></script>
<script src="/js/d3-time-format.v2.min.js"></script>
<script src="/js/moment.js"></script>
<script src="/js/dibujarGrafico.js"></script>
<script type="text/javascript">    
    $(document).ready(function(){
        $("#barra").hide();
        $("#footer").hide();
        dibujarGrafico({!!$tarea!!});        
    });
    gantt.config.xml_date = "%Y-%m-%d %H:%i:%s"; 
    gantt.init("gantt_here"); 
    gantt.load("/grafico/{{$proyecto->id}}/detalles");       
</script>
@endsection
