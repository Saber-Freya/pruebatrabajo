<div class = "row">
    <div class = "col-xs-6 col-md-3">
        <h1 class = "page-header">Costos</h1>
    </div>
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('tipo', 'Tipo:') !!}
    {!! Form::select('tipo', [
    '1'=>'Consulta',
    '2'=>'Cirugía'
    ], null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('titulo', 'Titulo: ') !!}<i class="fa fa-info-circle" title="Nombre o titulo de la Consulta o Cirugía."></i>
    {!! Form::text('titulo', null, ['class' => 'form-control', 'maxlength' => 60]) !!}
</div>

<!--- Costo Field --->
<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('costo2', 'Costo:') !!}
    {!! Form::text('costo2', null, ['class' => 'form-control currency', 'placeholder'=>'$', 'onkeyup' => 'servicio(this.form)']) !!}
</div>
{!! Form::hidden('costo', null, ['id' => 'costo']) !!}
<script>
    function servicio(form) {
        $('#costo2').priceFormat({
            prefix: '$ ',
            centsSeparator: '.',
            thousandsSeparator: ',',
            allowNegative: false
        });
        var precio = $('#costo2').unmask();
        precio = precio / 100;
        $('#costo').val(precio);
        return;
    }
</script>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('descripcion', 'Descripción: ') !!}
    {!! Form::textarea('descripcion', null, ['class' => 'form-control', 'rows' => '4']) !!}
</div>

<!--- Submit Field --->
<div class="form-group col-sm-12">
     {!! Form::button('Guardar <i class = "glyphicon glyphicon-floppy-save"></i>', ['class' => 'btn btn-success',
        'type'=>'submit']) !!}
        <a class = "btn btn-danger" href = "{!! route('costos.index') !!}">
            Cancelar
            <i class = "glyphicon glyphicon-floppy-remove"></i>
        </a>
</div>