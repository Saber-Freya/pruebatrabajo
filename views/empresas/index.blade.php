@extends('app')@section('content')@include('flash::message')
<div class="col-xs-12 col-sm-12 col-lg-12"><h1 class="pull-left">Empresas <i class=" info fa fa-info-circle" title="La información de la empresa será afectada a partir del momento en que la actualize (documentos, recetas, etc.)"></i></h1>
    @if($empresas->isEmpty())<a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('empresas.create') !!}">Agregar
        Empresa</a>
    @endif
</div>
<div class="col-xs-12 col-sm-12 col-lg-12">
    @if($empresas->isEmpty())
        <div class="well text-center">No Se Encontraron Resultados.</div>
    @else
        <div class="row table-responsive">
            <table class="table">
                <thead>
                <th>Nombre / Razón Social</th>
                <th>Nombre Comercial</th>
                <th>RFC</th>
                <th>Dirección</th>
                <th>Ubicación</th>
                <th>E-mail</th>
                <th>Código Postal
                </th> {{--<th>Certificado (cer)</th>                        <th>Clave privada (key)</th>--}}
                <th>Logo</th>
                <th width="50px">Acción</th>
                </thead>
                <tbody>
                @foreach($empresas as $empresa)
                    <tr>
                        <td>{!! $empresa->nombre !!}</td>
                        <td>{!! $empresa->nom_comercial !!}</td>
                        <td>{!! $empresa->rfc !!}</td>
                        <td>{!! $empresa->calle !!} {!! $empresa->num_ext !!} {!! $empresa->num_int !!}
                            , {!! $empresa->colonia !!} CP. {!! $empresa->codigo_postal !!}</td>
                        <td>{!! $empresa->estadoStr !!}, {!! $empresa->ciudadStr !!}</td>
                        <td>{!! $empresa->email !!}</td>
                        <td>{!! $empresa->codigo_postal !!}</td>
                        {{--<td>{!! $empresa->certificado !!}</td>                        <td>{!! $empresa->keypass !!}</td>--}}
                        <td>{!! HTML::image('img/uploads/empresa/'.$empresa->img_logo, 'logo', array('class'=>'thumb','style'=>'max-width: 50px')) !!}</td>
                        <td><a href="{!! route('empresas.edit', [$empresa->id]) !!}"><i class="glyphicon glyphicon-edit"
                                                                                        title="Editar"></i></a>
                            {{--<a href="{!! route('empresas.delete', [$empresa->id]) !!}" onclick="return confirm('Si borra la empresa tendrá que realizar el proceso completamente desde el inicio. Las facturas que ya hayan realizado seguirán en el sistema. Al igual que los productos y clientes. Desea continuar?')"><i class="glyphicon glyphicon-remove"></i></a>--}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
        {!!  str_replace('/empresas', '/dr_basico/empresas', $empresas->render()) !!}
</div>
@endsection
