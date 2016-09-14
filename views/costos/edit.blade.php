@extends('app')
@section('content')
@include('common.errors')
<div class="container">
    {!! Form::model($costos, ['route' => ['costos.update', $costos->id], 'method' => 'patch']) !!}
        @include('costos.fields')
    {!! Form::close() !!}
</div>
@endsection