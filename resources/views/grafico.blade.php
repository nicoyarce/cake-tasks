@extends('layouts.master')
@section('content')
<h1 id="titulo" align="center">Gráfico de tareas</h1>
<hr>
<div class="row">
    <div class="col-md-8 order-md-1" id="grafico">
        <br>
        <br>
    </div>
    <div class="col-md-4 order-md-2 mb-4">
        <h5 id="nroTareas">Numero de tareas: </h5>
        <form action="{{action('GraficosController@filtrar')}}" method="POST">
            {{csrf_field()}}
            <div class="form-group">
                <label for="opcion">Filtro Area:</label>
                <select class="form-control" id="opcion" required name="opcion">
                    <option selected value="0">Todas</option>
                    @foreach ($areas as $area)
                    <option value="{{$area->id}}">{{$area->nombrearea}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
        <button id="reset" type="button" onclick="reset()" class="btn btn-primary">Reset zoom</button>
    </div>
</div>
<link rel="stylesheet" href="/css/estiloGrafico.css">
<script src="/js/d3.v3.min.js"></script>
<script src="/js/d3.tip.v0.6.3.js"></script>
<script src="/js/d3-time-format.v2.min.js"></script>
<script src="/js/d3-zoom.v1.min.js"></script>
<script src="/js/dibujarGrafico.js"></script>
@endsection
@include ('layouts.bottom')
