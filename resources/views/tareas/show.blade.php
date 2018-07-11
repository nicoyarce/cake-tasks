@extends('layout')
@section('content')
<h1>Tarea</h1>
<ul>
	<li>Nombre: {{$tarea->nombre}}</li>
	<li>Fecha inicio: {{$tarea->fechainicio}}</li>
	<li>Fecha termino: {{$tarea->fechatermino}}</li>
	<li>Porcentaje avance: {{$tarea->avance}}</li>	
</ul>
@endsection