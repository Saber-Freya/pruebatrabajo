@extends('app')@section('content')
<div class="container">
    @include('flash::message')

    <div class="row">
        <h2 class="pull-left">Hospitales</h2>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('hospitals.create') !!}">Agregar</a>
    </div>
    <div class="col-xs-12">Hay {{ sizeof($hospitals) }} Hospitales en esta página</div>

    <div class="row">
        @if($hospitals->isEmpty())
            <div class="text-center">No hay registros.</div>
        @else

            <div class="table-responsive col-xs-12">
                <table class="table">
                    <thead>
                        <th>Nombre</th>
                        <th>Calle</th>
                        <th>No. Exterior</th>
                        <th>No. Interior</th>
                        <th>Colonia</th>
                        <th>Teléfono</th>
                        <th>Principal</th>
                        <th width="50px">Acción</th>
                    </thead>
                    <tbody>
                     
                    @foreach($hospitals as $hospital)
                        <?php if($hospital->principal != 1){ $principal = 'NO';}else{ $principal = 'SI'; }?>
                        <tr>
                            <td>{!! $hospital->nombre !!}</td>
                            <td>{!! $hospital->calle !!}</td>
                            <td>{!! $hospital->no_ext !!}</td>
                            <td>{!! $hospital->no_int !!}</td>
                            <td>{!! $hospital->colonia !!}</td>
                            <td>{!! $hospital->tel !!}</td>
                            <td>{!! $principal !!}</td>
                            <td>
                                <a title="Editar" href="{!! route('hospitals.edit', [$hospital->id]) !!}"><i class="glyphicon glyphicon-edit"></i></a>
                                <a title="Borrar" href="#" data-slug="hospitals" data-id="{!! $hospital->id !!}"  onclick="return borrarElemento(this)"><i class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    {!!  str_replace('/hospitals', '/dr_basico/hospitals', $hospitals->render()) !!}
</div>
@endsection