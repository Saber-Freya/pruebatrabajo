<div class = "row">
    <div class = "col-xs-6 col-md-3">
        <h2 class = "page-header">Hospital</h2>
    </div>
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('nombre', 'Nombre:') !!}
    {!! Form::text('nombre', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('calle', 'Calle:') !!}
    {!! Form::text('calle', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('no_ext', 'No. Exterior:') !!}
    {!! Form::text('no_ext', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('no_int', 'No. Interior:') !!}
    {!! Form::text('no_int', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('colonia', 'Colonia:') !!}
    {!! Form::text('colonia', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('tel', 'Teléfono:') !!}
    {!! Form::text('tel', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('principalS', '¿Es el Principal?') !!}<br>
    <input id="principalS" type="checkbox" data-off-color="primary" data-off-text="SI" data-on-color="warning" data-on-text="NO" checked="false" class="form-control switch">
    {!! Form::hidden('principal', 0, ['class' => 'form-control', 'id' => 'principal']) !!}
</div>

<!--- Submit Field --->
<div class="form-group col-sm-12">
     {!! Form::button('Guardar <i class = "glyphicon glyphicon-floppy-save"></i>', ['class' => 'btn btn-success',
        'type'=>'submit']) !!}
        <a class = "btn btn-danger" href = "{!! route('hospitals.index') !!}">
            Cancelar
            <i class = "glyphicon glyphicon-floppy-remove"></i>
        </a>
</div>

<script>
    var hospital = null;
    @if(isset($hospital))
        hospital = {!!$hospital!!};
    @endif
    $(document).on('ready',function () {
        checarPrincipal();

        $('#principalS').on("switchChange.bootstrapSwitch", function(e, data){
            checarPrincipal();
        })

        if(hospital != null){
            console.log('editar hospital');
            if (hospital.principal == '1'){$("#principalS").trigger("click");}
        }

    });

    function checarPrincipal(){
        var principal = ($("#principalS").prop("checked"))? 0:1;
        if (principal == 0){$("#principal").val(0);}else{$("#principal").val(1);}
    }
</script>