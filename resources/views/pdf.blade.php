<head>
    <script src="{{public_path('js/jquery-3.3.1.min.js')}}"></script>
    <link href="{{public_path('css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{public_path('css/personal.css')}}" rel="stylesheet">    
    <script> 

Function.prototype.bind = Function.prototype.bind || function (thisp) {
    var fn = this;
    return function () {
        return fn.apply(thisp, arguments);
    };
;</script>    
    {{-- <script src="/js/jquery-3.3.1.min.js"></script>
    <link href="/css/bootstrap.css" rel="stylesheet">
    <link href="/css/personal.css" rel="stylesheet"> --}}
    <style>
        thead { display: table-header-group }
        tfoot { display: table-row-group }
        tr { page-break-inside: avoid }        
    </style>
</head>

<nav id="barra" class="navbar navbar-expand-md navbar-dark mb-3">    
    <div id="logo" class="row">
        <div class="col-1"><img src="{{public_path('armada.png')}}" width="35px" height="auto"></div>
        <!-- <img src="/armada.png" width="35px" height="auto"> -->
        <div class="col-1 ml-3 d-flex align-items-end"><h3 class="text-light">Holistic</h3></div>
    </div>
</nav>
<h3>Informe / {{Date::now()->format('d-M-Y - H:i:s')}}</h3>
<hr>
<div class="row" id="graficoBotones">        
    <div id="zoom" class="col-6 p-1 ml-3 pl-3">
        <div class="small">
            <div id="grafico" style="width: 500px; height: 500px;"></div>
        </div>        
    </div>
    <div id="botones" class="col-6">
        <div class="row form-group d-flex justify-content-end mr-3">            
            <ul class="detallesTarea list-group ">
                <li class="list-group-item"><b>{{ $proyecto->nombre }}</b></li>
                <li class="list-group-item"><b>FIT:</b> {{ $proyecto->fecha_inicio->format('d-M-Y') }}</li>
                <li class="list-group-item"><b>FTT original:</b> {{ $proyecto->fecha_termino_original->format('d-M-Y') }}</li>
                <li class="list-group-item"><b>FTT modificada: </b>
                    @if($proyecto->fecha_termino_original == $proyecto->fecha_termino)
                        -
                    @else
                        {{ $proyecto->fecha_termino->format('d-M-Y')}}
                    @endif
                </li>
                <li class="list-group-item"><b>Atraso [días]: </b>
                    @if($proyecto->fecha_termino_original->gte($proyecto->fecha_termino))
                        -
                    @else
                        {{$proyecto->atraso}}
                    @endif
                </li>
                <li class="list-group-item"><b>Avance [%]: </b>{{$proyecto->avance}}</li>                
            </ul>                   
        </div>
    </div>        
</div>
<br>
<div class="row">
    <div class="col-10">
        <h4><b>Lista Tareas</b> - Total: {{count($proyecto->tareas)}}</h4>
    </div>
</div>
<table id="tablaTareas" class="table table-hover mt-2">
    <thead class="thead-light">
        <tr>
            <th>NOMBRE<br>TAREA</th>
            <th>FIT<br>&nbsp;</th>
            <th>FTT<br>Original</th>
            <th>FTT<br>Modificada</th>
            <th>ATRASO<br>[días]</th>
            <th>AVANCE<br>[%]</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tareas as $tarea)
        <tr id="{{$tarea->id}}">
            @if($tarea->colorAtraso == "VERDE" || $tarea->avance == 100)
            <td class="bg-success"><a class="text-dark" >{{$tarea->nombre}}</a></td>
            @elseif($tarea->colorAtraso == "AMARILLO")
            <td class="fondo-amarillo"><a class="text-dark" >{{$tarea->nombre}}</a></td>
            @elseif($tarea->colorAtraso == "NARANJO")
            <td class="fondo-naranjo"><a class="text-dark" >{{$tarea->nombre}}</a></td>
            @elseif($tarea->colorAtraso == "ROJO")
            <td class="bg-danger"><a class="text-dark" >{{$tarea->nombre}}</a></td>
            @endif
            <td style="width: 16%">{{ $tarea->fecha_inicio->format('d-M-Y')}}</td>
            <td style="width: 16%">{{ $tarea->fecha_termino_original->format('d-M-Y') }}</td>
            <td style="width: 16%">
                @if($tarea->fecha_termino_original == $tarea->fecha_termino)
                -
                @else
                {{ $tarea->fecha_termino->format('d-M-Y')}}
                @endif
            </td>
            <td>
                @if($tarea->atraso==0)
                -
                @else
                {{$tarea->atraso}}
                @endif
            </td>
            <td>{{$tarea->avance}}</td>
        </td>
    </tr>
    @endforeach
</tbody>
</table>

<link href="{{public_path('css/estiloGrafico.css')}}" rel="stylesheet" >
<script src="{{public_path('js/d3.v3.min.js')}}"></script>
<script src="{{public_path('js/d3-time-format.v2.min.js')}}"></script>
<script src="{{public_path('js/moment.js')}}"></script>
<script src="{{public_path('js/dibujarGrafico.js')}}"></script>

{{-- <link href="/css/estiloGrafico.css" rel="stylesheet" >    
<script src="/js/d3.v3.min.js"></script>
<script src="/js/d3-time-format.v2.min.js"></script>
<script src="/js/moment.js"></script>
<script src="/js/dibujarGrafico.js"></script> --}}
<script type="text/javascript">    
    $(document).ready(function(){        
        dibujarGrafico({!!$tareasJSON!!});              
    });
</script>
