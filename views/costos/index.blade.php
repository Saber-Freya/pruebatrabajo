@extends('app')
@section('content')
<div class="container">
@include('flash::message')
    <div class="row">
        <h2 class="pull-left">Costos</h2>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('costos.create') !!}">Agregar</a>
    </div>
<div class="col-xs-12">Hay {{ sizeof($costos) }} Costos</div>
    <div class="row tabla table-responsive" style="margin-top: 25px">
        @if($costos->isEmpty())
            <div class="well text-center">No hay registros.</div>
        @else
            <div class="table-responsive col-xs-12">
                <table class="table">
                    <thead>
                        <th>Titulo</th>
                        <th>Tipo</th>
                        <th>Costo</th>
                        <th width="50px">Action</th>
                    </thead>
                    <tbody>
                    @foreach($costos as $costo)
                        <?php
                        if($costo->tipo == 1){$tipo = "Consulta";}
                        else{$tipo = "Cirugia";}
                        if($costo->estatus == 1){$estatus = "Pagado";}
                        else{$estatus = "Pendiente de Pago";}
                        ?>
                        <tr>
                            <td>{!! $costo->titulo !!}</td>
                            <td>{!! $tipo !!}</td>
                            <td>$ {!! number_format($costo->costo, 2, '.', ',') !!}</td>
                            <td>
                                <a title="Editar" href="{!! route('costos.edit', [$costo->id]) !!}"><i class="glyphicon glyphicon-edit"></i></a>
                                <a title="Borrar" href="#" data-slug="costos" data-id="{!! $costo->id !!}"
                                   onclick="return borrarElemento(this)"><i class="glyphicon glyphicon-remove"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <?php echo $costos->render(); ?>
</div>
@endsection