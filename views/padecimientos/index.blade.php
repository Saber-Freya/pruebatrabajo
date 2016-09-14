@extends('app')@section('content')
<div class="container">
    @include('flash::message')

    <div class="row">
        <h2 class="pull-left">Padecimientos</h2>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('padecimientos.create') !!}">Agregar</a>
    </div>
    <div class="col-xs-12">Hay {{ sizeof($padecimientos) }} Padecimientos en esta página</div>

    <div class="row">
        @if($padecimientos->isEmpty())
            <div class="text-center">No hay registros.</div>
        @else

            <div class="table-responsive col-xs-12">
                <table class="table">
                    <thead>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th width="50px">Acción</th>
                    </thead>
                    <tbody>
                    @foreach($padecimientos as $padecimiento)
                        <tr>
                            <td>{!! $padecimiento->nombre !!}</td>
                            <td>{!! $padecimiento->descripcion !!}</td>
                            <td>
                                <a title="Editar" href="{!! route('padecimientos.edit', [$padecimiento->id]) !!}"><i class="glyphicon glyphicon-edit"></i></a>
                                <a title="Borrar" href="#" data-slug="padecimientos" data-id="{!! $padecimiento->id !!}"  onclick="return borrarElemento(this)"><i class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    {!!  str_replace('/padecimientos', '/dr_basico/padecimientos', $padecimientos->render()) !!}
</div>
@endsection