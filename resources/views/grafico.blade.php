@extends('layouts.master')
@section('content')
<meta name="_token" content="{!! csrf_token() !!}" />
<div class="row justify-content-between">
    <div class="col-4">
        <h4>{{$proyecto->nombre}} 
            @if(!is_null($proyecto->deleted_at))
                <span class="badge badge-pill badge-warning">Archivado</span>
            @endif
        </h4>
    </div>
    <div class="col-7">
        <table class="table table-sm table-borderless">
            <thead>
                <tr>                    
                    <th>FIR</th>
                    <th>FTR Original</th>
                    <th>FTR Modificada</th>                    
                </tr>
            </thead>
            <tbody>
                <tr>                    
                    <td>{{ $proyecto->fecha_inicio->format('d-M-Y') }}</td>
                    <td>{{ $proyecto->fecha_termino_original->format('d-M-Y') }}</td>
                    <td>
                        @if($proyecto->fecha_termino_original == $proyecto->fecha_termino)
                        -
                        @else
                        {{ $proyecto->fecha_termino->format('d-M-Y')}}
                        @endif
                    </td>                                      
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-1">
        <a type="button" class="btn btn-primary btn-sm float-right" href="{{url()->previous()}}">Atrás 
            <i class="fas fa-arrow-left "></i>
        </a>
    </div>
</div>
<hr>
<div class="row" id="graficoBotones">
    <div id="grafico" class="col-6">
    </div>
    {{-- <div id="zoom" class="col-6 p-1">
        <div class="small">
            
        </div>
    </div> --}}
    <div id="botones" class="col-6">
        <div class="row form-group">
            <div class="col-2">                            
                <div class="text-center">
                    <h3><span id="critica" class="badge badge-pill badge-warning" style="display: none;">Crítica</span></h3>
                </div>
            </div>
            <div class="col-5">
                <p class="m-0 text-center font-weight-bold" style="font-size:15px">AVANCE REAL</p>
                <p class="m-0 text-center text-primary font-weight-bold" style="font-size:15px">{{$proyecto->avance}}%</p>
            </div>
            <div class="col-5">
                <p class="m-0 text-center font-weight-bold" style="font-size:15px">AVANCE PROYECTADO</p>
                <p class="m-0 text-center text-primary font-weight-bold" style="font-size:15px">{{$proyecto->porcentajeAtraso}}%</p>
            </div>            
        </div>
        <div class="row col-12">                            
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
        <div class="row">
            <div class="form-group col-6">
                <label for="opcion">Filtro área:</label>
                <select data-id="{{$proyecto->id}}" class="form-control" id="opcionArea" name="opcionArea">
                    <option selected value="0">Todas</option>
                    @foreach ($areas as $area)
                    <option value="{{$area->id}}">{{$area->nombrearea}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-6">
                <label for="opcion">Filtro color:</label>
                <select data-id="{{$proyecto->id}}" class="form-control" id="opcionColor" name="opcionColor">
                    <option selected value="TODAS">Todas</option>
                    <option value="VERDE">Verde</option>
                    <option value="AMARILLO">Amarillo</option>
                    <option value="NARANJO">Naranjo</option>
                    <option value="ROJO">Rojo</option>
                </select>
            </div>
        </div>
        
        <div class="row">
            <ul class="detallesTarea list-group w-100 mb-1" style="display: none;">
                <li class="list-group-item"><span class="titulospan">Nombre tarea:</span><span id="nombre"><br></span></li>
                <li class="list-group-item"><span class="titulospan">Avance:</span><span id="avance"></span></li>
            </ul>
        </div>
        <div class="row">
            <ul class="detallesTarea list-group w-50" style="display: none;">
                <li class="list-group-item"><span class="titulospan">Área:</span><br><span id="area"></span></li>
                <li class="list-group-item"><span class="titulospan">FIT:</span><span id="fir"></span></li>
                <li class="list-group-item"><span class="titulospan">FTT original:</span><span id="ftro"></span></li>
                <li class="list-group-item"><span class="titulospan">FTT modificada:</span><span id="ftrm"></span></li>
                <li class="list-group-item"><span class="titulospan">Atraso [días]:</span><span id="atraso"></span></li>                
            </ul>
            <ul class="detallesTarea list-group w-50 mb-1" style="display: none;">
                <li class="list-group-item"><p class="titulospan">Observaciones:</p></li>
                <div id="listaObservaciones" style="display: none;">                    
                </div>
            </ul>
        </div>
    </div>
<br>
</div>
<link rel="stylesheet" href="/css/estiloGrafico.css">
<script src="/js/d3.v3.min.js"></script>
<script src="/js/d3-time-format.v2.min.js"></script>
<script src="/js/moment.js"></script>
<script src="/js/dibujarGrafico.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$("#barra").hide();
$("#footer").hide();
dibujarGrafico({!!$tareas!!});
dibujarSimbologia();
});
</script>
@endsection
