@extends('app')@section('content')
    <div class="container">
        @include('common.errors')
        {!! Form::open(['route' => 'auxiliars.store']) !!}
            @include('auxiliars.fields')
        {!! Form::close() !!}
    </div>
@endsection