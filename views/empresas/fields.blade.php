@include('empresas.script')
<p><i class="fa fa-asterisk"></i> Campos obligatorios</p>
<div class="row">
    <h2>Datos del emisor</h2>
    <!--- Nom_comercial Field --->
    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('nom_comercial', 'Nombre Comercial:') !!}
        {!! Form::text('nom_comercial', null, ['class' => 'form-control']) !!}
    </div>
    <!--- Nombre Field --->
    <div class="form-group col-sm-6 col-lg-4">
        <i class="fa fa-asterisk"></i>{!! Form::label('nombre', 'Nombre o Razón Social:') !!}
        {!! Form::text('nombre', null, ['class' => 'form-control']) !!}
    </div>
    <!--- Giro empresarial Field --->
    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('giro', 'Giro Empresarial:') !!}
        {!! Form::text('giro', null, ['class' => 'form-control']) !!}
    </div>
    <!--- RFC Field --->
    <div class="form-group col-sm-6 col-lg-4">
        <i class="fa fa-asterisk"></i>{!! Form::label('rfc', 'RFC:') !!}
        {!! Form::text('rfc', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('regimen', 'Régimen fiscal:') !!}
        {!! Form::select('regimen',
         array(
            '0' => 'Seleccionar Régimen',
            'Regimen general de ley personas morales' => 'Régimen general de ley personas morales',
            'Regimen simplificado de ley personas morales' => 'Régimen simplificado de ley personas morales',
            'Regimen de arrendamiento' => 'Régimen de arrendamiento',
            'Regimen de las personas morales con fines no lucrativos' => 'Régimen de las personas morales con fines no lucrativos',
            'Regimen de las personas fisicas con actividades empresariales y profesionales' => 'Régimen de las personas fisicas con actividades empresariales y profesionales',
            'Regimen intermedio de las personas fisicas con actividades empresariales y profesionales' => 'Régimen intermedio de las personas fisicas con actividades empresariales y profesionales',
            'Regimen intermedio de las personas fisicas con actividades empresariales' => 'Régimen intermedio de las personas fisicas con actividades empresariales',
            'Contribuyente del regimen de uso o goce temporal de bienes' => 'Contribuyente del régimen de uso o goce temporal de bienes',
            'Regimen de Incorporación Fiscal' => 'Régimen de Incorporación Fiscal'
         ),
         null, ['class' => 'form-control']) !!}
    </div>
    <!--- Telefono Field --->
    <div class="form-group col-sm-6 col-lg-4">
        <i class="fa fa-asterisk"></i>{!! Form::label('telefono', 'Teléfono:') !!}
        {!! Form::text('telefono', null, ['class' => 'form-control']) !!}
    </div>
    <!--- curp Field --->
    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('curp', 'CURP:') !!}
        {!! Form::text('curp', null, ['class' => 'form-control']) !!}
    </div>
    <!--- email Field --->
    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('email', 'Email:') !!}<i class=" info fa fa-info-circle" title="A este correo se enviarán copia de la factura generada y notificaciones."></i>
        {!! Form::email('email', null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="row">
    <h2>Domicilio Fiscal</h2>
    <!--- Calle Field --->
    <div class="form-group col-sm-6 col-lg-4">
        <i class="fa fa-asterisk"></i>{!! Form::label('calle', 'Calle:') !!}
        {!! Form::text('calle', null, ['class' => 'form-control']) !!}
    </div>
    <!--- No. ext Field --->
    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('num_ext', 'No. exterior:') !!}
        {!! Form::text('num_ext', null, ['class' => 'form-control']) !!}
    </div>
    <!--- No. int Field --->
    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('num_int', 'No. interior:') !!}
        {!! Form::text('num_int', null, ['class' => 'form-control']) !!}
    </div>
    <!--- Colonia Field --->
    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('colonia', 'Colonia:') !!}
        {!! Form::text('colonia', null, ['class' => 'form-control']) !!}
    </div>
    <!--- CP Field --->
    <div class="form-group col-sm-6 col-lg-4">
        <i class="fa fa-asterisk"></i>{!! Form::label('codigo_postal', 'CP:') !!}
        {!! Form::text('codigo_postal', null, ['class' => 'form-control']) !!}
    </div>
    <!--- Estado Field --->
    <div class="form-group col-sm-6 col-lg-4">
        <i class="fa fa-asterisk"></i>{!! Form::label('estado', 'Estado:') !!}
        {!! Form::select('estado', $estados, null, ['class' => 'form-control', 'onchange' => 'cargarCiudades(this.value)']) !!}
    </div>
    <!--- Ciudad Field --->
    <div class="form-group col-sm-6 col-lg-4">
        <i class="fa fa-asterisk"></i>{!! Form::label('ciudad', 'Ciudad (Municipio):') !!}
        {!! Form::select('ciudad', array( '0' => 'Seleccione una ciudad' ), null, ['class' => 'form-control']) !!}
    </div>
    <!--- Localidad Field --->
    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('localidad', 'Localidad:') !!}
        {!! Form::text('localidad', null, ['class' => 'form-control']) !!}
    </div>
</div>

<script>
    $(document).on('change', '.btn-file :file', function() {
        var input = $(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });
    $(document).ready( function() {
        $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
            var input = $(this).parents('.input-group').find(':text'),
                    log = numFiles > 1 ? numFiles + ' files selected' : label;
            if( input.length ) {
                input.val(log);
            }else {
                if( log ) alert(log);
            }
        });
    });
</script>

<div class="row">
    <h2>Logo de la empresa</h2>
    <div class="imag"></div>
    <p style="margin-top: 10px;">Archivos de imagen en formato png, jpg o gif.</p>

    <div class="input-group">
                <span class="input-group-btn">
                    <span class="btn btn-primary btn-file">
                        Logo {!!  Form::file('img_logo', ['accept' => '.jpe,.png,.jpeg,.gif'],['multiple']) !!}
                    </span>
                </span>
        {!!  Form::text('img_logo', null,['readonly', 'class'=>'form-control']) !!}
    </div>
</div>
<div class="row esconder" style="margin-top: 10px;">
    <h2>Archivos fiscales</h2>
    <!--- Validadro Field --->
    <div class="form-group col-sm-12">
        <p id='Antesde2'>
            Antes de subir sus CSD compruebe que sean los correctos.<br>
            Si el validador fue positivo favor de ingresarlos al sistema.
        </p>
        <span class = "btn btn-warning" onclick="verPagina()">Validador de archivos</span>
    </div>
        <p>
            Introducir los archivos .key y .cer del CSD (Certificado de Sello Digital) así como su contraseña,
            estos archivos son diferentes a los archivos de la FIEL.
        </p>
    <!--- Certificado Field --->
    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('archivoCer', 'Certificado (archivo .cer):') !!}
        {!! Form::file('archivoCer', ['accept' => '.cer']) !!}
    </div>
    <!--- Llave Field --->
    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('archivoKey', 'Certificado (archivo .key):') !!}
        {!! Form::file('archivoKey', ['accept' => '.key']) !!}
    </div>
    <!--- Pass privado Field --->
    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('claveprivada', 'Contraseña de la llave:') !!}
        {!! Form::text('claveprivada', null, ['class' => 'form-control']) !!}
        <span><b>Sensible a mayusculas</b></span>
    </div>
</div>
<div class="row center esconder">
    <h2>Terminos y condiciones</h2>
    <div class="form-group col-sm-12">
        <i class="fa fa-square-o" id="aceptar"></i>
        Acepto los
        <a onclick="verPDF('tc.pdf')" href="javascript:void(0)" id="linkTC">
            Terminos y Condiciones
        </a>
    </div>
</div>
<!--- Submit Field --->
<div class="form-group col-sm-12" style="margin-top: 10px;">
    {!! Form::button('Guardar <i class = "glyphicon glyphicon-floppy-save"></i>', ['class' => 'btn btn-success',
    /*'type'=>'submit',*/ 'onclick'=>'return validar(this)']) !!}
     <a class = "btn btn-danger" href = "{!! route('empresas.index') !!}">
            Cancelar <i class="glyphicon glyphicon-floppy-remove"></i>
        </a>
</div>

<script>
    $(document).ready(function () {
       $("#aceptar").on("click", function () {
           if($("#aceptar").hasClass('fa-check-square-o')){
               $("#aceptar").removeClass("fa-check-square-o active").addClass("fa-square-o");
           }
           //Si esta desactivado, activalo!
           else{
               $("#aceptar").removeClass("fa-square-o").addClass("fa-check-square-o active");
           }
       })
    });
</script>