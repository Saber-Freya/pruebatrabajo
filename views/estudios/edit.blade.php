@extends('app')
@section('content')
@include('common.errors')
<div class="container">
    {!! Form::model($estudio, ['route' => ['estudios.update', $estudio->id], 'method' => 'patch']) !!}
        @include('estudios.fields')
    {!! Form::close() !!}
</div>
@endsection