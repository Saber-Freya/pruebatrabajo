@include('clientes.script')
<div class = "row">
    <div class = "col-xs-6 col-md-3">
        <h1 class = "page-header">Paciente</h1>
    </div>
</div>

<fieldset class="row" id="datosCliente">
    <!--- Nombre Field --->
    <div class="form-group col-sm-6 col-lg-4">
        <i class="fa fa-asterisk"></i>{!! Form::label('nombre', 'Nombre:') !!}
       {!! Form::text('nombre', null, ['class' => 'form-control required', 'maxlength' => 60]) !!}
    </div>

    <!--- Apellido Field --->
    <div class="form-group col-sm-6 col-lg-4">
        <i class="fa fa-asterisk"></i>{!! Form::label('apellido', 'Apellidos:') !!}
        {!! Form::text('apellido', null, ['class' => 'form-control required', 'maxlength' => 60]) !!}
    </div>

    <!--- Calle Field --->
    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('calle', 'Calle:') !!}
        {!! Form::text('calle', null, ['class' => 'form-control', 'maxlength' => 60]) !!}
    </div>

    <!--- Num ext Field --->
    <div class="form-group col-xs-6 col-sm-2 col-lg-2">
        {!! Form::label('num_ext', 'Núm. Ext.:') !!}
        {!! Form::text('num_ext', null, ['class' => 'form-control numero ', 'maxlength' => 10]) !!}
    </div>

    <!---Num INt Field --->
    <div class="form-group col-xs-6 col-sm-2 col-lg-2">
        {!! Form::label('num_int', 'Núm. Int.:') !!}
        {!! Form::text('num_int', null, ['class' => 'form-control', 'maxlength' => 10]) !!}
    </div>

    <!--- Colonia Field --->
    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('col', 'Colonia:') !!}
        {!! Form::text('col', null, ['class' => 'form-control', 'maxlength' => 60]) !!}
    </div>

    <!--- Tel Field --->
    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('tel', 'Teléfono:') !!}
        {!! Form::text('tel', null, ['class' => 'form-control inputTelefono required', 'maxlength' => 10]) !!}
    </div>

    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('sangre', 'Grupo sanguíneo:') !!}
        {!! Form::select('sangre',
        ['0'=>'Seleccione Grupo sanguíneo',
        '1'=>'O-',
        '2'=>'O+',
        '3'=>'A-',
        '4'=>'A+',
        '5'=>'B-',
        '6'=>'B+',
        '7'=>'AB-',
        '8'=>'AB+'],
        null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('sexo', 'Sexo:') !!}
        {!! Form::select('sexo',
        ['0'=>'Seleccione Sexo',
        '1'=>'Masculino',
        '2'=>'Femenino'],
        null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('fecha_nacimiento', 'Fecha Nacimiento:') !!}<i class="fa fa-info-circle" title="Para una rápida búsqueda de la fecha, dar clic en el título del mes, después en el título del año y avanzar con las flechas hacia el año correspondiente y seleccionar."></i>
        <div class = "input-group date">
            {!! Form::text('fecha_nacimiento', null, ['class' => 'form-control']) !!}
            <span class = "input-group-addon">
                <i class = "glyphicon glyphicon-calendar"></i>
            </span>
        </div>
    </div>

    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('etnicidad', 'Etnicidad:') !!}
        {!! Form::text('etnicidad', null, ['class' => 'form-control', 'maxlength' => 60]) !!}
    </div>

    <!--- Foto Field --->
    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('foto', 'Foto: ',['class' => 'imag']) !!}<i class="info fa fa-info-circle" title="Formato: jpg, png o gif. Se recomienda un tamaño de 165x165 px"></i>
        <div class="input-group">
                <span class="input-group-btn">
                    <span class="btn btn-primary btn-file">
                        Foto {!!  Form::file('foto', null,['multiple']) !!}
                    </span>
                </span>
            {!!  Form::text('foto', null,['readonly','class'=>'form-control','id'=>'foto_nom']) !!}
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
                    if( input.length ) {input.val(log);}else {if( log ) alert(log);}
                });
            });
        </script>
    </div>

</fieldset>

<div class="grupo col-xs-12 pad0">
    <div class="col-xs-12 titulo page-header-sub">Correos</div>
    <div class="form-group col-sm-6 col-lg-4">
        {!! Form::label('email', 'Correo:') !!}
        {!! Form::text('email', null, ['class' => 'form-control', 'maxlength' => 60]) !!}
        {!! Form::hidden('cantidad_i', null, ['class' => 'form-control', 'value' => '1']) !!}
    </div>

    <div class="col-sm-1" style="margin-top:25px">
        {{--{!! Form::label('agrega', 'Agregar') !!}--}}
        {!! Form::button('<i class = "fa fa-plus-circle"></i>', ['class' => 'btn btn-success',
        'onclick' => "agregarEmail(this)", 'value' => "Remover Imagen" , 'title' => 'Agregar Correo']) !!}
    </div>

    <div class="col-sm-5 col-lg-7 areas-header table-responsive">
        {!! Form::label('emails', 'Lista de Correos:') !!} <i class="info fa fa-info-circle" title="Lista de Correos, aquí se pueden agregar varios"></i>
        <table class="table table-bordered" id="seccion-emails">
            <thead>
                <tr>
                    <th style="width: 75%;">Correo</th>
                    <th style="width: 25%;">Borrar</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="grupo col-xs-12 pad0">
    <div class="col-xs-12 titulo page-header-sub">¿Ha tenido o tiene ahora? <i class="fa fa-comment-o cursor" title="Abrir o cerrar todos los campos de cometarios" onclick="mostrarCampo('que_de_que')"></i></div>
    {{--<div class="col-xs-12 titulo page-header-sub">Ficha médica</div>--}}
    {{--<div class="col-xs-12" style="font-size: 15px;font-weight: bold;">¿Ha tenido o tiene ahora?</div>--}}
    <div class="form-group col-sm-6 col-md-3" align="center">
        {!! Form::label('asma', 'Asma Bronquial:') !!}<BR>
        <input id="asma" type="checkbox" data-off-text="SI" data-on-text="NO" checked="false" class="form-control switch">
        <i class="fa fa-comment-o cursor" id="globo_asma_coment" title="Agregar comentario" onclick="mostrarCampo('asma_coment')"></i>
    </div>

    <div class="form-group col-sm-6 col-md-3 comentariosHa asma_coment hidden">
        {!! Form::label('asma_coment', 'Comentario Asma Bronquial:') !!}<BR>
        {!! Form::text('asma_coment', null, ['class' => 'form-control','id' => 'asma_coment', 'maxlength' => 225]) !!}
    </div>

    <div class="form-group col-sm-6 col-md-3" align="center">
        {!! Form::label('ulsera', 'Ulcera Gastroduodenal:') !!}<BR>
        <input id="ulsera" type="checkbox" data-off-text="SI" data-on-text="NO" checked="false" class="form-control switch">
        <i class="fa fa-comment-o cursor" id="globo_ulsera_coment" title="Agregar comentario" onclick="mostrarCampo('ulsera_coment')"></i>
    </div>

    <div class="form-group col-sm-6 col-md-3 comentariosHa ulsera_coment hidden">
        {!! Form::label('ulsera_coment', 'Comentario Ulcera:') !!}<BR>
        {!! Form::text('ulsera_coment', null, ['class' => 'form-control','id' => 'ulsera_coment', 'maxlength' => 225]) !!}
    </div>

    <div class="form-group col-sm-6 col-md-3" align="center">
        {!! Form::label('fiebre', 'Fiebre Reumática:') !!}<BR>
        <input id="fiebre" type="checkbox" data-off-text="SI" data-on-text="NO" checked="false" class="form-control switch">
        <i class="fa fa-comment-o cursor" id="globo_fiebre_coment" title="Agregar comentario" onclick="mostrarCampo('fiebre_coment')"></i>
    </div>

    <div class="form-group col-sm-6 col-md-3 comentariosHa fiebre_coment hidden">
        {!! Form::label('fiebre_coment', 'Comentario Fiebre:') !!}<BR>
        {!! Form::text('fiebre_coment', null, ['class' => 'form-control','id' => 'fiebre_coment', 'maxlength' => 225]) !!}
    </div>

    <div class="form-group col-sm-6 col-md-3" align="center">
        {!! Form::label('diabetes', 'Diabetes:') !!}<BR>
        <input id="diabetes" type="checkbox" data-off-text="SI" data-on-text="NO" checked="false" class="form-control switch">
        <i class="fa fa-comment-o cursor" id="globo_diabetes_coment" title="Agregar comentario" onclick="mostrarCampo('diabetes_coment')"></i>
    </div>

    <div class="form-group col-sm-6 col-md-3 comentariosHa diabetes_coment hidden">
        {!! Form::label('diabetes_coment', 'Comentario Diabetes:') !!}<BR>
        {!! Form::text('diabetes_coment', null, ['class' => 'form-control','id' => 'diabetes_coment', 'maxlength' => 225]) !!}
    </div>

    <div class="form-group col-sm-6 col-md-3" align="center">
        {!! Form::label('cardiacas', 'Enfermedades Cardiacas:') !!}<BR>
        <input id="cardiacas" type="checkbox" data-off-text="SI" data-on-text="NO" checked="false" class="form-control switch">
        <i class="fa fa-comment-o cursor" id="globo_cardiacas_coment" title="Agregar comentario" onclick="mostrarCampo('cardiacas_coment')"></i>
    </div>

    <div class="form-group col-sm-6 col-md-3 comentariosHa cardiacas_coment hidden">
        {!! Form::label('cardiacas_coment', 'Comentario Cardiacas:') !!}<BR>
        {!! Form::text('cardiacas_coment', null, ['class' => 'form-control','id' => 'cardiacas_coment', 'maxlength' => 225]) !!}
    </div>

    <div class="form-group col-sm-6 col-md-3" align="center">
        {!! Form::label('convulsiones', 'Convulsiones:') !!}<BR>
        <input id="convulsiones" type="checkbox" data-off-text="SI" data-on-text="NO" checked="false" class="form-control switch">
        <i class="fa fa-comment-o cursor" id="globo_convulsiones_coment" title="Agregar comentario" onclick="mostrarCampo('convulsiones_coment')"></i>
    </div>

    <div class="form-group col-sm-6 col-md-3 comentariosHa convulsiones_coment hidden">
        {!! Form::label('convulsiones_coment', 'Comentario Convulsiones:') !!}<BR>
        {!! Form::text('convulsiones_coment', null, ['class' => 'form-control','id' => 'convulsiones_coment', 'maxlength' => 225]) !!}
    </div>

    <div class="form-group col-sm-6 col-md-3" align="center">
        {!! Form::label('tuberculosis', 'Tuberculosis:') !!}<BR>
        <input id="tuberculosis" type="checkbox" data-off-text="SI" data-on-text="NO" checked="false" class="form-control switch">
        <i class="fa fa-comment-o cursor" id="globo_tuberculosis_coment" title="Agregar comentario" onclick="mostrarCampo('tuberculosis_coment')"></i>
    </div>

    <div class="form-group col-sm-6 col-md-3 comentariosHa tuberculosis_coment hidden">
        {!! Form::label('tuberculosis_coment', 'Comentario Tuberculosis:') !!}<BR>
        {!! Form::text('tuberculosis_coment', null, ['class' => 'form-control','id' => 'tuberculosis_coment', 'maxlength' => 225]) !!}
    </div>

    <div class="form-group col-sm-6 col-md-3" align="center">
        {!! Form::label('mareos', 'Vértigos o Mareos:') !!}<BR>
        <input id="mareos" type="checkbox" data-off-text="SI" data-on-text="NO" checked="false" class="form-control switch">
        <i class="fa fa-comment-o cursor" id="globo_mareos_coment" title="Agregar comentario" onclick="mostrarCampo('mareos_coment')"></i>
    </div>

    <div class="form-group col-sm-6 col-md-3 comentariosHa mareos_coment hidden">
        {!! Form::label('mareos_coment', 'Comentario Vértigos:') !!}<BR>
        {!! Form::text('mareos_coment', null, ['class' => 'form-control','id' => 'mareos_coment', 'maxlength' => 225]) !!}
    </div>

    <div class="form-group col-sm-6 col-md-3" align="center">
        {!! Form::label('dolor_cabeza', 'Dolor de Cabeza Severo:') !!}<BR>
        <input id="dolor_cabeza" type="checkbox" data-off-text="SI" data-on-text="NO" checked="false" class="form-control switch">
        <i class="fa fa-comment-o cursor" id="globo_dolor_cabeza_coment" title="Agregar comentario" onclick="mostrarCampo('dolor_cabeza_coment')"></i>
    </div>

    <div class="form-group col-sm-6 col-md-3 comentariosHa dolor_cabeza_coment hidden">
        {!! Form::label('dolor_cabeza_coment', 'Comentario Dolor de Cabeza:') !!}<BR>
        {!! Form::text('dolor_cabeza_coment', null, ['class' => 'form-control','id' => 'dolor_cabeza_coment', 'maxlength' => 225]) !!}
    </div>

    <div class="form-group col-sm-6 col-md-3" align="center">
        {!! Form::label('emocionales', 'Problemas Emocionales:') !!}<BR>
        <input id="emocionales" type="checkbox" data-off-text="SI" data-on-text="NO" checked="false" class="form-control switch">
        <i class="fa fa-comment-o cursor" id="globo_emocionales_coment" title="Agregar comentario" onclick="mostrarCampo('emocionales_coment')"></i>
    </div>

    <div class="form-group col-sm-6 col-md-3 comentariosHa emocionales_coment hidden">
        {!! Form::label('emocionales_coment', 'Comentario Emocionales:') !!}<BR>
        {!! Form::text('emocionales_coment', null, ['class' => 'form-control','id' => 'emocionales_coment', 'maxlength' => 225]) !!}
    </div>

    <div class="form-group col-sm-6 col-md-3" align="center">
        {!! Form::label('hernias', 'Hernias:') !!}<BR>
        <input id="hernias" type="checkbox" data-off-text="SI" data-on-text="NO" checked="false" class="form-control switch">
        <i class="fa fa-comment-o cursor" id="globo_hernias_coment" title="Agregar comentario" onclick="mostrarCampo('hernias_coment')"></i>
    </div>

    <div class="form-group col-sm-6 col-md-3 comentariosHa hernias_coment hidden">
        {!! Form::label('hernias_coment', 'Comentario Hernias:') !!}<BR>
        {!! Form::text('hernias_coment', null, ['class' => 'form-control','id' => 'hernias_coment', 'maxlength' => 225]) !!}
    </div>

    <div class="form-group col-sm-6 col-md-3" align="center">
        {!! Form::label('arterial', 'Hipertensión Arterial:') !!}<BR>
        <input id="arterial" type="checkbox" data-off-text="SI" data-on-text="NO" checked="false" class="form-control switch">
        <i class="fa fa-comment-o cursor" id="globo_arterial_coment" title="Agregar comentario" onclick="mostrarCampo('arterial_coment')"></i>
    </div>

    <div class="form-group col-sm-6 col-md-3 comentariosHa arterial_coment hidden">
        {!! Form::label('arterial_coment', 'Comentario Hipertensión:') !!}<BR>
        {!! Form::text('arterial_coment', null, ['class' => 'form-control','id' => 'arterial_coment', 'maxlength' => 225]) !!}
    </div>

</div>

<div class="grupo col-xs-12 pad0">
    <div class="col-xs-12 titulo page-header-sub">Contactos de emergencia
        <div id="agregar_contacto" class="btn btn-success pull-right">
            Agregar <i class="glyphicon glyphicon-plus"></i>
        </div>
    </div>
    <div class="col-xs-12 pad0 row-eq-height">
        <div class="col-xs-12">
            <div class="form-group col-sm-6 col-lg-4">
                <i class="fa fa-asterisk"></i>{!! Form::label('nombre_con', 'Nombre:') !!}
                {!! Form::text('nombre_con', null, ['class' => 'form-control', 'maxlength' => 60]) !!}
            </div>
            <div class="form-group col-sm-6 col-lg-4">
                {!! Form::label('parentesco_id', 'Parentesco:') !!}
                {!! Form::select('parentesco_id', $listaParentescos, null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-xs-12 col-sm-6 col-lg-4">
                {!! Form::label('calleE', 'Calle:') !!}
                {!! Form::text('calleE', null, ['class' => 'form-control required', 'maxlength' => 60]) !!}
            </div>
            <div class="form-group col-xs-12 col-sm-6 col-lg-4">
                {!! Form::label('no_extE', 'Núm. Ext.:') !!}
                {!! Form::text('no_extE', null, ['class' => 'form-control numero required', 'maxlength' => 10]) !!}
            </div>
            <div class="form-group col-xs-12 col-sm-6 col-lg-4">
                {!! Form::label('no_intE', 'Núm. Int.:') !!}
                {!! Form::text('no_intE', null, ['class' => 'form-control required', 'maxlength' => 10]) !!}
            </div>
            <div class="form-group col-xs-12 col-sm-6 col-lg-4">
                {!! Form::label('coloniaE', 'Colonia:') !!}
                {!! Form::text('coloniaE', null, ['class' => 'form-control required', 'maxlength' => 60]) !!}
            </div>
            <div class="form-group col-xs-12 col-sm-6 col-lg-4">
                {!! Form::label('cpE', 'Código Postal:') !!}
                {!! Form::text('cpE', null, ['class' => 'form-control inputTelefono required', 'maxlength' => 5]) !!}
            </div>
            <div class="form-group col-sm-6 col-lg-4">
                {!! Form::label('tel_per_con', 'Teléfono:') !!}
                <input type="tel" class="form-control inputTelefono" id="tel_per_con" maxlength="10">
            </div>
            <div class="form-group col-sm-6 col-lg-4">
                {!! Form::label('email_per_con', 'Correo:') !!}
                <input type="email" class="form-control" id="email_per_con" maxlength="60">
            </div>
        </div>
    </div>
    <div id="seccion_contactos" class="col-xs-12 @if(!isset($cliente)) invisible @endif">
        <div class="col-xs-12 titulo">
            Lista de Contactos
        </div>
        <div class="col-xs-12 pad0" style="font-weight: bold">
            <div class="col-sm-2 hidden-xs">Contacto</div>
            <div class="col-sm-1 hidden-xs">Parentesco</div>
            <div class="col-sm-1 hidden-xs">Calle</div>
            <div class="col-sm-1 hidden-xs">Núm. Ext.</div>
            <div class="col-sm-1 hidden-xs">Núm. Int.</div>
            <div class="col-sm-1 hidden-xs">Colonia</div>
            <div class="col-sm-1 hidden-xs">Código Postal</div>
            <div class="col-sm-1 hidden-xs">Teléfono</div>
            <div class="col-sm-2 hidden-xs">Correo</div>
            <div class="col-sm-1 hidden-xs text-right">Acción</div>
        </div>
        @if(isset($cliente))
            @foreach($cliente->contactos as $con)
                <div class="contacto col-xs-12 pad0" style="border-top: solid darkgrey">
                    <div class="col-xs-12 col-sm-2 contacto">{!! $con->nombre !!}</div>
                    <div class="col-xs-12 col-sm-1 parentesco" id="{!! $con->parentesco_id !!}">{!! $con->parentesco !!}</div>
                    <div class="col-xs-12 col-sm-1 calleE">{!! $con->calleE !!}</div>
                    <div class="col-xs-12 col-sm-1 no_intE">{!! $con->no_intE !!}</div>
                    <div class="col-xs-12 col-sm-1 no_extE">{!! $con->no_extE!!}</div>
                    <div class="col-xs-12 col-sm-1 coloniaE">{!! $con->coloniaE !!}</div>
                    <div class="col-xs-12 col-sm-1 cpE">{!! $con->cpE !!}</div>
                    <div class="col-xs-12 col-sm-1 tel_per">{!! $con->telefono_personal !!}</div>
                    <div class="col-xs-12 col-sm-2 email_per">{!! $con->email_personal !!}</div>
                    <div class="col-xs-12 col-sm-1">
                        <div class='minus flotante' onclick='$(this).parent().parent().remove()'><i class='fa fa-times'></i></div>
                        <div class='edit flotante' onclick='editarContacto(this)'><i class='glyphicon glyphicon-edit'></i></div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<div class="titulo col-xs-12">
    &iquest;Requerirá Factura?
    <i id="check-factura" class="fa @if(isset($cliente)) @if($cliente->rfc != "") fa-check-square-o @else  fa-square-o @endif @else fa-square-o @endif" onclick="check(this)" style="cursor: pointer"></i>
</div>

<div class="col-xs-12 pad0 grupo margintop20 invisible" id="seccion-factura">
    <div class="col-sm-6 col-xs-12 titulo margintop10">Datos Facturación <span class="red"></span></div>
    <div class="pregunta col-sm-4 pull-right" align="right">
        &iquest;Usar datos del paciente?
        <i id="check-factura" class="fa fa-square-o" onclick="mismosDatos(this)" style="cursor: pointer"></i>
    </div>
    <div class="datos-facturacion col-xs-12 pad0">
        <div class="form-group col-sm-6 col-lg-4">
            <i class="fa fa-asterisk"></i>{!! Form::label('nom_comercial', 'Nombre Comercial:') !!}
            {!! Form::text('nom_comercial', null, ['class' => 'form-control', 'maxlength' => 60]) !!}
        </div>
        <div class="form-group col-sm-6 col-lg-4">
            <i class="fa fa-asterisk"></i>{!! Form::label('razon_social', 'Razón Social:') !!}
            {!! Form::text('razon_social', null, ['class' => 'form-control', 'maxlength' => 60]) !!}
        </div>
        <div class="form-group col-sm-6 col-lg-4">
            {!! Form::label('aseguradora', 'Aseguradora:') !!}
            {!! Form::text('aseguradora', null, ['class' => 'form-control', 'maxlength'=> 60]) !!}
        </div>
        <div class="form-group col-sm-6 col-lg-4">
            <i class="fa fa-asterisk"></i>{!! Form::label('rfc', 'RFC:') !!}
            {!! Form::text('rfc', null, ['class' => 'form-control', 'maxlength'=> 13]) !!}
        </div>
        <div class="form-group col-sm-6 col-lg-4">
            <i class="fa fa-asterisk"></i>{!! Form::label('metodo_pago', 'Forma de pago:') !!}
            <select id="metodo_pago" name="metodo_pago" class="form-control">
            @foreach($formaPago as $pago)
                <option value="{!! $pago->id !!}">{!! $pago->codigo !!} | {!! $pago->nombre !!}</option>
            @endforeach
            </select>
        </div>
        <div class="form-group col-sm-6 col-lg-4">
            <i class="fa fa-asterisk"></i>{!! Form::label('numCuenta', 'Número de Cuenta:') !!}
            {!! Form::text('numCuenta', null, ['class' => 'form-control numero', 'maxlength'=>18, 'minlength'=>4]) !!}
        </div>
        <div class="form-group col-xs-12 col-sm-6 col-lg-4">
            <i class="fa fa-asterisk"></i>{!! Form::label('emailF', 'Email:') !!}
            {!! Form::text('emailF', null, ['class' => 'form-control','maxlength' => 60]) !!}
        </div>
        <div class="form-group col-sm-6 col-lg-4">
            <i class="fa fa-asterisk"></i>{!! Form::label('calleF', 'Calle:') !!}
            {!! Form::text('calleF', null, ['class' => 'form-control', 'maxlength' => 60]) !!}
        </div>
        <div class="form-group col-sm-6 col-lg-4">
            <i class="fa fa-asterisk"></i>{!! Form::label('colF', 'Colonia:') !!}
            {!! Form::text('colF', null, ['class' => 'form-control', 'maxlength' => 60]) !!}
        </div>
        <div class="form-group col-sm-6 col-lg-4">
            <i class="fa fa-asterisk"></i>{!! Form::label('num_extF', 'Núm. Ext.:') !!}
            {!! Form::text('num_extF', null, ['class' => 'form-control', 'maxlength' => 10]) !!}
        </div>
        <div class="form-group col-sm-6 col-lg-4">
            {!! Form::label('num_intF', 'Núm. Int.:') !!}
            {!! Form::text('num_intF', null, ['class' => 'form-control', 'maxlength' => 10]) !!}
        </div>
        <div class="form-group col-sm-6 col-lg-4">
            <i class="fa fa-asterisk"></i>{!! Form::label('cpF', 'Código Postal:') !!}
            {!! Form::text('cpF', null, ['class' => 'form-control inputTelefono required','maxlength'=>5]) !!}
        </div>

        <div class="form-group col-sm-6 col-lg-4">
            <i class="fa fa-asterisk"></i>{!! Form::label('edoF', 'Estado:') !!}
            <select id="edoF" class="form-control">
                <option value="0">Selecciona Estado</option>
                @foreach($estados as $edo)
                    <option value="{!! $edo->id !!}">{!! $edo->title !!}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-6 col-lg-4">
            <i class="fa fa-asterisk"></i>{!! Form::label('cdF', 'Ciudad:') !!}
            <select name="cdF" id="cdF" class="form-control" readonly="">
            </select>
        </div>

    </div>
</div>

<!--- Submit Field --->
<fileset class="form-group col-sm-12">
    <a onclick="Guardar(this)" class="btn btn-success guardar">
        Guardar <i class = "glyphicon glyphicon-floppy-save"></i>
    </a>
    <a class = "btn btn-danger cancelar" href = "{!! route('clientes.index') !!}">
        Cancelar <i class = "glyphicon glyphicon-floppy-remove"></i>
    </a>
    {{--{!! Form::reset('Limpiar Formulario', ['class' => 'btn btn-default pull-right limpiar']) !!}--}}
</fileset>

<script>
    var clientes = null;
    @if(isset($cliente))
         clientes = {!!$cliente!!};
    @endif
    $(document).on('ready',function(){
        if(clientes != null){
            console.log(clientes);
            $("#edo").val(clientes.edo).trigger("change");

            $(".guardar").attr('dato-id', clientes.id);

            var res = "";
            @for($i=0;$i<count($emails);$i++)

                var email = '{!!$emails[$i]->email!!}';

                res += "<tr id='"+email+"'><td class='email'>" + email + "</td>";
                res += "<td><div class='minus col-xs-1' onclick='quitarEmail(this)'><i class='fa fa-times' title='Quitar solo este E-mail'></i></div></td></tr>";
            @endfor
            $("#seccion-emails").append(res);

            if (clientes.rfc != ""){$("#seccion-factura").removeClass('invisible');}

            console.log(clientes.foto);
            if (clientes.foto == 'undefined'){
                console.log("entro a foto");
                $('#foto_nom').val('');
            }

            if (clientes.asma == '1'){$("#asma").trigger("click");}
            if (clientes.ulsera == '1'){$("#ulsera").trigger("click");}
            if (clientes.fiebre == '1'){$("#fiebre").trigger("click");}
            if (clientes.diabetes == '1'){$("#diabetes").trigger("click");}
            if (clientes.cardiacas == '1'){$("#cardiacas").trigger("click");}
            if (clientes.convulsiones == '1'){$("#convulsiones").trigger("click");}
            if (clientes.tuberculosis == '1'){$("#tuberculosis").trigger("click");}
            if (clientes.mareos == '1'){$("#mareos").trigger("click");}
            if (clientes.dolor_cabeza == '1'){$("#dolor_cabeza").trigger("click");}
            if (clientes.emocionales == '1'){$("#emocionales").trigger("click");}
            if (clientes.hernias == '1'){$("#hernias").trigger("click");}
            if (clientes.arterial == '1'){$("#arterial").trigger("click");}

            if (clientes.asma_coment != ''){
                $('#globo_asma_coment').removeClass('fa-comment-o').addClass('fa-comment').css("color", "#337AB7");
            }
            if (clientes.ulsera_coment != ''){
                $('#globo_ulsera_coment').removeClass('fa-comment-o').addClass('fa-comment').css("color", "#337AB7");
            }
            if (clientes.fiebre_coment != ''){
                $('#globo_fiebre_coment').removeClass('fa-comment-o').addClass('fa-comment').css("color", "#337AB7");
            }
            if (clientes.diabetes_coment != ''){
                $('#globo_diabetes_coment').removeClass('fa-comment-o').addClass('fa-comment').css("color", "#337AB7");
            }
            if (clientes.cardiacas_coment != ''){
                $('#globo_cardiacas_coment').removeClass('fa-comment-o').addClass('fa-comment').css("color", "#337AB7");
            }
            if (clientes.convulsiones_coment != ''){
                $('#globo_convulsiones_coment').removeClass('fa-comment-o').addClass('fa-comment').css("color", "#337AB7");
            }
            if (clientes.tuberculosis_coment != ''){
                $('#globo_tuberculosis_coment').removeClass('fa-comment-o').addClass('fa-comment').css("color", "#337AB7");
            }
            if (clientes.mareos_coment != ''){
                $('#globo_mareos_coment').removeClass('fa-comment-o').addClass('fa-comment').css("color", "#337AB7");
            }
            if (clientes.dolor_cabeza_coment != ''){
                $('#globo_dolor_cabeza_coment').removeClass('fa-comment-o').addClass('fa-comment').css("color", "#337AB7");
            }
            if (clientes.emocionales_coment != ''){
                $('#globo_emocionales_coment').removeClass('fa-comment-o').addClass('fa-comment').css("color", "#337AB7");
            }
            if (clientes.hernias_coment != ''){
                $('#globo_hernias_coment').removeClass('fa-comment-o').addClass('fa-comment').css("color", "#337AB7");
            }
            if (clientes.arterial_coment != ''){
                $('#globo_arterial_coment').removeClass('fa-comment-o').addClass('fa-comment').css("color", "#337AB7");
            }

            if(clientes.factura == 'true'){$("#check-factura").trigger("click");}else{$("#seccion-factura").addClass('invisible');}
            if(clientes.facturacion !== null){
                var facturacion = clientes.facturacion;
                $("#nom_comercial").val(facturacion.nom_comercial);
                $("#razon_social").val(facturacion.razon_social);
                $("#aseguradora").val(facturacion.aseguradora);
                $("#rfc").val(facturacion.rfc);
                $("#metodo_pago").val(facturacion.metodoPago);
                $("#numCuenta").val(facturacion.numCuenta);
                $("#emailF").val(facturacion.email);
                $("#calleF").val(facturacion.calle);
                $("#colF").val(facturacion.col);
                $("#num_extF").val(facturacion.num_ext);
                $("#num_intF").val(facturacion.num_int);
                $("#cpF").val(facturacion.cp);
                $("#edoF").val(facturacion.edo).trigger("change");
            }

        }
    });

    $("#edo").on("change", function(){
        if($(this).val() == 0){
            return;
        }else{
            $.post('/clientes/getCiudadesByEdoId', {
                _token: $('meta[name=csrf-token]').attr('content'),
                id: $(this).val()
            }).done(function (data) {
                if(data){
                    var res = "";
                    for(var i=0; i<data.length; i++){
                        res += "<option value='"+data[i]['id']+"'>"+data[i]['title']+"</option>";
                    }
                    $("#cd").html(res);
                            @if(isset($cliente))
                                var ciudad ="{!! $cliente->cd  !!}";
                    $("#cd").val(ciudad);
                    @endif
                    $("#cd").removeAttr('readonly');
                }
            }).fail(function () {
                swal("Error", "No se pudo conectar con el servidor", "error");
            });
        }
    });

    $("#agregar_contacto").on("click", function () {
        var contacto = $("#nombre_con").val().trim();
        var parentesco_id = $("#parentesco_id option:selected").val();
        var parentesco_nombre = $("#parentesco_id option:selected").text();
        var calleE = $("#calleE").val().trim();
        var no_extE = $("#no_extE").val().trim();
        var no_intE = $("#no_intE").val().trim();
        var coloniaE = $("#coloniaE").val().trim();
        var cpE = $("#cpE").val().trim();
        var tel_per = $("#tel_per_con").val().trim();
        var email_per = $("#email_per_con").val().trim();

        if(!validarNoVacio(contacto)) return swal("Espere", "Es necesario agregar el nombre de contacto", "info");
        /*if(!validarNumericoOrVacio(tel_emp) || !validarNumericoOrVacio(tel_per)) return swal("Espere", "El valor del teléfono debe de contener solo números", "info");*/
        /*if(tel_emp != "" && !validar10Digitos(tel_emp)) return swal("Espere", "Los campos de teléfono deben de tener 10 dígitos", "info");*/
        if(tel_per != "" && !validar10Digitos(tel_per)) return swal("Espere", "Los campos de teléfono deben de tener 10 dígitos", "info");
        /*if(email_emp != "" && !validarEmail(email_emp)) return swal("Espere", "El correo debe de ser un correo valido. Ejemplo: correo@ejemplo.com", "info");*/
        if(email_per != "" && !validarEmail(email_per)) return swal("Espere", "El correo debe de ser un correo valido. Ejemplo: correo@ejemplo.com", "info");

        var res = "<div class='contacto col-xs-12 pad0' style='border-top: solid darkgrey'>";
            res += "<div class='col-xs-12 col-sm-2 contacto'>"+contacto+"</div>";
            res += "<div class='col-xs-12 col-sm-1 parentesco' id="+parentesco_id+">"+parentesco_nombre+"</div>";
            res += "<div class='col-xs-12 col-sm-1 calleE'>"+calleE+"</div>";
            res += "<div class='col-xs-12 col-sm-1 no_extE'>"+no_extE+"</div>";
            res += "<div class='col-xs-12 col-sm-1 no_intE'>"+no_intE+"</div>";
            res += "<div class='col-xs-12 col-sm-1 coloniaE'>"+coloniaE+"</div>";
            res += "<div class='col-xs-12 col-sm-1 cpE'>"+cpE+"</div>";
            res += "<div class='col-xs-12 col-sm-1 tel_per'>"+tel_per+"</div>";
            res += "<div class='col-xs-12 col-sm-2 email_per'>"+email_per+"</div>";
            res += "<div class='col-xs-12 col-sm-1'>";
            res += "<div class='minus flotante' onclick='$(this).parent().parent().remove()'><i class='fa fa-times'></i></div>";
            res += "<div class='edit flotante' onclick='editarContacto(this)'><i class='glyphicon glyphicon-edit'></i></div></div>";
        res += "</div>";

        $("#seccion_contactos").append(res).removeClass('invisible');
        /*$("#seccion_contactos").removeClass('invisible');*/

        limpiarContacto();
    });

    function editarContacto(x){
        var contacto = $(x).parent().siblings('.contacto').html().trim();
        /*var parentesco_nombre = $(x).parent().siblings('.parentesco').html().trim();*/
        var parentesco_id = $(x).parent().siblings('.parentesco').attr('id');
        var calleE = $(x).parent().siblings('.calleE').html().trim();
        var no_extE = $(x).parent().siblings('.no_extE').html().trim();
        var no_intE = $(x).parent().siblings('.no_intE').html().trim();
        var coloniaE = $(x).parent().siblings('.coloniaE').html().trim();
        var cpE = $(x).parent().siblings('.cpE').html().trim();
        var tel_per = $(x).parent().siblings('.tel_per').html().trim();
        var email_per = $(x).parent().siblings('.email_per').html().trim();

        $("#nombre_con").val(contacto);
        $("#parentesco_id").val(parentesco_id);
        $("#calleE").val(calleE);
        $("#no_extE").val(no_extE);
        $("#no_intE").val(no_intE);
        $("#coloniaE").val(coloniaE);
        $("#cpE").val(cpE);
        $("#tel_per_con").val(tel_per);
        $("#email_per_con").val(email_per);

        $(x).parent().parent().remove();
    }

    function limpiarContacto() {
        $("#nombre_con").val("");
        $("#parentesco_id").val(2);
        $("#calleE").val("");
        $("#no_extE").val("");
        $("#no_intE").val("");
        $("#coloniaE").val("");
        $("#cpE").val("");
        $("#tel_per_con").val("");
        $("#email_per_con").val("");
    }

    function check(x){
        //Si esta activado, desactivalo!
        if($(x).hasClass('fa-check-square-o')){
            $(x).removeClass("fa-check-square-o active").addClass("fa-square-o");
            $("#seccion-factura").addClass('invisible');
        }
        //Si esta desactivado, activalo!
        else{
            $(x).removeClass("fa-square-o").addClass("fa-check-square-o active");
            $("#seccion-factura").removeClass('invisible');
        }
    }

    function mismosDatos(x){
            //Si esta activado, desactivalo!
            if($(x).hasClass('fa-check-square-o')){
                $(x).removeClass("fa-check-square-o active").addClass("fa-square-o active");
                $("#seccion-factura input").each(function(){
                    $(this).val("");
                });
                $("#seccion-factura select").each(function(){
                    $(this).val(0);
                });
                $("#seccion-factura input:first").focus();
            }
            //Si esta desactivado, activalo!
            else{
                $(x).removeClass("fa-square-o").addClass("fa-check-square-o active");
                $("#datosCliente input").each(function(){
                    var id = $(this).attr("id");
                    var valor = $(this).val();
                    $("#"+id+"F").val(valor);

                });
                $("#datosCliente select").each(function(){
                    var id = $(this).attr("id");
                    var valor = $(this).val();
                    $("#"+id+"F").val(valor).trigger("change");

                });
                $("#seccion-factura input:first").focus();
            }
        }

</script>