@extends('app')@section('content')
    <div class="container">
        @include('common.errors')
        {!! Form::open(['route' => 'roles.store']) !!}
            @include('roles.fields')

            <!--- Submit Field --->
            <div class="form-group col-xs-12" style="text-align:right">
                @if(Entrust::can('crear_roles'))
                    <button type="button" onclick="guardarRol()" class="btn btn-success">Guardar <i class = "glyphicon glyphicon-floppy-save"></i></button>
                @endif
                <a class = "btn btn-danger cancelar" href = "{!! route('roles.index') !!}">
                    Cancelar
                    <i class = "glyphicon glyphicon-floppy-remove"></i>
                </a>
            </div>
        {!! Form::close() !!}
    </div>
@endsection
