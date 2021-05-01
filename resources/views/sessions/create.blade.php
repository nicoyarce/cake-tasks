@extends('layouts.master')
@section('content')
@include('layouts.errors')

<link href="/css/signin.css" rel="stylesheet">
<form method="POST" action="{{action('SessionsController@store')}}"class="form-signin">
    {{csrf_field()}}
    <h1 class="h3 mb-3 font-weight-normal">Iniciar sesión</h1>
    
    <label for="run" class="sr-only">RUN</label>
    <input type="text" id="run" name="run" class="form-control" placeholder="RUN" required autofocus>

    <label for="password" class="sr-only">Contraseña</label>
    <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required>
    
    <button class="btn btn-lg btn-primary btn-block" type="submit">Iniciar sesión</button>
</form>
<script src="/js/jquery-3.3.1.min.js"></script>
<script src="/js/jquery.rut.min.js"></script>
<script>
$(function() {
    $("#run").rut().on('rutValido', function(e, rut, dv) {
        alert("El run " + rut + "-" + dv + " es correcto");
    }, { minimumLength: 7} );
})
</script>
@endsection
