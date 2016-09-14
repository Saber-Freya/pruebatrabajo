<div class = "row">
    <div class = "col-xs-6 col-md-3">
        <h2 class = "page-header">Estudios</h2>
    </div>
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('nombre', 'Nombre:') !!}
    {!! Form::text('nombre', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('descripcion', 'Descripción:') !!}
    {!! Form::textarea('descripcion', null, ['class' => 'form-control','rows' => '4']) !!}
</div>

<!--- Submit Field --->
<div class="form-group col-sm-12">
     {!! Form::button('Guardar <i class = "glyphicon glyphicon-floppy-save"></i>', ['class' => 'btn btn-success',
        'type'=>'submit']) !!}
        <a class = "btn btn-danger" href = "{!! route('estudios.index') !!}">
            Cancelar <i class = "glyphicon glyphicon-floppy-remove"></i>
        </a>
</div>