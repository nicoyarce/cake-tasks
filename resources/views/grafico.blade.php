@extends('layouts.master')
@section('content')
@section('tituloPagina', 'Gráfico - ' . $proyecto->nombre)
<input type="hidden" name="_token" value="{{ csrf_token() }}" />
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
    <div class="col-6">
        <div id="grafico"></div>
        <div class="col-12">
            <p class="m-0 text-center font-weight-bold" style="font-size:15px">PROMEDIO TAREAS FILTRADAS</p>
            <p class="m-0 text-center text-primary font-weight-bold" style="font-size:15px"><span id="promedio_avances_tareas">{{$promedio_avances_tareas}}</span>%</p>
        </div>            
    </div>
    {{-- <div id="zoom" class="col-6 p-1">
        <div class="small">

        </div>
    </div> --}}
    <div id="botones" class="col-6">
        <div class="row text-center">            
            <div class="col-4">
                <a type="button" class="btn btn-primary btn-sm" id="maximizar">
                    <span style="color: white;">
                        <i id="icono_maximizar" class="fas fa-expand-arrows-alt"></i>
                    </span>
                </a>
            </div>
            <div class="col-4">
                <p class="m-0 text-center font-weight-bold" style="font-size:15px">AVANCE REAL</p>
                <p class="m-0 text-center text-primary font-weight-bold" style="font-size:15px">{{$proyecto->avance}}%</p>
            </div>
            <div class="col-4">
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
            <input type="hidden" id="proyecto_id" name="proyecto_id" value="{{$proyecto->id}}">
            <div class="form-group col-6">
                <label for="filtro_area">Filtro área:</label>
                <select multiple class="form-control" id="filtro_area" name="filtro_area">
                    @foreach ($areas as $area)
                        <option value="{{$area->id}}">{{$area->nombrearea}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-6">
                <label for="filtro_color">Filtro color:</label>
                <select multiple class="form-control" id="filtro_color" name="filtro_color">
                    <option value="{{$propiedades[0]->color}}">Verde</option>
                    <option value="{{$propiedades[1]->color}}">Amarillo</option>
                    <option value="{{$propiedades[2]->color}}">Naranjo</option>
                    <option value="{{$propiedades[3]->color}}">Rojo</option>
                </select>
            </div>
            <div class="form-group col-6">
                <label for="filtro_categoria">Filtro tipo proyecto:</label>
                <select multiple class="form-control" id="filtro_categoria" name="filtro_categoria">
                    @foreach ($categorias as $categoria)
                        <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
                    @endforeach
                </select>
            </div> 
            <div class="form-group col-6">
                <label for="filtro_trabajo">Filtro trabajos ASMAR/propios:</label>
                <select class="form-control" id="filtro_trabajo" name="filtro_trabajo">
                    <option value="">Todos</option>
                    <option value="0">ASMAR</option>
                    <option value="1">Propios</option>                    
                </select>
            </div>            
        </div>
        <div class="row">
            <div class="w-50">
                <h3 class="text-center"><span id="critica" class="badge badge-pill badge-warning" style="display: none;">Crítica</span></h3>                
            </div>
            <div class="w-50">
                <h3 class="text-center"><span id="trabajo_interno" class="badge badge-pill badge-info" style="display: none;">Trabajo Propio</span></h3>
                <h3 class="text-center"><span id="trabajo_externo" class="badge badge-pill badge-primary" style="display: none;">Trabajo ASMAR</span></h3>  
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
    function cambioFiltro() {
        const proyecto_id = $("#proyecto_id").val();
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            method: "POST", // Type of response and matches what we said in the route
            dataType: "json", //tipo de respuesta esperada
            url: "/grafico/" + proyecto_id + "/filtrar",
            data: {
                proyecto_id,
                filtro_area: JSON.stringify($("#filtro_area").val()),
                filtro_color: JSON.stringify($("#filtro_color").val()),
                filtro_categoria: JSON.stringify($("#filtro_categoria").val()),
                filtro_trabajo: JSON.stringify($("#filtro_trabajo").val()),
            },
            success: function (response) {
                // What to do if we succeed
                $("#detallesTarea").hide();
                d3.selectAll("svg.grafico").remove();
                dibujarGrafico(response.tareas);
                $("#promedio_avances_tareas").html(
                    response.promedio_avances_tareas
                );
                //habilitarZoom();
            },
            error: function (jqXHR, textStatus, errorThrown, exception) {
                // What to do if we fail
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + " : " + errorThrown);
            },
        });
    }
    $(document).ready(function(){
        iniciarMultiSelect();
        $("#maximizar").on('click', function() {
            if ($("#main").hasClass("container")) {
                $("#main").removeClass("container").addClass("container-fluid");
                $("#barra").hide();
                $("#icono_maximizar").removeClass('fa-expand-arrows-alt').addClass('fa-compress-arrows-alt');
            } else {
                $("#main").removeClass("container-fluid").addClass("container");
                $("#barra").show();
                $("#icono_maximizar").removeClass('fa-compress-arrows-alt').addClass('fa-expand-arrows-alt');
            }
        });
        $("select[multiple], #filtro_trabajo").on("change", function () {
            cambioFiltro();
        });
        dibujarSimbologia({!!json_encode($propiedades)!!});
        dibujarGrafico({!!$tareas!!});
    });
</script>
@endsection
