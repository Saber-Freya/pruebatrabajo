@extends('app')
@section('content')
@include('common.errors')
<div class="container">
    {!! Form::open(['route' => 'costos.store']) !!}
        @include('costos.fields')
    {!! Form::close() !!}
</div>
@endsection