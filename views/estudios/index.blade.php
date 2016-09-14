@extends('app')@section('content')
<div class="container">
    @include('flash::message')
    <div class="row">
        <h2 class="pull-left">Estudios</h2>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('estudios.create') !!}">Agregar</a>
    </div>

    <div class="col-xs-12">Hay {{ sizeof($estudios) }} Estudio(s) en esta página</div>

    <div class="row">
        @if($estudios->isEmpty())
            <div class="text-center">No hay registros.</div>
        @else

            <div class="table-responsive col-xs-12">
                <table class="table">
                    <thead>
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th width="50px">Action</th>
                    </thead>
                    <tbody>
                    @foreach($estudios as $estudio)
                        <tr>
                            <td>{!! $estudio->nombre !!}</td>
                            <td>{!! $estudio->descripcion !!}</td>
                            <td>
                                <a title="Editar" href="{!! route('estudios.edit', [$estudio->id]) !!}"><i class="glyphicon glyphicon-edit"></i></a>
                                <a title="Borrar" href="#" data-slug="estudios" data-id="{!! $estudio->id !!}"  onclick="return borrarElemento(this)"><i class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    {!!  str_replace('/estudios', '/dr_basico/estudios', $estudios->render()) !!}
</div>
@endsection