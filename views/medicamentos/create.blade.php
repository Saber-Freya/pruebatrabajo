@extends('app')@section('content')
<div class="container">
    @include('common.errors')
    {!! Form::open(['route' => 'medicamentos.store']) !!}
        <div class = "row">
            <div class = "col-xs-6 col-md-3">
                <h2 class = "page-header">Medicamento</h2>
            </div>
        </div>
        @include('medicamentos.fields')
        <div class="form-group col-xs-12">
            {!! Form::button('Guardar <i class = "glyphicon glyphicon-floppy-save"></i>', ['class' => 'btn btn-success guardarMedicamento',
                'onclick'=>'guardarMedicamento()']) !!}
            <a class = "btn btn-danger" href = "{!! route('medicamentos.index') !!}">
                Cancelar <i class = "glyphicon glyphicon-floppy-remove"></i>
            </a>
        </div>
    {!! Form::close() !!}
</div>
@endsection