@extends('app')

@section('content')

    @include('common.errors')

    {!! Form::model($usuarios, ['route' => ['usuarios.update', $usuarios->id], 'method' => 'patch']) !!}

        @include('usuarios.fields')

    {!! Form::close() !!}
@endsection