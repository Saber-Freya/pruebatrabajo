@extends('app')@section('content')
<div class="container">
    @include('common.errors')
    {!! Form::open(['route' => 'clientes.store', 'files'=>true]) !!}
        @include('clientes.fields')
    {!! Form::close() !!}
</div>
@endsection