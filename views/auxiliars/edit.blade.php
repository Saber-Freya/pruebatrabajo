@extends('app')@section('content')
    <div class="container">
        @include('common.errors')
        {!! Form::model($auxiliar, ['route' => ['auxiliars.update', $auxiliar->id], 'method' => 'patch']) !!}
            @include('auxiliars.fields')
        {!! Form::close() !!}
    </div>
@endsection