@extends('app')@section('content')
<div class="container">
    @include('flash::message')

    <div class="row">
        <h2 class="pull-left">Medicamentos</h2>
        @if(Entrust::can('crear_medicamentos'))
            <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('medicamentos.create') !!}">Agregar</a>
        @endif
    </div>
    <div class="col-xs-12">Hay {{ sizeof($medicamentos) }} Medicamentos en esta página</div>

    <div class="row">
        @if($medicamentos->isEmpty())
            <div class="text-center">No hay registros.</div>
        @else

            <div class="table-responsive col-xs-12">
                <table class="table">
                    <thead>
                        <th>Componente Químico</th>
                        <th>Marca</th>
                        <th>¿Necesita Receta?</th>
                        @if(Entrust::can('editar_medicamentos')|| Entrust::can('eliminar_medicamentos'))
                            <th width="50px">Acción</th>
                        @endif
                    </thead>
                    <tbody>
                    @foreach($medicamentos as $medicamento)
                        <?php if($medicamento->receta != 1){ $receta = 'NO';}else{ $receta = 'SI'; }?>
                        <tr>
                            <td>{!! $medicamento->componente !!}</td>
                            <td>{!! $medicamento->marca !!}</td>
                            <td>{!! $receta !!}</td>
                            <td>
                                @if(Entrust::can('editar_medicamentos'))
                                    <a title="Editar" href="{!! route('medicamentos.edit', [$medicamento->id]) !!}"><i class="glyphicon glyphicon-edit"></i></a>
                                @endif
                                @if(Entrust::can('eliminar_medicamentos'))
                                    <a title="Borrar" href="#" data-slug="medicamentos" data-id="{!! $medicamento->id !!}"  onclick="return borrarElemento(this)"><i class="fa fa-trash-o"></i></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    {!!  str_replace('/medicamentos', '/dr_basico/medicamentos', $medicamentos->render()) !!}
</div>
@endsection