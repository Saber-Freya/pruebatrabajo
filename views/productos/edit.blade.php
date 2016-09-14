@extends('app')@section('content')
<div class="container">
    @include('common.errors')
    {!! Form::model($productos, ['route' => ['productos.update', $productos->id], 'method' => 'patch']) !!}
        @include('productos.fields')
    {!! Form::close() !!}
</div>
@endsection
