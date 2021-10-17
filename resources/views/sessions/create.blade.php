@extends('layouts.master')
@section('content')
@include('layouts.errors')

<link href="/css/signin.css" rel="stylesheet">
<form method="POST" action="{{action('SessionsController@store')}}"class="form-signin">
    {{csrf_field()}}

    <div class="card" style="width: 18rem;">
        <img src="{{url('/img/holistic.png')}}" class="card-img-top" alt="Logo Holistic" width="35px" height="auto">
        <div class="card-body">
            <h5 class="card-title">Iniciar sesión</h5>
            <label for="run" class="sr-only">RUN</label>
            <input type="text" id="run" name="run" class="form-control" placeholder="RUN" required autofocus>
            <label for="password" class="sr-only">Contraseña</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Iniciar sesión</button>
        </div>
    </div>
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
