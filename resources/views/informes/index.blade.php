@extends('layouts.master')
@section('content')
@include('layouts.errors')
<div class="row justify-content-between">
    <div class="col-10">
        <h1>{{$proyecto->nombre}}</h1>
    </div>
    <div class="col-2 d-flex align-items-center">
        <a type="button" class="btn btn-success mx-auto" href="{{action('InformesController@generarInformeManual', $proyecto['id'])}}" role="button">Generar informe
            <i class="fas fa-plus"></i>
        </a>
    </div>     
</div>
<hr>
<div class="row justify-content-between">
    <div class="col-12">
        <h2>Informes</h2>
    </div>   
</div>
@if(count($proyecto->informes)>0)
<table class="table table-hover">
    <thead>
        <tr>            
            <th>Fecha</th>
            <th>Hora</th>
            <th>Ver</th>
            <th>Borrar</th>
        </tr>
    </thead>
    
    <tbody>
        @foreach ($proyecto->informes as $informe)
        <tr>            
            <td>{{$informe->created_at->format('d-M-Y')}}</td> 
            <td>{{$informe->created_at->format('H:i:s')}}</td>         
            <td> 
                <a href="{{Storage::url($informe->ruta)}}" type="button" class="btn btn-primary" >
                    <i class="fas fa-eye "></i>
                </a>
            </td>
            <td>                
                <form method="POST" action="/informes/destroy/{{$informe->id}}">
                    {{csrf_field()}}
                    {{method_field('DELETE')}}
                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Desea eliminar el informe?')"><i class="fas fa-trash-alt"></i></button>
                </form>             
            </td>            
        </tr>
        @endforeach
    </tbody>
</table>
@else
<h3 class="text-center">No hay informes</h3>
@endif
@endsection
