@extends('layouts.master')
@section('content')
@include('layouts.errors')
@if(is_null($proyecto->deleted_at))
    {{-- Para informes normales --}}
    <div class="row justify-content-between">
        <div class="col-9">
            <h1>{{$proyecto->nombre}}</h1>
        </div>
        <div class="col-2 d-flex align-items-center">
            @can('borrar_informes')
            <a type="button" class="btn btn-success mx-auto" data-toggle="modal" data-target="#exampleModal" href="" role="button">Generar informe
                <i class="fas fa-plus"></i>
            </a>
            @endcan
        </div>
        <div class="col-1 d-flex align-items-center">
            <a type="button" class="btn btn-primary float-right" href="{{url()->previous()}}">Atrás <i class="fas fa-arrow-left "></i></a>
        </div>
    </div>
    <hr>
    <div class="row justify-content-between">
        <div class="col-12">
            <h2>Informes</h2>
        </div>
    </div>
    @if(count($informes)>0)
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Ver</th>
                    @can('borrar_informes')
                    <th>Borrar</th>
                    @endcan
                </tr>
            </thead>

            <tbody>
                @foreach ($informes as $informe)
                <tr>
                    <td>{{$informe->fecha->format('d-M-Y')}}</td>
                    <td>{{$informe->created_at->format('H:i:s')}}</td>
                    <td>
                        <a href="{{Storage::url($informe->ruta)}}" type="button" class="btn btn-primary" >
                            <i class="fas fa-eye "></i>
                        </a>
                    </td>
                    @can('borrar_informes')
                    <td>
                        <form method="POST" action="/informes/destroy/{{$informe->id}}">
                            {{csrf_field()}}
                            {{method_field('DELETE')}}
                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Desea eliminar el informe?')"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </td>
                    @endcan
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <hr>
        <h3 class="text-center">No hay informes</h3>
    @endif
@else
    {{-- Para informes archivados --}}
    <div class="row justify-content-between">
        <div class="col-11">
            <h1>{{$proyecto->nombre}}</h1>
            <span class="badge badge-pill badge-warning">Archivado</span>
        </div>
        <div class="col-1 d-flex align-items-center">
            <a type="button" class="btn btn-primary float-right" href="{{url()->previous()}}">Atrás <i class="fas fa-arrow-left "></i></a>
        </div>
    </div>
    <hr>
    <div class="row justify-content-between">
        <div class="col-12">
            <h2>Informes</h2>
        </div>
    </div>
    @if(count($proyecto->informes()->withTrashed()->get())>0)
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Ver</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($informes as $informe)
                <tr>            
                    <td>{{$informe->fecha->format('d-M-Y')}}</td> 
                    <td>{{$informe->created_at->format('H:i:s')}}</td>         
                    <td> 
                        <a href="{{Storage::url($informe->ruta)}}" type="button" class="btn btn-primary" target="_blank">
                            <i class="fas fa-eye "></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <hr>
        <h3 class="text-center">No hay informes</h3>
    @endif
@endif
@endsection
@section('modal-title')
Generar Informe
@endsection
@section('modal-content')
@include('layouts.errors')
<div class="form-group text-center">
    <a type="button" class="btn btn-success mx-auto"
        href="{{action('InformesController@generarInformeManual', $proyecto['id'])}}" role="button">Generar informe
        completo
        <i class="fas fa-file-alt"></i>
    </a>
</div>
<hr>
<form class="form-horizontal" method="POST" action="{{action('InformesController@generarInformePersonalizado', $proyecto['id'])}}">
    {{csrf_field()}}
    <div class="form-group">
        <div class="form-row">
            <div class="col-6">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="grafico" name="grafico">
                    <label class="custom-control-label" for="grafico">Incluir gráfico</label>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-6">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="observaciones" name="observaciones">
                    <label class="custom-control-label" for="observaciones">Incluir observaciones</label>
                </div>
            </div>
        </div>
        @foreach ($propiedades as $i => $propiedad)
            @if($propiedad->id != 6)
                <div class="form-row">
                    <div class="col-6">
                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="incluye_tareas_{{$i}}" name="incluye_tareas[]" value="{{$propiedad->id}}">
                            <label class="custom-control-label" for="incluye_tareas_{{$i}}">Incluir tareas color </label>
                        </div>
                    </div>
                    <div class="col-6">
                        <input style="height:50px" disabled="" type="color" class="form-control" value="{{$propiedad->color}}">
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    <div class="form-group text-center">
        <button type="submit" class="btn btn-primary">Generar informe personalizado
            <i class="fas fa-tasks"></i>
        </button>
    </div>
</form>

@endsection
