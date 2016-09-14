@extends('app')
@section('content')
@include('common.errors')
<div class="container">
    {!! Form::model($cliente, ['route' => ['clientes.update', $cliente->id], 'method' => 'patch', 'files'=>true]) !!}
        @include('clientes.fields')
    {!! Form::close() !!}
</div>
@endsection
