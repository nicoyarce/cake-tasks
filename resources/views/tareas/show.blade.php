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
                @if($tarea->colorAtraso == "VERDE" || $tarea->avance == 100)
                    <td class="bg-success"><a class="text-dark" href="{{action('TareasController@show', $tarea['id'])}}">{{$tarea->nombre}}</a>
                @elseif($tarea->colorAtraso == "AMARILLO")
                    <td class="fondo-amarillo"><a class="text-dark" href="{{action('TareasController@show', $tarea['id'])}}">{{$tarea->nombre}}</a>
                @elseif($tarea->colorAtraso == "NARANJO")
                    <td class="fondo-naranjo"><a class="text-dark" href="{{action('TareasController@show', $tarea['id'])}}">{{$tarea->nombre}}</a>
                @elseif($tarea->colorAtraso == "ROJO")
                    <td class="bg-danger"><a class="text-dark" href="{{action('TareasController@show', $tarea['id'])}}">{{$tarea->nombre}}</a>
                @endif
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
                        @if(empty($tarea->autorUltimoCambioFtt))
                            {{ $tarea->fecha_termino->format('d-M-Y')}}
                        @else
                            <a data-toggle="tooltip" data-placement="bottom" data-html="true" 
                                title="Modificado por: {{$tarea->autorUltimoCambioFtt->nombre}} <br> Fecha: <br> {{$tarea->fecha_ultimo_cambio_ftt->format('d-M-Y H:i:s')}}">
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
                    @if(empty($tarea->autorUltimoCambioAvance))
                        {{$tarea->avance}}
                    @else
                        <a data-toggle="tooltip" data-placement="bottom" data-html="true" 
                        title="Autor ultimo cambio: {{$tarea->autorUltimoCambioAvance->nombre}} <br> Fecha ultimo cambio: <br> {{$tarea->fecha_ultimo_cambio_avance->format('d-M-Y H:i:s')}}">
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
    <div class="form-group col-6 pt-5">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Área</h5>    
            <p class="card-text">{{$tarea->area->nombrearea}}</p>
          </div>
        </div>
    </div>
    <div class="form-group col-6">
        <h4>Observaciones</h4>
        <div class="list-group">        
            @if(count($tarea->observaciones)>0)
                @foreach ($tarea->observaciones()->get() as $observaciones)
                <div class="list-group-item flex-column align-items-start">
                    <p class="mb-1">{{$observaciones->contenido}}</p>
                    <small>{{$observaciones->created_at->format('d-M-Y H:m')}} - 
                        @if($observaciones->autor == null)
                        Sin autor
                        @else
                        {{$observaciones->autor->nombre}}
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
@endsection
