@extends('layouts.master')
@section('content')
 <div class="row justify-content-between">
        <div class="col-11">
            <h1>Holistic</h1><h2>v2.5</h2>
        </div>        
        <div class="col-1 d-flex align-items-center">        
            <a type="button" class="btn btn-primary float-right" href="{{url()->previous()}}">Atrás <i class="fas fa-arrow-left "></i></a>        
        </div>     
    </div>
    <hr>
    <div class="row justify-content-between">
        <div class="col-12">
            <h3>Desarrollado por:</h3>
            <h4>Nicolas Oyarce Aburto</h4>
            <h5>Contacto: nicoyarce&commat;gmail.com</h5>
            <h5><a href="mailto:nicoyarce@gmail.com?Subject=Holistic" target="_top">Enviar mail</a></h5>
            <h5>Se sugiere utilizar una resolución de pantalla superior a 1024 x 768 para visualizar el sitio en forma óptima</h5>
        </div>
    </div>
@endsection
