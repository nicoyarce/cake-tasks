<head>
    <script src="{{public_path('js/jquery-3.3.1.min.js')}}"></script>
    <link href="{{public_path('css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{public_path('css/personal.css')}}" rel="stylesheet">    
        
    {{-- <script src="/js/jquery-3.3.1.min.js"></script>
    <link href="/css/bootstrap.css" rel="stylesheet">
    <link href="/css/personal.css" rel="stylesheet"> --}}
    <style>
        thead { display: table-header-group }
        tfoot { display: table-row-group }
        tr { page-break-inside: avoid }        
    </style>
</head>

<nav id="barra" class="navbar navbar-expand-md navbar-dark">    
    <div id="logo">
        {{-- <img src="{{public_path('armada.png')}}" width="35px" height="auto"> --}}
        <img src="/armada.png" width="35px" height="auto">
    </div>        
    <h2 class="text-light text-center" align="center">Holistic</h2>
</nav>
<h3 align="center">Informe - {{Date::now()}}</h3>
<hr>
<div class="row" id="graficoBotones">        
    <div id="zoom" class="col-6 p-1">
        <div class="small">
            <div id="grafico"></div>
        </div>        
    </div>
    <div id="botones" class="col-6">
        <div class="row form-group">
            <div class="col-3">                
                <p class="m-0 text-center font-weight-bold">PORCENTAJE AVANCE PROYECTO</p>                               
                <p class="m-0 text-center text-primary font-weight-bold" style="font-size:30px">{{$proyecto->avance}}</p>                             
            </div>                   
        </div>
    </div>        
</div>
<table class="table table-hover">
    <thead>
        <tr>
            <th>NOMBRE<br>PROYECTO</th>
            <th>FIR<br>&nbsp;</th>
            <th>FTR<br>Original</th>
            <th>FTR<br>Modificada</th>
            <th>ATRASO<br>[días]</th>
            <th>AVANCE<br>[%]</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><b>{{ $proyecto->nombre }}</b></td>
            <td>{{ $proyecto->fecha_inicio->format('d-M-Y') }}</td>
            <td>{{ $proyecto->fecha_termino_original->format('d-M-Y') }}</td>
            <td>
                @if($proyecto->fecha_termino_original == $proyecto->fecha_termino)
                -
                @else
                {{ $proyecto->fecha_termino->format('d-M-Y')}}
                @endif
            </td>
            <td>
                @if($proyecto->fecha_termino_original->gte($proyecto->fecha_termino))
                -
                @else
                {{$proyecto->atraso}}
                @endif
            </td>
            <td>{{$proyecto->avance}}</td>
        </tr>
    </tbody>
</table>
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
            <td style="width: 15%">{{ $tarea->fecha_inicio->format('d-M-Y')}}</td>
            <td style="width: 15%">{{ $tarea->fecha_termino_original->format('d-M-Y') }}</td>
            <td style="width: 15%">
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
