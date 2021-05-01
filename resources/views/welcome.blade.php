@extends('layouts.master')
@section('content')

<h2 class="display-4">Bienvenido a CakeTasks</h2>
<hr>
@if(!Auth::check())
  <p>Debe iniciar sesión para utilizar el sistema.</p>
@else
  <div class="form-row">    
    <div class="col-4">
      <h4>Proyectos</h4>
      <p class="lead">  
      @can('gestionar_proyectos')
        <b><a href="/proyectos/">{{$nroProyectos}} proyecto(s)</a></b> en ejecución. 
      @else
        <b><a href="/proyectos/">{{$nroProyectos}} proyecto(s)</a></b> asociado(s) a su cuenta.  
      @endcan
      </p>
      <p class="lead">
      @can('indice_proyectos_archivados')
        <b><a href="/proyectosArchivados/">{{$nroProyectosArch}} proyecto(s)</a></b> en archivo histórico.
      @endcan
      </p>
    </div>
    <div class="col-4">
      @can('gestionar_usuarios')
        <h4>Usuarios</h4>
        <p class="lead">{{$nroUsuarios}} usuario(s) registrado(s) en el sistema.</p>
      @endcan
    </div>
    <div class="col-4">
      @can('crear_informes')
        <h4>Informes</h4>
        <p class="lead">{{$nroInformes}} informe(s) registrado(s) en el sistema.</p>
      @endcan
    </div>
  </div>
  <!-- Preloader -->
  <div id="preloader">
    <div id="status">&nbsp;</div>
  </div>
@endif
@endsection
