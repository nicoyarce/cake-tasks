@extends('layouts.master')
@section('content')
@section('tituloPagina', 'Informes')
@include('layouts.errors')
@if(is_null($proyecto->deleted_at))
    {{-- Para informes normales --}}
    <div class="row justify-content-between">
        <div class="col-9">
            <h1>{{$proyecto->nombre}}</h1>
        </div>
        <div class="col-2 d-flex align-items-center">
            @can('crear_informes')
            <a type="button" class="btn btn-success mx-auto" data-toggle="modal" data-target="#exampleModal" href="" role="button">Generar informe
                <i class="fas fa-plus"></i>
            </a>
            @endcan
        </div>
        <div class="col-1 d-flex align-items-center">
            <a type="button" class="btn btn-primary float-right" href="/proyectos">Atrás <i class="fas fa-arrow-left "></i></a>
        </div>
    </div>
    <hr>
    <div class="row justify-content-between">
        <div class="col-12">
            <h2>Informes</h2>
        </div>
    </div>
    @if(count($lista_informes)>0)
        <table id="tablaInformes" class="table table-hover">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Ver detalles</th>                    
                </tr>
            </thead>
            <tbody>
                @foreach ($lista_informes as $fechas)                                       
                    <tr>                         
                        <td>{{$fechas[0]->created_at->format('d-M-Y')}}</td>                                            
                        <td>
                            <div class="dropdown dropright">
                                <button class="btn btn-primary btn-lg dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-eye"></i>
                                </button>                                
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @foreach ($fechas as $informe)                                   
                                    <a href="{{Storage::url($informe->ruta)}}" class="dropdown-item" target="_blank" rel="noopener noreferrer">
                                        {{$informe->created_at->format('H:i:s')}}                                        
                                        @foreach((array)json_decode($informe->colores) as $color)
                                            <div class="cuadro-colores" style="background-color:{{$color}};">&nbsp;</div>
                                        @endforeach
                                        @can('borrar_informes')
                                        <div class="text-right mr-1 mb-1">
                                            <form method="POST" action="/informes/destroy/{{$informe->id}}">
                                                {{csrf_field()}}
                                                {{method_field('DELETE')}}
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Desea eliminar el informe?')"><i class="fas fa-trash-alt"></i></button>
                                            </form>
                                        </div>
                                        @endcan
                                        <div class="dropdown-divider"></div>
                                    @endforeach
                                    </a>
                                    
                                </div>
                            </div>                               
                        </td>                 
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
<form class="form-horizontal" method="POST" action="{{action('InformesController@generarInforme', $proyecto['id'])}}">
    {{csrf_field()}}
    <div class="form-group text-center">
        <button type="submit" class="btn btn-success mx-auto" role="button">Generar informe completo
            <i class="fas fa-file-alt"></i>
        </button>
    </div>
</form>
<hr>
<div class="form-group text-center">
    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#detalle_generacion" aria-expanded="false" aria-controls="detalle_generacion">
        Detalles informe personalizado
        <i class="fas fa-tasks"></i>
    </button>    
</div>
<div class="collapse" id="detalle_generacion">
    <div class="card card-body">
        <div class="alert alert-warning alert-dismissible fade show" role="alert" style="display: none;">
            Debe elegir algun tipo de tarea.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="personalizado" class="form-horizontal" method="POST" action="{{action('InformesController@generarInforme', $proyecto['id'])}}">
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
                <hr>
                @foreach ($propiedades as $i => $propiedad)
                    @if($propiedad->id != 6)
                        <div class="form-row">
                            <div class="col-6">
                                <div class="custom-control custom-checkbox">
                                <input type="checkbox" rel="incluye_tareas" class="custom-control-input" id="incluye_tareas_{{$i}}" name="incluye_tareas[]" value="{{$propiedad->id}}">
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
                <button type="button" class="btn btn-primary" onclick="enviar()">Generar
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="/css/fixedHeader.dataTables.min.css">
<script src="/js/dataTables.fixedHeader.min.js"></script>
<script src="/js/jquery.stickytableheaders.min.js"></script>
<script>
	$(document).ready(function() {
		$('#tablaInformes').stickyTableHeaders();
    	$('#tablaInformes').DataTable( {
    		//"order": [[ 1, 'asc' ], [ 2, 'asc' ]],
    		//"fixedHeader": true,
    		"ordering": false,
    		"paging":   false,
	        "language": {
	            "url": "/js/locales/datatables.net_plug-ins_1.10.19_i18n_Spanish.json"
	        }
    	} );
    } );
    
	function enviar() {
        if (!$('input[rel=incluye_tareas]:checked').length > 0) {
            $('.alert').show();               
        } else {
            $("#personalizado").submit();
        }
    }
</script>
@endsection
