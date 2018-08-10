@extends('layouts.master')
@section('content')

<h1>Bienvenido</h1>
<hr>
@if(!Auth::check())
<p>Debe iniciar sesión para utilizar el sistema.</p>
@endif
@role('Administrador')
<p>Existe(n)<b><a href="/proyectos/"> {{$nroProyectos}} proyectos</a></b> registrados en el sistema.</p>
@endrole
@hasanyrole('OCR|Usuario')
<p>Tiene<b><a href="/proyectos/"> {{$nroProyectos}} proyecto(s)</a></b> asociados a su cuenta.</p>
@endhasanyrole

@endsection
