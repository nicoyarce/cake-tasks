{{-- Propiedad con id = 6 es el porcentaje para que color de grafico pase a verde, se maneja directamente con la id --}}
@extends('layouts.master')
@section('content')
@section('tituloPagina', 'Propiedades Gráfico')
<input type="hidden" name="_token" value="{{ csrf_token() }}" />
<div class="row">
    <div class="col-12">
        <h1>Propiedades gráfico</h1>
    </div>    
</div>
@include('layouts.errors')
@if(count($propiedades)>0)
<div class="row offset-3">                            
    <svg id="simbologia" class="w-100">
        <defs>
            <marker
            id="arrow"
            markerUnits="strokeWidth"
            markerWidth="12"
            markerHeight="12"
            viewBox="0 0 12 12"
            refX="6"
            refY="6"
            orient="auto">
            <path d="M2,2 L10,6 L2,10 L6,6 L2,2" style="fill: #000;"></path>
            </marker>
        </defs>
    </svg>                              
</div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Avance Fecha</th>
                <th>Color / Número</th>
                <th>Editar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($propiedades as $propiedad)
            <tr style="line-height: 50px; min-height: 50px; height: 50px;" id="{{$propiedad->id}}">
                <td>{{$propiedad->nombre}}</td>
                <td>
                    @if ($propiedad->avance == -1)
                    Color del porcentaje de avance de la tarea en el grafico
                    @elseif($propiedad->id == 6)
                    Bajo que porcentaje de avance de tarea en adelante se cambiara a color verde
                    @else
                    Si la fecha actual es mayor o igual al {{$propiedad->avance}}% del tiempo de ejecucion
                    @endif
                </td>
                <td>
                    @if($propiedad->id == 6)
                    {{$propiedad->avance}}                  
                    @else
                    <input style="height:50px" disabled="" type="color" class="form-control" value="{{$propiedad->color}}">
                    @endif
                </td>                 
                <td>                    
                    <a href="{{action('PropiedadesGraficoController@edit', $propiedad['id'])}}" type="button" class="btn btn-primary">
                    <i class="fas fa-edit"></i>
                    </a>                   
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <hr>
        <h3 class="text-center">No hay propiedades</h3>
    @endif
<script src="/js/d3.v3.min.js"></script>
<script src="/js/d3-time-format.v2.min.js"></script>
<script src="/js/dibujarGrafico.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    var svgSimbologia = d3.select('#simbologia')
    .attr('viewBox', "0,0,200,45");
    dibujarSimbologia();
}); 
</script>  
@endsection

{{-- Modal --}}
@if(empty($editar))
    {{-- Crear propiedad --}}
    @section('modal-title')
    Crear propiedad
    @endsection
    @section('modal-content')
    @include('layouts.errors')
    @if(isset($abrir_modal))    
        <script type="text/javascript">
            $(document).ready(function(){
                $('#exampleModal').modal('toggle')
            }); 
        </script>
    @endif
    @endsection
@else
    {{-- Modificar propiedad --}}
    @section('modal-title')
    Modificar propiedad
    @endsection
    @section('modal-content')
    @include('layouts.errors')
    <form class="form-horizontal" method="POST" action="{{action('PropiedadesGraficoController@update', $editar->id)}}">
        {{csrf_field()}}
        {{method_field('PUT')}}
        <div class="form-group">
            <label for="nombre">Nombre</label>       
            <input type="text" class="form-control" id="nombre" required name="nombre" value="{{$editar->nombre}}">        
        </div>
        
        @if($editar->id != 5)
        <div class="form-group">
            <label for="nombre">Avance</label>
            @if($editar->id == 6)  
                <input type="text" class="form-control" id="avance"  @if($editar->id != 6)readonly="" disabled=""@endif name="avance" value="{{$editar->avance}}">
            @else
                <h4>{{$editar->avance}}</h4>
            @endif
        </div> 
        @endif
        
        @if($editar->id != 6)
        <div class="form-group">
            <label for="nombre">Color</label>       
            <input style="height:50px" type="color" class="form-control" id="color" required name="color" value="{{$editar->color}}">
        </div>       
        @endif
        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#exampleModal').modal('toggle');        
        }); 
    </script>
    @endsection
@endif
