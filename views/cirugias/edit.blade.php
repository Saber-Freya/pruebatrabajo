@extends('app')
@section('content')
@include('common.errors')
<div class="container">
    {!! Form::model($cirugia, ['route' => ['cirugias.update', $cirugia->id], 'method' => 'patch']) !!}
        @include('cirugias.fields')
    {!! Form::close() !!}
</div>
@endsection