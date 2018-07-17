@extends('layouts.master')
@section('content')
<h1>Tarea: {{$tarea->nombre}}</h1>
<ul>
    <li>Area: {{$tarea->area}}</li>
    <li>Pertenece a: {{$tarea->proyecto->nombre}}</li>
	<li>Fecha inicio: {{$tarea->fecha_inicio}}</li>
	<li>Fecha termino: {{$tarea->fecha_termino}}</li>
	<li>Porcentaje avance: {{$tarea->avance}} %</li>	
</ul>
@endsection
