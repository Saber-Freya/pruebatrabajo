@extends('app')@section('content')
<div class="container">
    @include('common.errors')
    {!! Form::model($proveedor, ['route' => ['proveedores.update', $proveedor->id], 'method' => 'patch']) !!}
        @include('proveedores.fields')
    {!! Form::close() !!}
</div>
@endsection