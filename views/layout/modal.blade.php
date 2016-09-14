<?php
//-----  Aqui es donde llamamos a las funciones del Repositorio
$local = 1;
$perc  = 0;
$total = 0;
$diskPerc = 0;
$diskTotal = 0;
// -- Seccion R A M  ----- //
if($local==0){
    $mem = \App\Libraries\Repositories\UsuariosRepository::getRamSpace();
    $total = $mem['MemTotal']/1000;
    $libre = ($mem['MemFree']+$mem['Buffers']+$mem['Cached'])/1000;
    $used = $total - $libre;
    $perc = round(($used*100)/$total, 2);

    //---- Fin seccion R A M -------//

    // -- Seccion D I S C O  ----- //
    //   -  En $diskTotal se obtiene el total del server,
    //   -  si es un server compartido es necesario colocar los GB Asignados del Cpanel
    $diskTotal = \App\Libraries\Repositories\UsuariosRepository::getDiskSpace()/1000000000;
    $diskFree = \App\Libraries\Repositories\UsuariosRepository::getDiskSpaceFree()/1000000000;

    //   -  En $diskUsed es necesario colocar la direccion de la carpeta "LARAVEL"
    //   -  y la de Public tambien.
    $diskUsed = \App\Libraries\Repositories\UsuariosRepository::getDiskSpaceUsed('../../pvproduccion')/1000000000;
    $diskUsed = $diskUsed + \App\Libraries\Repositories\UsuariosRepository::getDiskSpaceUsed('.')/1000000000;
    //dump($diskUsed);
    //---- Fin seccion D I S C O -------//

    ///$diskUsed = $diskTotal - $diskFree;
    //dump($diskUsed);
    $diskPerc = round(($diskUsed*100)/$diskTotal, 2);
}
?>

{{--Inhábiles--}}
<div class="modal fade" id="modalDisponibilidad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i  class = "fa fa-calendar"></i> Fechas Inhábiles</h4>
            </div>
            <div class="modal-body">
                <div class="row" align="center">

                    <div class="col-xs-12">
                        <div class="" style="z-index: 1;">
                            {!! Form::label('multiFechas', 'Seleccione Fechas:') !!}<i class="fa fa-info-circle" title="Se deshabilitan los días seleccionados. Si se requiere deshabilitar todos los días de la semana, ejemplo: todos los Domingos, entonces entrar a la sección de Altas -> Horarios y borrar los horarios dados de alta para ese día."></i>
                            <div class = "input-group multiFechas">
                                {!! Form::hidden('multiFechasT', null, ['class' => 'form-control','id' => 'multiFechasT']) !!}
                            </div>
                        </div>
                        <div class="col-xs-12" id="fechas"></div>
                    </div>
                    <div class="col-xs-12">
                            <div class="btn btn-success" onclick="guardarFechas()">Guardar Fechas</div>
                    </div>

                    <div class = "col-xs-12 " style="margin-top: 15px; z-index: 2;">
                        <button class="btn btn-default BTNfechasDisponibles" onclick="disponibles()">Ver Fechas Inhábiles</button>
                        <button class="btn btn-default ocultarFechas hidden"> Ocultar Fechas Inhábiles </button>
                    </div>
                    <div class="col-xs-12 fechasDisponibles"></div>
                </div>
            </div>
            <div class="modal-footer container-fluid">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

{{--Reportes--}}
<div class = "modal fade" id = "Reportes" tabindex = "-1" role = "dialog" aria-labelledby = "myModalLabel"
     aria-hidden = "true">
    <div class = "modal-dialog modal-lg">
        <div class = "modal-content">
            <div class = "modal-header">
                <button type = "button" class = "close" data-dismiss = "modal" aria-label = "Close">
                    <span aria-hidden = "true">&times;</span>
                </button>
                <h4 class = "modal-title"><i class="fa fa-h-square" aria-hidden="true"></i> Reporte</h4>
            </div>
            <div class = "modal-body">
                <div class="row">
                    <div class = "panel">
                        <div class="panel-body">

                            <div class="form-group col-sm-6 col-lg-4">
                                {!! Form::label('fecha_inicio', 'Fecha Inicio:') !!}
                                <input name="fecha_inicio" type="text" id="fecha_inicio" class="date form-control">
                            </div>

                            <div class="form-group col-sm-6 col-lg-4">
                                {!! Form::label('fecha_fin', 'Fecha Fin:') !!}
                                <input name="fecha_fin" type="text" id="fecha_fin" class="date form-control">
                            </div>
                            <div class = "col-xs-12 col-md-3" style="margin-top: 23px;">
                                <button class="btn btn-success center-block" onclick="excel()">
                                    Generar Excel
                                </button>
                            </div>
                        </div>
                        <div class = "col-xs-12">
                            <br>
                            Seleccionar Fechas del Reporte
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--Modal Campanita de cambio --}}
<div class="modal fade" id="modalCampanita" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-bell"></i>&nbsp;Alertas</h4>
            </div>
            <div class="modal-body container-fluid">
                @if(Entrust::can('ver_servicios'))
                    <div class="row col-xs-12">
                        <div class="col-sm-4" align="center">
                            <a href = "{{ url('/servicios/pendientes/todo') }}" class="btn btn-default boton_pendiente">Pendientes de Pago</a>
                            {{--<a href = "{{ url('/cuentas_por_cobrar') }}" class="btn btn-default boton_pendiente">Pendientes de Pago</a>--}}
                        </div>
                        <div class="col-sm-4" align="center">
                            <a href = "{{ url('/servicios/inicio/hoy') }}" class="btn btn-default boton_citas">Citas de hoy</a>
                        </div>
                        <div class="col-sm-4" align="center">
                            <a onclick="reagendarLista(this)" class="btn btn-default boton_reagendar">Reagendar y Seguimientos</a>
                        </div>
                    </div>

                    <fileset>
                        <div class="table-responsive margentop50">
                            <div class="tablaContenido"></div>
                        </div>
                    </fileset>
                @endif

                <div class="col-xs-12" style="font-weight: bold;"><i class="fa fa-hdd-o"></i> Recursos</div>
                <div class="col-xs-12 grupo">
                    <div class="col-xs-2">RAM:</div>
                    <div class="progress">
                        <div class="progress-bar col-xs-10" role="progressbar" aria-valuenow="{!! $perc !!}"
                             aria-valuemin="0" aria-valuemax="100" style="width: {!! $perc !!}%;">
                            {!! $perc !!}%
                        </div>
                        Total: {!! round($total,2) !!} MB
                    </div>


                    <div class="col-xs-2">Espacio en Disco:</div>
                    <div class="progress">
                        <div class="progress-bar col-xs-10" role="progressbar" aria-valuenow="{!! $diskPerc !!}"
                             aria-valuemin="0" aria-valuemax="100" style="width: {!! $diskPerc !!}%;">
                            {!! $diskPerc !!}%
                        </div>
                        Total: {!! round($diskTotal,2) !!} GB
                    </div>
                </div>
            </div>
            <div class="modal-footer container-fluid">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>

<script>
$(document).on('ready',function() {
    $('.date').datepicker({
        dateFormat  : "yyyy-mm-dd",
        todayBtn    : "linked",
        changeMonth : true,
        changeYear  : true,
        language    : "es",
        autoclose   : true,
        todayHighlight: true
    });

    $('.multiFechas').datepicker({
        multidate: true,
        format  : "yyyy-mm-dd",
        todayBtn    : "linked",
        changeMonth : true,
        changeYear  : true,
        startDate   : '0d',
        language    : "es",
        todayHighlight: true
    });

    /*$('.hora #hora').clockpicker({
        autoclose : true,
        defaultTime : ""
    });*/
    $('.multiFechas').on('changeDate', function (e) {
        var fecha = e.dates;
        console.log(fecha);
        var fecha_inhabil ="";

        for (var i = 0; i < fecha.length; i++) {
            fecha_inhabil += $.datepicker.formatDate('yy-mm-dd',new Date(fecha[i]))+',';
        }

        /*var fechaSeleccionadaH = $.datepicker.formatDate('yy-mm-dd',new Date(fecha));*/
        console.log(fecha_inhabil);
        $('#multiFechasT').val(fecha_inhabil);

    });


});

//Ocultar y mostrar fechas disponibles
$('.BTNfechasDisponibles').click(function(){
    $(this).addClass('hidden');
    $('.ocultarFechas').removeClass('hidden');
    $('.fechasDisponibles').removeClass('hidden');
});

$('.ocultarFechas').click(function(){
    $(this).addClass('hidden');
    $('.BTNfechasDisponibles').removeClass('hidden');
    $('.fechasDisponibles').addClass('hidden');
});

//Ocultar fechas para asignar
$('.ocultarFechasAsignar').click(function(){
    $(this).addClass('hidden');
    $('.FechasAsignar').removeClass('hidden');
    $('#fechas').addClass('hidden');;
});

function verFechas() {
    //mostrar fechas para asignar
    $('.FechasAsignar').addClass('hidden');
    $('#fechas').removeClass('hidden');
    $('.ocultarFechasAsignar').removeClass('hidden');

    var fecha_inicio = $("#fecha-inicial").datepicker('getDate');
    var fecha_final = $("#fecha-final").datepicker('getDate');

    console.log(fecha_inicio);
    console.log(fecha_final);

    if (fecha_inicio == null || fecha_final == null) {
        swal("Espere", "Es necesario fecha de inicio y de fin", "warning");
        return;
    }

    $('.btn-guardarFechas').addClass('show').removeClass('invisible');

        if(!fecha_inicio || !fecha_final)
        return;
    var dias = 0;
    if (fecha_inicio && fecha_final) {
        dias = Math.floor((fecha_final.getTime() - fecha_inicio.getTime()) / 86400000);
    }

    var suma_dias = dias+1;

    var s= "";
    for(var d = 1; d <= suma_dias; d++){
        s += "<div class='col-sm-4 col-xs-12 fechis fecha"+d+"'></div>";
    }
    $("#fechas").html(s);

    for(var c = 1; c <= suma_dias; c++){
        $(".fecha"+c).datepicker({
            dateFormat  : "yy-mm-dd",
            todayBtn    : "linked",
            changeMonth : true,
            changeYear  : true,
            language    : "es",
            autoclose   : true,
            todayHighlight: true
        }).datepicker('setDate', fecha_inicio);
        fecha_inicio.setDate(fecha_inicio.getDate()+1);
    }
}

function guardarFechas() {
    $('.btn-guardarFechas').addClass('invisible').removeClass('show');
    waitingDialog.show('Guardando..', {dialogSize: 'sm', progressType: 'warning'});
    var nuevas =  $('#multiFechasT').val();
    console.log(nuevas);
    var fechas = nuevas.split(',');
    fechas = $.grep(fechas,function(n){
        return(n);
    });
    console.log(fechas);

    $.ajax({
        type: 'POST',
        url: '/dr_basico/guardarFechas',
        data:{
            _token: $('meta[name=csrf-token]').attr('content'),
            fechas: fechas,
        },
        success: function(data){
            waitingDialog.hide();
            swal("Guardado","","success");
            window.location.reload(3000);
        },error: function (ajaxContext){
            waitingDialog.hide();
            swal("Espere","Algo salio mal, reintente de nuevo","warning");
        }
    });
}

function excel() {
    var inicio = $('#fecha_inicio').val().replace(/\//g,'-');
    var final = $('#fecha_fin').val().replace(/\//g,'-');
    console.log(final);
    if(inicio!='' && final!=''){
        $.ajax({
            type: 'GET',
            url: '/reporte/excel/'+inicio+'/'+final,
            data:{
                _token: $('meta[name=csrf-token]').attr('content'),
            },
            success: function(data){
                /*$(window.document.body).html(data);*/
                window.open('/reporte/excel/'+inicio+'/'+final);
            },error: function (ajaxContext){
                swal("Espere","No se encontraron cirugias entre estas fechas","warning");
            }
        })
    }else{
        swal('Faltan Datos', 'Es necesario Fecha de Inicio y de Final.', 'warning');
    }
}

function borrarFechas(este) {
    var fecha = $(este).attr('fecha');
    console.log(fecha);
    $.ajax({
        type: 'POST',
        url: '/dr_basico/borrarFecha/'+fecha,
        data:{
            _token: $('meta[name=csrf-token]').attr('content'),
        },
        success: function(data){
            waitingDialog.hide();
            swal("Borrado","","success");
            disponibles();
            /*window.location.reload();*/
        },error: function (ajaxContext){
            waitingDialog.hide();
            swal("Espere","Algo salio mal, reintente de nuevo","warning");
        }
    });
}
</script>