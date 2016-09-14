@extends('app')@section('content')
<div class="container">
    @include('common.errors')
    {!! Form::open(['route' => 'padecimientos.store']) !!}
        @include('padecimientos.fields')
    {!! Form::close() !!}
</div>
@endsection