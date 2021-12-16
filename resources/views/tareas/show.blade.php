@extends('layouts.master')
@section('content')
@include('layouts.errors')

<div class="row justify-content-between">
    <h2 class="col-11">{{$tarea->nombre}}
        @if($tarea->critica)
            <span class="badge badge-pill badge-warning">Crítica</span>
        @endif
    </h2>
    <div class="col-1">
        <a type="button" class="btn btn-primary float-right" href="{{url()->previous()}}">Atrás <i class="fas fa-arrow-left "></i></a>
    </div>
</div>
<hr>
<div class="row">
    <table id="tablaTareas" class="table table-hover mt-2">
        <thead class="thead-light" style="position: sticky;">
            <tr>
                <th>NOMBRE<br>TAREA</th>
                <th>FIT<br>&nbsp;</th>
                <th>FTT<br>Original</th>
                <th>FTT<br>Modificada</th>
                <th>ATRASO<br>[días]</th>
                <th class="text-center">AVANCE<br>REAL<br>[%]</th>
                <th class="text-center">AVANCE<br>PROGRAMADO<br>[%]</th>
                <th>VER EN CARTA GANTT</th>
            </tr>
        </thead>
        <tbody>
            <tr id="{{$tarea->id}}">
                @if($tarea->colorAtraso == $propiedades[0]->color || $tarea->avance == 100)
                <td style="background-color: {{$propiedades[0]->color}};">
                    @elseif($tarea->colorAtraso == $propiedades[1]->color)
                <td style="background-color: {{$propiedades[1]->color}};">
                    @elseif($tarea->colorAtraso == $propiedades[2]->color)
                <td style="background-color: {{$propiedades[2]->color}};">
                    @elseif($tarea->colorAtraso == $propiedades[3]->color)
                <td style="background-color: {{$propiedades[3]->color}};">
                    @endif
                    <a class="text-dark" href="#">{{$tarea->nombre}}</a>
                    @if($tarea->critica)
                    <span class="badge badge-pill badge-warning">Crítica</span>
                    @endif
                </td>
                <td style="width: 12%">{{ $tarea->fecha_inicio->format('d-M-Y')}}</td>
                <td style="width: 12%">{{ $tarea->fecha_termino_original->format('d-M-Y') }}</td>
                <td style="width: 12%">
                    @if($tarea->fecha_termino_original == $tarea->fecha_termino)
                    -
                    @else						
                        @if(empty($tarea->autorUltimoCambioFtt()->withTrashed()->first()))
                            {{ $tarea->fecha_termino->format('d-M-Y')}}
                        @else
                            <a data-toggle="tooltip" data-placement="bottom" data-html="true"
                                title="Modificado por: {{array_get($tarea->autorUltimoCambioFtt()->withTrashed()->first(), 'nombre')}} <br>
                                Fecha: <br>
                                {{$tarea->fecha_ultimo_cambio_ftt->format('d-M-Y H:i:s')}}">
                                {{ $tarea->fecha_termino->format('d-M-Y')}}
                            </a>
                        @endif
                    @endif
                </td>
                <td>
                    @if($tarea->atraso==0)
                    -
                    @else
                    {{$tarea->atraso}}
                    @endif
                </td>
                <td>
                    @if(empty($tarea->autorUltimoCambioAvance()->withTrashed()->first()))
                        {{$tarea->avance}}
                    @else
                        <a data-toggle="tooltip" data-placement="bottom" data-html="true"
                        title="Autor ultimo cambio: {{array_get($tarea->autorUltimoCambioAvance()->withTrashed()->first(), 'nombre')}} <br> Fecha ultimo cambio: <br> {{$tarea->fecha_ultimo_cambio_avance->format('d-M-Y H:i:s')}}">
                            {{$tarea->avance}}
                        </a>
                    @endif
                </td>
                <td>{{$tarea->porcentajeAtraso}}</td>
                <td>
                    <a href="{{action('TareasController@cargarVisor', ['tareaid' => $tarea->id])}} "type="button" class="btn btn-primary" target="_blank">
                    <i class="fas fa-eye"></i></a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="row">
    <div class="form-group col-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Tipo Tarea</h5>
            <p class="card-text">{{$tarea->tipoTarea->descripcion}}</p>
          </div>
        </div>
    </div>
    <div class="form-group col-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Área</h5>
            <p class="card-text">{{$tarea->area->nombrearea}}</p>
          </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Observaciones</h5>
                <div class="list-group">
                    @if(count($tarea->observaciones)>0)
                        @foreach ($tarea->observaciones()->get() as $observacion)
                            <div class="list-group-item flex-column align-items-start">
                                <p class="card-text">{{$observacion->contenido}}</p>
                            <small>{{$observacion->created_at->format('d-M-Y H:m')}} -
                            @if($observacion->autor == null)
                                Sin autor
                            @else
                                {{array_get($observacion->autor()->withTrashed()->first(), 'nombre')}}
                            @endif
                            </small>
                            </div>
                        @endforeach
                    @else
                        <div class="list-group-item ">
                            <p class="mb-1">No hay datos.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="form-group col-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Tareas Hijas</h5>
                <div class="list-group">
                    @if(count($tarea->tareasHijas)>0)
                        @foreach ($tarea->tareasHijas()->get() as $tareahija)
                            <div class="list-group-item flex-column align-items-start">
                                <p class="card-text">{{$tareahija->nombre}}</p>
                            <small>{{$tareahija->fecha_inicio->format('d-M-Y')}} - {{$tareahija->fecha_termino->format('d-M-Y')}}</small>
                            </div>
                        @endforeach
                    @else
                        <div class="list-group-item ">
                            <p class="mb-1">No hay datos.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
