@extends('app')@section('content')
<div class="container">
    @include('common.errors')
    {!! Form::open(['route' => 'productos.store', "id"=>"form_materiales"]) !!}
        @include('productos.fields')
    {!! Form::close() !!}
</div>
@endsection
