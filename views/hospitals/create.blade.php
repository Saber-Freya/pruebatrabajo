@extends('app')@section('content')
<div class="container">
    @include('common.errors')
    {!! Form::open(['route' => 'hospitals.store']) !!}
        @include('hospitals.fields')
    {!! Form::close() !!}
</div>
@endsection