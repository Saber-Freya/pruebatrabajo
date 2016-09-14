@extends('app')@section('content')
<div class="container">
    @include('common.errors')
    {!! Form::model($hospital, ['route' => ['hospitals.update', $hospital->id], 'method' => 'patch']) !!}
        @include('hospitals.fields')
    {!! Form::close() !!}
</div>
@endsection