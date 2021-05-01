@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col-8">
        <h1>Nomenclaturas avance: </h1><h3>{{$tipo_tarea->descripcion}}</h3>
    </div>
    <div class="col-2">
        <a type="button" class="btn btn-primary float-right" href="/tipotareas">Atrás <i class="fas fa-arrow-left "></i></a>
    </div>
    <div class="col-2">
        <a type="button" class="btn btn-success float-right"
        @if(empty($editar))
        data-toggle="modal" data-target="#exampleModal" href=""
        @else
        href="{{action('NomenclaturaAvancesController@indexConModal', ['id' => $tipo_tarea])}}"
        @endif
        role="button" >Crear tipo avance <i class="fas fa-plus"></i>
        </a>
    </div>
</div>
@include('layouts.errors')
@if(count($tipos_avance)>0)
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Porcentaje representado</th>
                    <th>Nombre nomenclatura</th>                    
                    <th>Fecha Creación</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tipos_avance as $tipo_avance)
                <tr id="{{$tipo_avance->id}}">
                    <td>{{$tipo_avance->porcentaje}}</td>
                    <td>{{$tipo_avance->glosa}}</td>                    
                    <td>{{$tipo_avance->created_at->format('d-M-Y')}}</td>                  
                    <td>
                        <a href="{{route('avances.edit', $tipo_avance->id)}}" type="button" class="btn btn-primary">
                        <i class="fas fa-edit"></i>
                        </a>
                    </td>                   
                    <td>
                        @if($tipo_avance->habilitaBorrado)
                        <form method="POST" action="{{action('NomenclaturaAvancesController@destroy', $tipo_avance->id)}}">
                            {{csrf_field()}}
                            {{method_field('DELETE')}}
                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Desea eliminar la nomenclatura?')"><i class="fas fa-trash-alt"></i></button>
                        </form>
                        @else
                        <span data-toggle="tooltip" data-placement="bottom" data-html="true" title="Existen tareas asociadas a esta nomenclatura">
                            <button class="btn btn-danger" disabled="true" style="pointer-events: none;"><i class="fas fa-trash-alt"></i></button>
                        </span>
                        @endif
                    </td>                   
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <hr>
        <h3 class="text-center">No hay nomenclaturas de avance</h3>
    @endif  
@endsection
@if(empty($editar))
{{-- Crear area --}}
@section('modal-title')
Crear nomenclatura
@endsection
@section('modal-content')
@include('layouts.errors')
<form class="form-horizontal" method="POST" action="{{action('NomenclaturaAvancesController@store', ['id' => $tipo_tarea->id])}}">
    {{csrf_field()}}
    <input type="hidden" id="tipo_tarea_id" name="tipo_tarea_id" value="{{$tipo_tarea->id}}">
    <div class="form-group">
        <label for="glosa">Nombre nomenclatura</label>       
        <input type="text" class="form-control" id="glosa" required name="glosa">        
    </div>
    <div class="form-group">
        <label for="porcentaje">Porcentaje representado</label>       
        <input type="text" class="form-control" id="porcentaje" required name="porcentaje">        
    </div>      
    <div class="form-group text-center">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>
@if(isset($abrir_modal))    
    <script type="text/javascript">
        $(document).ready(function(){
            $('#exampleModal').modal('toggle')
        }); 
    </script>
@endif
@endsection
@else
{{-- Modificar area --}}
@section('modal-title')
Modificar nomenclatura
@endsection
@section('modal-content')
@include('layouts.errors')
<form class="form-horizontal" method="POST" action="{{action('NomenclaturaAvancesController@update', $editar->id)}}">
    {{csrf_field()}}
    <input type="hidden" id="tipo_tarea_id" name="tipo_tarea_id" value="{{$tipo_tarea->id}}">
    {{method_field('PUT')}}
    <div class="form-group">
        <label for="glosa">Nombre nomenclatura</label>       
        <input type="text" class="form-control" id="glosa" required name="glosa" value="{{$editar->glosa}}">        
    </div>       
    <div class="form-group">
        <label for="porcentaje">Porcentaje representado</label>       
        <input type="text" class="form-control" id="porcentaje" required name="porcentaje" value="{{$editar->porcentaje}}">    
    </div>
    <div class="form-group text-center">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function(){
        $('#exampleModal').modal('toggle')
    }); 
</script>
@endsection
@endif
