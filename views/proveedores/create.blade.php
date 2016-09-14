@extends('app')@section('content')
<div class="container">
    @include('common.errors')
    {!! Form::open(['route' => 'proveedores.store']) !!}
        @include('proveedores.fields')
    {!! Form::close() !!}
</div>
@endsection