@extends('app')@section('content')
<div class="container">
    @include('flash::message')
    <div class="row">
        <h2 class="pull-left">Proveedores</h2>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('proveedores.create') !!}">Agregar</a>
    </div>

    <div class="col-xs-12">Hay {{ sizeof($proveedores) }} Proveedor(es) en esta página</div>

    <div class="row">

        @if($proveedores->isEmpty())
            <div class="text-center">No hay registros.</div>
        @else

            <div class="table-responsive col-xs-12">
                <table class="table sort">
                    <thead>
                    <th>Nombre</th>
                    <th>Calle</th>
                    <th>No. Exterior</th>
                    <th>No. Interior</th>
                    <th>Colonia</th>
                    <th>Teléfono</th>
                    <th width="50px">Acción</th>
                    </thead>
                    <tbody>
                        @foreach($proveedores as $proveedor)
                            <tr>
                                <td>{!! $proveedor->nombre !!}</td>
                                <td>{!! $proveedor->calle !!}</td>
                                <td>{!! $proveedor->no_int !!}</td>
                                <td>{!! $proveedor->no_ext !!}</td>
                                <td>{!! $proveedor->colonia !!}</td>
                                <td>{!! $proveedor->calle !!}</td>
                                <td>
                                    <a title="Editar" href="{!! route('proveedores.edit', [$proveedor->id]) !!}">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    <a title="Borrar" href="#" data-slug="proveedores" data-id="{!! $proveedor->id !!}"
                                       onclick="return borrarElemento(this)"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    {!!  str_replace('/proveedores', '/dr_basico/proveedores', $proveedores->render()) !!}
</div>
@endsection