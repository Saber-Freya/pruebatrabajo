@extends('app')

@section('content')

    @include('common.errors')
    <div class = "row">
        <div class = "col-xs-6 col-md-3">
            <h1 class = "page-header">Empresa</h1>
        </div>

    </div>

    {!! Form::model($empresa, ['route' => ['empresas.update', $empresa->id],'files'=>true,'method' => 'patch']) !!}

        @include('empresas.fields')

    {!! Form::close() !!}
    {{--<div class="col-xs-12 col-md-6 col-xs-offset-4" style="margin-top: -4%">
       <h4>El logo no sera modificado al menos que se seleccione otra imagen.</h4>
    </div>--}}
@endsection
