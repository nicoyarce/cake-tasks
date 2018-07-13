@extends('layout')
@section('content')
<h1 id="titulo" align="center">Gráfico de tareas</h1>
<hr>
 
<div id="grafico" align="center">
    <button id="reset" type="button" onclick="resetZoom()" align="center">Reset zoom</button>
    <br>
    <br>
</div>

<link rel="stylesheet" href="/css/estiloGrafico.css">
<script src="/js/d3.v3.min.js"></script>
<script src="/js/svg-pan-zoom.min.js"></script>
<script src="/js/d3.tip.v0.6.3.js"></script>
<script src="/js/d3-time-format.v2.min.js"></script>
<script src="/js/dibujarGrafico.js"></script>
@endsection
@include ('bottom')
