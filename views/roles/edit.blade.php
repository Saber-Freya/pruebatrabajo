@extends('app')@section('content')
    <div class="container">
        @include('common.errors')
        {!! Form::model($role, ['route' => ['roles.update', $role->id], 'method' => 'patch']) !!}

        @include('roles.fields')
                <!--- Submit Field --->
        <div class="form-group col-xs-12" style="text-align:right">
            @if(Entrust::can('editar_roles'))
                    <!--{!! Form::submit('Actualizar', ['class' => 'btn btn-success']) !!}-->
            <button type="button" onclick="actualizarRol()" id="{!! $role->id !!}" class="btn btn-success">Actualizar <i class = "glyphicon glyphicon-floppy-save"></i></button>
            @endif
            <a class = "btn btn-danger cancelar" href = "{!! route('roles.index') !!}">
                Cancelar
                <i class = "glyphicon glyphicon-floppy-remove"></i>
            </a>
        </div>
        {!! Form::close() !!}
    </div>
@endsection