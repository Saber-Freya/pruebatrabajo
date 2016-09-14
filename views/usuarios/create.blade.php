@extends('app')

@section('content')

    @include('common.errors')

    {!! Form::open(['route' => 'usuarios.store']) !!}

        @include('usuarios.fields')

    {!! Form::close() !!}
@endsection
