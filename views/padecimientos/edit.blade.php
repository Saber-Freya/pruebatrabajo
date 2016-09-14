@extends('app')@section('content')
<div class="container">
    @include('common.errors')
    {!! Form::model($padecimiento, ['route' => ['padecimientos.update', $padecimiento->id], 'method' => 'patch']) !!}
        @include('padecimientos.fields')
    {!! Form::close() !!}
</div>
@endsection