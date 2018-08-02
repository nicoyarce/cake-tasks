@extends('layouts.master')
@section('content')
<meta name="_token" content="{!! csrf_token() !!}" />
<div class="row justify-content-between">
    <h3 id="titulo" align="center">Gráfico de tareas</h3>
    <a type="button" class="btn btn-primary float-right" href="{{url()->previous()}}" title="">Atrás <i class="fas fa-arrow-left "></i></a>
</div>
<div class="row justify-content-between">
    <h5 align="center">{{$proyecto->nombre}}</h5>
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
            <label for="opcion">Filtro Area:</label>
            <select data-id="{{$proyecto->id}}" class="form-control" id="opcion" name="opcion">
                <option selected value="0">Todas</option>
                @foreach ($areas as $area)
                <option value="{{$area->id}}">{{$area->nombrearea}}</option>
                @endforeach
            </select>
        </div>
        <div class="row form-group">
            <ul id="detallesTarea" class="list-group w-100" style="display: none;">
                <li class="list-group-item">Area: <span id="area"></span></li>
                <li class="list-group-item">Fecha inicio reparaciones: <span id="fir"></span></li>
                <li class="list-group-item">Fecha termino reparaciones original: <span id="ftro"></span></li>               
                    <li class="list-group-item">Fecha termino reparaciones modificada: <span id="ftrm"></span></li>
                    <li class="list-group-item">Atraso [días]: <span id="atraso"></span></li>                
                <li class="list-group-item">Avance [%]: <span id="avance"></span></li>
            </ul>
        </div>
    </div>
    <br>
</div>
<div class="row mt-3 col-auto mr-auto">
    <button value="0" id="activar" class="btn btn-primary"> <i class="fas fa-search"></i> <span id="botonZoom">Activar zoom</span></button> 

</div>
<script src="/js/jquery-3.3.1.min.js"></script>
<link rel="stylesheet" href="/css/estiloGrafico.css">
<link rel="stylesheet" href="/css/anythingzoomer.css">
<script src="/js/d3.v3.min.js"></script>
<script src="/js/jquery.anythingzoomer.min.js"></script>
<script src="/js/d3.tip.v0.6.3.js"></script>
<script src="/js/d3-time-format.v2.min.js"></script>
<script src="/js/dibujarGrafico.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        dibujarGrafico({!!$tarea!!});        
    })
</script>
@endsection
@include ('layouts.bottom')
