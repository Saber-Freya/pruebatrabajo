@extends('app')
@section('content')
@include('common.errors')
<div class="container">
    {!! Form::model($servicio, ['route' => ['servicios.update', $servicio->id], 'method' => 'patch']) !!}
        @include('servicios.fields')
    {!! Form::close() !!}
</div>
@endsection