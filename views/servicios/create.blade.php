@extends('app')
@section('content')
@include('common.errors')
<div class="container">
    {!! Form::open(['route' => 'servicios.store']) !!}
        @include('servicios.fields')
    {!! Form::close() !!}
</div>
@endsection