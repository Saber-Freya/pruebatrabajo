@extends('app')@section('content')
<div class="container">
    @include('common.errors')
    {!! Form::model($cirugia, ['route' => ['cirugias.update', $cirugia->id], 'method' => 'patch']) !!}
        @include('cirugias.fields')
    {!! Form::close() !!}
</div>
@endsection