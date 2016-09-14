{{--Modal Finalizar Historial --}}
<div class="modal fade" id="modalFinalizar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Finalizar Historial</h4>
            </div>
            <div class="modal-body container-fluid">

                <div class="col-xs-12 col-sm-6">
                    {!! Form::label('fecha_fin', 'Fecha:') !!}
                    <div class = "input-group date">
                        {!! Form::text('fecha_fin', null, ['class' => 'form-control']) !!}
                        <span class = "input-group-addon"> <i class = "glyphicon glyphicon-calendar"></i></span>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6">
                    {!! Form::label('hora_fin', 'Hora:') !!}
                    {!! Form::text('hora_fin', null, ['class' => 'form-control hora_fin','autocomplete' => 'off', 'size' => '10', 'readonly' => '']) !!}
                </div>

                <div class="col-xs-12 col-sm-6">
                    {!! Form::label('causa_fin', 'Causa o motivo:') !!}
                    {!! Form::text('causa_fin', null, ['class' => 'form-control']) !!}
                </div>

                <div class="col-xs-12">
                    {!! Form::label('detalles_fin', 'Detalles:') !!}
                    {!! Form::textarea('detalles_fin', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="modal-footer container-fluid">
                @if($finalizadoDatos->isEmpty())
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <a type="button" class="btn btn-warning pull-right finalizarHistorial" onclick="finalizarHistorial(this)" style="padding-left: 5px">Finalizar</a>
                @else
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
    @if($finalizadoDatos->isEmpty())
        var finalizadoDatos = null;
    @else
        var finalizadoDatos = {!! $finalizadoDatos !!};
    @endif

    $(document).on('ready',function() {
        $('.date #fecha_fin').datepicker({
            format        : "yyyy-mm-dd",
            todayBtn      : "linked",
            language      : "es",
            startDate     : '0d',
            autoclose     : true,
            todayHighlight: true,
            minDate       : 0
        });

        if (finalizadoDatos != null){
            $(".date #fecha_fin").val(finalizadoDatos[0].fecha_fin).attr('disabled','');
            $("#hora_fin").val(finalizadoDatos[0].hora_fin).attr('disabled',true).removeAttr('readonly');
            $("#causa_fin").val(finalizadoDatos[0].causa_fin).attr('disabled','');
            $("#detalles_fin").val(finalizadoDatos[0].detalles_fin).attr('disabled','');
        }

    });

    $('#hora_fin').on( "click", function() {
        $('.time-picker').removeClass('hidden');
        $('.time-picker').css('z-index', '5000');
    });

    jQuery(function() {
        $(".hora_fin").timePicker({
            startTime: '00:00',  //Indica el inicio
            endTime: new Date(0, 0, 0, 23, 59, 0),  //Indica que la fecha de fin
            show24Hours: true, //Formato de fechas AM y PM
            separator:':', //Separador de horas y minutos
            step: 1 //Frecuencia de cada intervalo
        });
    });

    function finalizarHistorial(este){
        var fecha_fin = $('.date #fecha_fin').val();
        var hora_fin = $('#hora_fin').val();
        var causa_fin = $('#causa_fin').val();
        var detalles_fin = $('#detalles_fin').val();
        var id_cliente = {!! $cliente->id !!};
        console.log(fecha_fin);

        if (causa_fin == ""){ return swal("Espere","La causa es necesaria","warning"); }

        $.ajax({
            type: 'POST',
            url: '/dr_basico/servicios/finalizarHistorial',
            data:{
                _token: $('meta[name=csrf-token]').attr('content'),
                fecha_fin: fecha_fin,
                hora_fin: hora_fin,
                causa_fin: causa_fin,
                detalles_fin: detalles_fin,
                id_cliente: id_cliente,
            },success: function(data){
                swal("Finalizado","Historial Cerrado","success");
                window.location.reload();
            },error: function (ajaxContext) {
                swal("Espere","Algo salio mal, reintente de nuevo","warning");
            }
        });
    }
</script>