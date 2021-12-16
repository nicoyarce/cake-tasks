@extends('layouts.master')
@section('content')
@section('tituloPagina', 'Tipos Tarea')
<div class="row">
    <div class="col-10">
        <h1>Tipos de tarea</h1>
    </div>    
    <div class="col-2">
        <a type="button" class="btn btn-success float-right"
        @if(empty($editar))
        data-toggle="modal" data-target="#exampleModal" href=""
        @else
        href="{{action('TipoTareasController@indexConModal')}}"
        @endif
        role="button" >Crear tipo tarea <i class="fas fa-plus"></i>
        </a>
    </div>
</div>
@if(count($tipo_tareas)>0)
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre Tipo Tarea</th>
                    <th>Fecha Creación</th>
                    <th>Cambiar nombre</th>
                    <th>Editar nomenclaturas avance</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tipo_tareas as $tipo_tarea)
                <tr id="{{$tipo_tarea->id}}">
                    <td>{{$tipo_tarea->descripcion}}</td>
                    <td>{{$tipo_tarea->created_at->format('d-M-Y')}}</td>                   
                    <td>
                        <a href="{{action('TipoTareasController@edit', $tipo_tarea['id'])}}" type="button" class="btn btn-primary">
                        <i class="fas fa-edit"></i>
                        </a>
                    </td>
                    <td>
                        <a href="{{action('NomenclaturaAvancesController@index', ['id' => $tipo_tarea->id])}}" type="button" class="btn btn-primary">
                        <i class="fas fa-list-ul"></i>
                        </a>
                    </td>   
                    <td>
                        @if($tipo_tarea->habilitaBorrado)
                        <form method="POST" action="{{action('TipoTareasController@destroy', $tipo_tarea->id)}}">
                            {{csrf_field()}}
                            {{method_field('DELETE')}}
                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Desea eliminar el tipo de tarea?')"><i class="fas fa-trash-alt"></i></button>
                        </form>
                        @else
                        <span data-toggle="tooltip" data-placement="bottom" data-html="true" title="Existen tareas asociadas a este tipo">
                            <button class="btn btn-danger" disabled="true" style="pointer-events: none;"><i class="fas fa-trash-alt"></i></button>
                        </span>
                        @endif
                    </td>                   
                </tr>
                @endforeach
            </tbody>
        </table>
    {{$tipo_tareas->links()}}
    @else
        <hr>
        <h3 class="text-center">No hay tipos de tareas</h3>
    @endif  
@endsection

@if(empty($editar))
    {{-- Crear tipo --}}
    @section('modal-title')
        Crear tipo de tarea
    @endsection
    @section('modal-content')
        @include('layouts.errors')
        <form class="form-horizontal" method="POST" action="{{action('TipoTareasController@store')}}">
            {{csrf_field()}}
            <div class="form-group">
                <label for="descripcion">Nombre tipo de tarea</label>       
                <input type="text" class="form-control" id="descripcion" required name="descripcion">        
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
    {{-- Modificar tipo --}}
    @section('modal-title')
        Modificar área
    @endsection
    @section('modal-content')
        @include('layouts.errors')
        <form class="form-horizontal" method="POST" action="{{action('TipoTareasController@update', $editar->id)}}">
            {{csrf_field()}}
            {{method_field('PUT')}}
            <div class="form-group">
                <label for="descripcion">Nombre tipo de tarea</label>         
                <input type="text" class="form-control" id="descripcion" required name="descripcion" value="{{$editar->descripcion}}">        
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
