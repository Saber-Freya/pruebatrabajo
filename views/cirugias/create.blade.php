@extends('app')
@section('content')
@include('common.errors')
<div class="container-fluid">
    {!! Form::open(['route' => 'cirugias.store']) !!}
        @include('cirugias.fields')
    {!! Form::close() !!}
</div>
@endsection