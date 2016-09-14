<div class="row col-xs-12">

    <div class = "col-xs-12 grupo consulta">
        <div class="col-xs-12 page-header-sub"><i class="fa fa-user-md"></i><strong> Consulta</strong></div>
        <div class="row col-xs-12">
            <div class="form-group col-md-3">
                {!! Form::label('padecimiento', 'Padecimiento Nuevo: ') !!}
                <i class="fa fa-info-circle" title="Dejar vacío este campo si hay seguimiento al padecimiento. Nota: Si se rellena este campo, se sustituirá por el padecimiento que esté  seleccionado en el campo de 'Seguimiento de Padecimiento'"></i>
                {!! Form::text('padecimiento', null, ['class'=>'form-control']) !!}
            </div>
            <div class="form-group col-md-3 @if(!count($padecimientoSelect)) hidden @endif">
                {!! Form::label('padecimientoSelect', 'Seguimiento de Padecimiento:') !!}
                {!! Form::select('padecimientoSelect', $padecimientoSelect, $padecimientoC->id_pade, ['class' => 'form-control']) !!}
            </div>
            {{--<div class="form-group col-md-4">
                {!! Form::label('diagnostico', 'Diagnostico:') !!}
                @if(isset($consulta))
                    {!! Form::text('diagnostico', $consulta->diagnostico, ['class'=>'form-control']) !!}
                @else
                    {!! Form::text('diagnostico', null, ['class'=>'form-control']) !!}
                @endif
            </div>--}}
            <div class="form-group col-md-2" style="margin-top:25px">
                <a type="button" class="btn btn-success guardarEstudio" id_cliente="{!! $cliente->id !!}"
                   data-id="{!! $cliente->id_servicio !!}" data-toggle="modal" data-target="#modalEstudio">Agregar Estudio
                    <i class = "glyphicon glyphicon-plus"></i>
                </a>
            </div>
            <div class="form-group col-xs-12">
                {!! Form::label('observaciones', 'Observaciones:') !!}
                @if(isset($consulta))
                    {!! Form::textarea('observaciones', $consulta->observaciones, ['class'=>'form-control','rows'=>'6']) !!}
                @else
                    {!! Form::textarea('observaciones', null, ['class' => 'form-control','rows'=>'6']) !!}
                @endif
            </div>
        </div>
        <div class="footerConsulta">
            @if(isset($consulta))
                <a type="button" class="btn btn-warning pull-right finalizarConsulta" id_cliente="{!! $cliente->id !!}"
                   data-id="{!! $cliente->id_servicio !!}" onclick="return finalizarConsulta(this)">Finalizar
                    <i class = "fa fa-circle fa-fw"></i>
                </a>
                <a type="button" class="btn btn-success pull-right actualizarConsulta" style="margin-right: 5px;" id_cliente="{!! $cliente->id !!}"
                   id_servicio="{!! $cliente->id_servicio !!}" id_preconsulta="{!! $preconsulta->id !!}"
                   onclick="almacenarConsulta(this)">Actualizar
                    <i class = "glyphicon glyphicon-floppy-save"></i>
                </a>
            @else
                <a type="button" class="btn btn-success pull-right almacenarConsulta" id_cliente="{!! $cliente->id !!}"
                   id_servicio="{!! $cliente->id_servicio !!}" id_preconsulta="{!! $preconsulta->id !!}"
                   onclick="almacenarConsulta(this)">Guardar <i class = "glyphicon glyphicon-floppy-save"></i>
                </a>
            @endif

        </div>
    </div>

    <div class = "col-xs-12 grupo preconsulta hidden">
        <div class="col-xs-12 page-header-sub"><i class = "fa fa-product-hunt">
            </i><strong> Valoración pre-consulta</strong>
        </div>
        <div class="row col-xs-12">
            <div class="form-group col-xs-12 col-sm-6">
                <div class="form-group col-xs-12">
                    {!! Form::label('sintomas', 'Sintomas:') !!}
                </div>
                <div class="form-group col-xs-12">
                    {!! Form::textarea('sintomas', $preconsulta->sintomas, ['class' => 'form-control','rows'=>'5']) !!}
                </div>
            </div>

            <div class="form-group col-xs-12 col-sm-6">
                <div class="form-group col-xs-12">
                    {!! Form::label('datos', 'Datos médicos:') !!}
                </div>
                <div class="form-group col-xs-12">
                    <div class="form-group col-md-6">
                        {!! Form::label('temperatura', 'Temperatura:') !!}
                        {!! Form::text('temperatura', $preconsulta->temperatura, ['id'=>'temperatura','class'=>'form-control','placeholder'=>'Temperatura']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('presion', 'Presion:') !!}
                        {!! Form::text('presion', $preconsulta->presion, ['id'=>'presion','class'=>'form-control','placeholder'=>'Presión']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('glucosa', 'Glucosa:') !!}
                        {!! Form::text('glucosa', $preconsulta->glucosa, ['id'=>'glucosa','class'=>'form-control','placeholder'=>'Glucosa']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('peso', 'Peso:') !!}
                        {!! Form::text('peso', $preconsulta->peso, ['id'=>'peso','class'=>'form-control','placeholder'=>'Peso']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('estatura', 'Estatura:') !!}
                        {!! Form::text('estatura', $preconsulta->estatura, ['id'=>'estatura','class'=>'form-control','placeholder'=>'Estatura']) !!}
                        {!! Form::hidden('inicioConsulta', $preconsulta->inicioConsulta, ['id'=>'inicioConsulta']) !!}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
{{--Modal Archivos--}}
<div class="modal fade" id="modalArchivos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg tamanoModal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Agregar Archivos</h4>
            </div>
            <div class="modal-body container-fluid">
                @include('documentos.documentos')
            </div>
            <div class="modal-footer container-fluid">
                <a type="button" class="btn btn-success pull-right guardarArchivos hidden" onclick="GuardarArchivos(this)">Guardar</a>
            </div>
        </div>
    </div>
</div>
@include('modals.estudio')
<script>
    function almacenarConsulta(este){
        var padecimiento = $('#padecimiento').val();
        var id_padecimiento = $('#padecimientoSelect').val();

        var estudios = [];
        var estudio = {};
        $("#seccion-estudios tr").each(function(){
            var control_id = $(this).attr("control_id");
            if(control_id!="" && control_id!=undefined){
                estudio = { control_id:control_id, };
                estudios.push(estudio);
            }
        });

        if (estudios == ''){estudios = 'vacio';}

        $.ajax({
            type: 'POST',
            url: '/dr_basico/servicios/almacenar/consulta',
            data:{
                _token: $('meta[name=csrf-token]').attr('content'),
                //preconsulta
                sintomas : $('#sintomas').val(),
                temperatura : $('#temperatura').val(),
                presion : $('#presion').val(),
                glucosa : $('#glucosa').val(),
                peso : $('#peso').val(),
                estatura : $('#estatura').val(),
                //consulta
                id_cliente: $(este).attr('id_cliente'),
                id_servicio: $(este).attr('id_servicio'),
                padecimiento: padecimiento,
                id_padecimiento: id_padecimiento,
                diagnostico : $('#diagnostico').val(),
                observaciones : $('#observaciones').val(),
                id_preconsulta: $(este).attr('id_preconsulta'),
                //estudios,
                estudios:estudios
            },
            success: function(data){
                swal("Guardado","","success");
                window.location.reload();
            },error: function (ajaxContext) {
                swal("Espere","Algo salio mal, reintente de nuevo","warning");
            }
        });
    }

    function finalizarConsulta(este) {
        var id_servicio = $(este).attr('data-id');
        var inicioConsulta = $('#inicioConsulta').val();
        var id_cliente = $(este).attr('id_cliente');
        $.ajax({
            type: 'POST',
            url: '/dr_basico/servicios/cambioEstado/'+id_servicio+'/3',
            data:{
                _token: $('meta[name=csrf-token]').attr('content'),
                inicioConsulta:inicioConsulta,
                id_cliente:id_cliente
            },success: function () {
                swal("Finalizada","","success");
                setTimeout("location.href = '/dr_basico'",0);
            },error: function (ajaxContext) {
                swal("Espere", "Algo salio mal, reintente de nuevo o comuníquese con su administrador", "warning");
            }
        });
    }

    function archivos(elemento){
        $('#titulo').val("");
        var id_servicio = $(elemento).attr('id');
        var id_cliente = $(elemento).attr('id_cliente');
        var id_padecimiento = $(elemento).attr('id_padecimiento');
        $('#archivo').addClass('guardarArchivo').attr('id', id_servicio);
        console.log(id_servicio);
        console.log(id_cliente);
        console.log(id_padecimiento);
        cargarPadecimientoA(id_cliente,id_padecimiento);
    }

    function cargarPadecimientoA(id_cliente,id_padecimiento){
        $('.id_padecimiento').html("");
        $.ajax({
            type: 'GET',
            url: '/dr_basico/padecimientosCliente/'+id_cliente,
            success: function(data){
                if (data == ''){
                    $('.clasePadecimiento').addClass('hidden');
                    $('#id_padecimientoA').val('0');
                }else{
                    $('.clasePadecimiento').removeClass('hidden');
                    var res = '<option value="0">Seleccionar Padecimiento</option>';
                    for(var i = 0; i < data.length; i++) {
                        res += '<option value="' + data[i].id + '">' + data[i].padecimiento + '</option>';
                    }
                    $('.id_padecimientoA').html(res).val(id_padecimiento);
                }

            },error: function (ajaxContext) {
                swal("Espere","Algo salio mal, reintente de nuevo","warning");
            }
        });
    }
</script>