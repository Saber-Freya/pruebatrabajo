@extends('app')@section('content')
<div class="container-fluid">
    @include('common.errors')
    {!! Form::open(['route' => 'cirugias.store']) !!}
        <div class = "row">
            <div class = "col-xs-6 col-md-3">
                <h1 class = "page-header">Cirugía</h1>
            </div>
        </div>
        <fieldset>
            <div class = "col-xs-12 col-xs-offset-0 col-md-offset-3 col-md-6 margentop20">
                <ul class = "list-group">
                    <li class = "list-group-item">
                        <i class = "fa fa-user"></i>
                        <strong> {!! $cita->paciente !!}</strong>
                        <span class="pull-right"> {!! $cita->hora !!} </span><strong class="pull-right" style="padding-right: 5px"> Hora </strong>
                    </li>
                    <li class = "list-group-item col-xs-6">
                        <strong>Cirugia a realizar: </strong>
                        <br/>
                        <span>{!! $cita->cirugia !!}</span>
                        <br/>
                    </li>
                    <li class = "list-group-item col-xs-6">
                        <strong>Fecha: </strong>
                        <br/>
                        <span>{!! $cita->fecha !!}</span>
                        <br/>
                    </li>
                </ul>
            </div>
        </fieldset>
        {!! Form::hidden('id_servicio', $cita->id, ['id' => 'id_servicio','class' => 'form-control']) !!}
        @include('cirugias.fields')
        <div class="form-group col-sm-12" style="margin-top: 10px">
            {!! Form::button('Guardar <i class = "glyphicon glyphicon-floppy-save"></i>', ['class' => 'btn btn-success
            guardarCirugia','accion' => 'g', 'onclick'=>'guardarCirugia()']) !!}
            <a class = "btn btn-danger" href = "{{ url('/') }}">
                Cancelar <i class = "glyphicon glyphicon-floppy-remove"></i>
            </a>
        </div>
    {!! Form::close() !!}
</div>
@endsection