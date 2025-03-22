<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Informe - {{ $proyecto->nombre }} - / {{ Carbon::now()->format('d-M-Y - H:i:s') }}</title>

    <link href="{{ url('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ url('css/personal.css') }}" rel="stylesheet">
    <link href="{{ url('css/estiloGrafico.css') }}" rel="stylesheet">

    <head>
        <style>
            thead {
                display: table-header-group
            }

            tfoot {
                display: table-row-group
            }

            tr {
                page-break-inside: avoid
            }

            .page {
                overflow: hidden;
                page-break-after: always;
            }
        </style>
    </head>

<body>
    <div class="page">
        <nav id="barra" class="navbar navbar-expand-md navbar-dark mb-3">
            <div id="logo" class="row">
                <div class="col-6"><img src="{{ url('img/armada.png') }}" width="35px" height="auto"></div>
                <div class="col-6 ml-6 d-flex align-items-end">
                    <h3 class="text-light">CakeTasks</h3>
                </div>
            </div>
        </nav>
        <h3>Informe / {{ Carbon::now()->format('d-M-Y - H:i:s') }}</h3>
        <hr>
        <div class="row" id="graficoBotones">
            <div id="seccion-grafico" class="col-xl-6 col-sm-6">
                @if (array_key_exists('incluye_grafico', $arrayConfiguraciones) && $arrayConfiguraciones['incluye_grafico'] == true)
                    <div id="grafico" style="width: 500px; height: 500px;"></div>
                @endif
            </div>
            <div id="botones" class="col-xl-6 col-sm-6">
                <div class="row w-100">
                    <ul class="detallesTarea list-group w-100">
                        <li class="list-group-item"><b>{{ $proyecto->nombre }}</b></li>
                        <li class="list-group-item"><b>Fecha Inicio Proyecto:</b>
                            {{ $proyecto->fecha_inicio->format('d-M-Y') }}</li>
                        <li class="list-group-item"><b>Fecha Término Proyecto original:</b>
                            {{ $proyecto->fecha_termino_original->format('d-M-Y') }}</li>
                        <li class="list-group-item"><b>Fecha Término Proyecto modificada: </b>
                            @if ($proyecto->fecha_termino_original == $proyecto->fecha_termino)
                                -
                            @else
                                {{ $proyecto->fecha_termino->format('d-M-Y') }}
                            @endif
                        </li>
                        <li class="list-group-item"><b>Atraso [días]: </b>
                            @if ($proyecto->fecha_termino_original->gte($proyecto->fecha_termino))
                                -
                            @else
                                {{ $proyecto->atraso }}
                            @endif
                        </li>
                        <li class="list-group-item"><b>Avance Real [%]: </b>{{ $proyecto->avance }}</li>
                        <li class="list-group-item"><b>Avance Programado [%]: </b>{{ $proyecto->porcentajeAtraso }}
                        </li>
                    </ul>
                </div>
                <hr>
                <div class="row w-100">
                    @if (array_key_exists('incluye_observaciones', $arrayConfiguraciones) &&
                            $arrayConfiguraciones['incluye_observaciones'] == true)
                        <ul class="list-group w-100">
                            @if (count($proyecto->observaciones) > 0)
                                @foreach ($proyecto->observaciones()->get() as $observacion)
                                    <div class="list-group-item flex-column align-items-start">
                                        <p class="card-text">{{ $observacion->contenido }}</p>
                                        <small>{{ $observacion->created_at->format('d-M-Y H:m') }} -
                                            @if ($observacion->autor == null)
                                                Sin autor
                                            @else
                                                {{ $observacion->autor[0] }}
                                            @endif
                                        </small>
                                    </div>
                                @endforeach
                            @else
                                <div class="list-group-item ">
                                    <p class="mb-1">No hay datos.</p>
                                </div>
                            @endif
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="page">
        <div class="row">
            <div class="col-10">
                <h4><b>Lista Tareas</b> - Total: {{ count($proyecto->tareas) }}</h4>
            </div>
        </div>
        <table id="tablaTareas" class="table table-hover mt-2">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>NOMBRE<br>TAREA</th>
                    <th>Fecha Inicio Tarea<br>&nbsp;</th>
                    <th>Fecha Término Tarea<br>Original</th>
                    <th>Fecha Término Tarea<br>Modificada</th>
                    <th>ATRASO<br>[días]</th>
                    <th class="text-center">AVANCE<br>REAL<br>[%]</th>
                    <th class="text-center">AVANCE<br>PROGRAMADO<br>[%]</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tareas as $llave => $tarea)
                    <tr id="{{ $tarea->id }}">
                        <td>{{ $llave + 1 }}</td>
                        @if ($tarea->colorAtraso == $propiedades[0]->color)
                            <td style="background-color: {{ $propiedades[0]->color }};">
                            @elseif($tarea->colorAtraso == $propiedades[1]->color)
                            <td style="background-color: {{ $propiedades[1]->color }};">
                            @elseif($tarea->colorAtraso == $propiedades[2]->color)
                            <td style="background-color: {{ $propiedades[2]->color }};">
                            @elseif($tarea->colorAtraso == $propiedades[3]->color)
                            <td style="background-color: {{ $propiedades[3]->color }};">
                        @endif
                        <a class="text-dark">{{ $tarea->nombre }}</a>
                        @if ($tarea->critica)
                            <span class="badge badge-pill badge-warning">Crítica</span>
                        @endif
                        </td>
                        <td style="width: 16%">{{ $tarea->fecha_inicio->format('d-M-Y') }}</td>
                        <td style="width: 16%">{{ $tarea->fecha_termino_original->format('d-M-Y') }}</td>
                        <td style="width: 16%">
                            @if ($tarea->fecha_termino_original == $tarea->fecha_termino)
                                -
                            @else
                                {{ $tarea->fecha_termino->format('d-M-Y') }}
                            @endif
                        </td>
                        <td>
                            @if ($tarea->atraso == 0)
                                -
                            @else
                                {{ $tarea->atraso }}
                            @endif
                        </td>
                        <td>{{ $tarea->avance }}</td>
                        <td>{{ $tarea->porcentajeAtraso }}</td>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="{{ url('js/jquery-3.3.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('js/d3.v3.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('js/d3-time-format.v2.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('js/moment.js') }}" type="text/javascript"></script>
    <script src="{{ url('js/jquery.multiselect.js') }}" type="text/javascript"></script>
    <script src="{{ url('js/dibujarGrafico.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            dibujarGrafico({!! $tareasJSON !!});
        });
    </script>
</body>

</html>
