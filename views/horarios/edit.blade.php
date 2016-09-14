@extends('app')@section('content')
<div class="container">
    @include('common.errors')
    {!! Form::model($horarios, ['route' => ['horarios.update', $horarios->id], 'method' => 'patch']) !!}
        @include('horarios.fields')
    {!! Form::close() !!}
</div>
@endsection
