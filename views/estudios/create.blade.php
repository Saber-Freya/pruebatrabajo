@extends('app')@section('content')
<div class="container">
    @include('common.errors')
    {!! Form::open(['route' => 'estudios.store']) !!}
        @include('estudios.fields')
    {!! Form::close() !!}
</div>
@endsection