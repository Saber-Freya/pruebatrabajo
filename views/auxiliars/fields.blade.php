@include('auxiliars.script')
<div class = "row">
    <div class = "col-xs-6 col-md-3">
        <h1 class = "page-header">Auxiliar</h1>
    </div>
</div>

<fieldset class="row">
    <div class="form-group col-sm-6 col-lg-4">
        <i style="color: #DE6868" class="fa fa-asterisk"></i>{!! Form::label('nombre', 'Nombre:') !!}
        {!! Form::text('nombre', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-6 col-lg-4">
        <i style="color: #DE6868" class="fa fa-asterisk"></i>{!! Form::label('apellido', 'Apellido:') !!}
        {!! Form::text('apellido', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('calleA', 'Calle:') !!}
        {!! Form::text('calleA', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('no_extA', 'No. Exterior:') !!}
        {!! Form::text('no_extA', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('no_intA', 'No. Interior:') !!}
        {!! Form::text('no_intA', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('coloniaA', 'Colonia:') !!}
        {!! Form::text('coloniaA', null, ['class' => 'form-control']) !!}
    </div>

    {{--<div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('domicilio', 'Dirección:') !!}
        {!! Form::text('domicilio', null, ['class' => 'form-control']) !!}
    </div>--}}

    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('telefono', 'Teléfono:') !!}
        {!! Form::text('telefono', null, ['class' => 'form-control']) !!}
    </div>

</fieldset>

<h4>Correos:</h4>
<fieldset class="row">
    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('email', 'Correo:') !!}
        {!! Form::text('email', null, ['class' => 'form-control']) !!}
        {!! Form::hidden('cantidad_i', null, ['class' => 'form-control', 'value' => '1']) !!}
    </div>

    <div class="col-sm-1" style="margin-top:25px">
        {{--{!! Form::label('agrega', 'Agregar') !!}--}}
        {!! Form::button('<i class = "fa fa-plus-circle"></i>', ['class' => 'btn btn-success',
        'onclick' => "agregarEmail(this)", 'value' => "Remover Imagen" , 'title' => 'Agregar Correo']) !!}
    </div>

    <div class="row col-sm-5 col-lg-7 areas-header table-responsive">
        {!! Form::label('emails', 'Lista de Correos:') !!} <i class="info fa fa-info-circle" title="Lista de Correos del Auxiliar, aquí se pueden agregar varios"></i>
        <table class="table table-bordered" id="seccion-emails">
            <thead>
            <tr>
                <th style="width: 75%;">Correo</th>
                <th style="width: 25%;">Quitar</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</fieldset>

<div class="form-group col-sm-12">
     {!! Form::button('Guardar <i class = "glyphicon glyphicon-floppy-save"></i>', ['class' => 'btn btn-success guardar',
        'type'=>'submit']) !!}
    {{--{!! Form::button('Guardar  <i class = "glyphicon glyphicon-floppy-save"></i>', ['class' => 'btn btn-success guardar',
            'onclick'=>'pulsar(this)']) !!}--}}
    <a class = "btn btn-danger" href = "{!! route('auxiliars.index') !!}">
        Cancelar
        <i class = "glyphicon glyphicon-floppy-remove"></i>
    </a>
</div>