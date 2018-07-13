@extends('layout')
@section('content')
<h1>Tarea: {{$tarea->nombre}}</h1>
<ul>	
    <li>Pertenece a: {{$tarea->proyecto->nombre}}</li>
	<li>Fecha inicio: {{$tarea->fechainicio}}</li>
	<li>Fecha termino: {{$tarea->fechatermino}}</li>
	<li>Porcentaje avance: {{$tarea->avance}} %</li>	
</ul>
@endsection
