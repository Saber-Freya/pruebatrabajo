@extends('app')@section('content')
<div class = "col-sm-6"><h1 class = "page-header">Bienvenido, {{ Auth::user()->name}}</h1></div>

<div class="col-xs-12 visible-xs">
    <div class="col-xs-12"><i class="default fa fa-circle fa-fw"></i> Por llegar </div>
    <div class="col-xs-12"><i class="azul fa fa-circle fa-fw"></i> Espera </div>
    <div class="col-xs-12"><i class="verde fa fa-circle fa-fw"></i> Consulta </div>
    <div class="col-xs-12"><i class="anaranjado fa fa-circle fa-fw"></i> Finalizada </div>
    <div class="col-xs-12"><i class="rojo fa fa-circle fa-fw"></i> Falta </div>
</div>

<div class = "col-xs-6 col-md-offset-3 col-md-3 " id="sandbox-container" style="z-index: 3;">
    <div class="calendario ajusteCalendario"></div>
</div>

{{--seccion Invisible--}}
<input id="inicioSemana" class="hidden">
<input id="fechaSeleccionada" class="hidden">

<div class = "col-md-9 semana subirSemana" style="z-index: 2;">

    <div class="form-group col-sm-3">
        {!! Form::select('tipoCita', [
            '0'=>'Filtrar tipo de Cita:',
            '1'=>'Consulta',
            '2'=>'Cirugía',
            '3'=>'Todo'
        ], null, ['id' => 'tipoCita','class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-2" align="center">
        <button type="button" class="btn btn-default" onclick="filtroTurno('manana')">Mañana</button>
    </div>

    <div class="form-group col-sm-2" align="center">
        <button type="button" class="btn btn-default" onclick="filtroTurno('tarde')">Tarde</button>
    </div>

    <div class="form-group col-sm-2" align="center">
        <button type="button" class="btn btn-default" onclick="filtroTurno('todo')">Todo</button>
    </div>

    <div class="form-group activarAgenda col-sm-3" align="center">
        <button type="button" class="btn btn-default" onclick="activarAgenda()"> Activar Agenda</button>
    </div>

    <div class="form-group col-sm-3 activarCalendario hidden" align="center">
        <button type="button" class="btn btn-default" onclick="activarCalendario()"> Desactivar Agenda</button>
    </div>

    <div class="col-xs-12 text-center hidden-xs" style="margin-top: 10px;">
        <div class="col-xs-4 col-sm-2"><i class="info fa fa-info-circle pull-left" title="Estos colores significan el estado del la Cita. Es necesario actualizar el estado mediante avanza el progreso de la Cita."></i><i class="default fa fa-circle fa-fw"></i> Por llegar </div>
        <div class="col-xs-4 col-sm-3"><i class="azul fa fa-circle fa-fw"></i> Espera </div>
        <div class="col-xs-4 col-sm-3"><i class="verde fa fa-circle fa-fw"></i> Consulta </div>
        <div class="col-xs-6 col-sm-2"><i class="anaranjado fa fa-circle fa-fw"></i> Finalizada </div>
        <div class="col-xs-6 col-sm-2"><i class="rojo fa fa-circle fa-fw"></i> Falta </div>
    </div>

    @include('web.estructuraSemana')
</div>

<div class = "col-md-3 diaLateral" style="z-index: 1;margin-top: 10px;"></div>

<script>
    $(document).on('ready',function() {

        var arrayFecha_inhabil = checarFechaInhabiles();
        var arrayServicios = buscarFechaServicios();

        // deshabilitar fechas y poner servicios en el calendario derecho
        calendario_derecho(arrayFecha_inhabil,arrayServicios);

        var hoy = new Date();

        var fechaSeleccionada = $.datepicker.formatDate('yy-mm-dd',new Date(hoy));
        $('#fechaSeleccionada').val(fechaSeleccionada);
        reiniciarSemana(hoy);

        $('#sandbox-container .calendario').on('changeDate', function (e) {
            console.log("cambio dia de calendario");
            /*console.log(e);*/
            activarCalendario();
            $('.horaT').css("background-color", "#F0F0F0");
            var fecha = e.date;
            /*console.log(fecha);*/
            var fechaSeleccionada = $.datepicker.formatDate('yy-mm-dd',new Date(fecha));
            $('#fechaSeleccionada').val(fechaSeleccionada);

            reiniciarSemana(fecha);

            var inicioCalendario = $('#inicioSemana').val();
            inicioCalendario = new Date(inicioCalendario);

            agragarClases(inicioCalendario);

            $("th[fecha='"+fechaSeleccionada+"']").trigger('onclick');
            calendarioSemanal(arrayFecha_inhabil);

        });

        var inicioCalendario = $('#inicioSemana').val();
        inicioCalendario = new Date(inicioCalendario);

        agragarClases(inicioCalendario);

        calendarioSemanal(arrayFecha_inhabil);

        var filtroTipoCita = $('#tipoCita').val();
        filtrarTipoCita(filtroTipoCita);

        $('#tipoCita').on('change', function(){
            var tipo = $(this).val();
            filtrarTipoCita(tipo);
        });

        $("th[fecha='"+fechaSeleccionada+"']").trigger('onclick');

        $('#modalConsulta').on('hidden.bs.modal', function (e) {
            var tieneDatos = $('.guardarConsulta').hasClass('hidden');
            if(tieneDatos == true){
                setTimeout("location.href = '/'",0);
            }
        })

    });

    function filtrarTipoCita(tipo){
        if (tipo == 3){tipo = '0';}
        if (tipo == 1){
            console.log('ver consultas');
            $("div[tipoCita='2'],span[tipoCita='2']").addClass('hidden');
            $("div[tipoCita='1'],span[tipoCita='1']").removeClass('hidden');
        }else if (tipo == 2){
            console.log('ver cirugias');
            $("div[tipoCita='1'],span[tipoCita='1']").addClass('hidden');
            $("div[tipoCita='2'],span[tipoCita='2']").removeClass('hidden');
        }else{
            console.log('ver todo');
            $("div[tipoCita='1'],span[tipoCita='1']").removeClass('hidden');
            $("div[tipoCita='2'],span[tipoCita='2']").removeClass('hidden');
        }
    }

    function checarFechaInhabiles(){
        var fecha_inhabil = "";
        var arrayFecha_inhabil = [];
        $.ajax({
            type   : "GET",
            url    : "/disponibles",
            async: false,
            success: function (data) {
                for (var i = 0; i < data.length; i++) {
                    fecha_inhabil += data[i].fecha+',';
                }
                arrayFecha_inhabil = fecha_inhabil.split(',');
                arrayFecha_inhabil = $.grep(arrayFecha_inhabil,function(n){
                    return(n);
                });
                return arrayFecha_inhabil;
            }
        });
        return arrayFecha_inhabil;
    }

    function checarHorarioInhabiles(){
        var horario_inhabil = "";
        var arrayhorario_inhabil = [];
        $.ajax({
            type   : "GET",
            url    : "/inhabiles",
            async: false,
            success: function (data) {
                /*console.log(data);*/
                for (var i = 0; i < data.length; i++) {
                    $("th[dia='"+data[i]+"']").css("background-color", "#BFBABA").removeAttr('onclick').attr('actividad','deshabilitado');
                    $("td[dia='"+data[i]+"']").css("background-color", "#BFBABA").removeAttr('onclick').attr('actividad','deshabilitado');
                }
            }
        });
    }

    function buscarFechaServicios(){
        /*var myArray = ['2016-06-17'];*/
        var servicios = "";
        var arrayServicios = [];
        $.ajax({
            type   : "GET",
            url    : "/servicios/hoy/Delante",
            async: false,
            success: function (data) {
                for (var i = 0; i < data.length; i++) {
                    servicios += data[i].fecha+',';
                }
                arrayServicios = servicios.split(',');
                arrayServicios = $.grep(arrayServicios,function(n){
                    return(n);
                });
                return arrayServicios;
            }
        });
        return arrayServicios;
    }

    function agragarClases(inicioCalendario){
        var d = '';
        /*var dS = '';*/
        var m = '';
        var y = '';
        var nDia = inicioCalendario;
        var fechaSeleccionada2 = $('#fechaSeleccionada').val();

        //Borde del titulo seleccionado de la tabla
        fechaSeleccionada2 = new Date(fechaSeleccionada2);
        var dS = fechaSeleccionada2.getDate()+1;
        /*console.log(dS);*/
        $('.horaT').removeClass('seleccionDia').removeAttr('actividad').removeAttr('onclick');
        /*$('.horaT').removeClass('seleccionDia').removeAttr('actividad').removeAttr('onclick').attr('onclick','agregarCita(this)');*/
        $('.dia').removeClass('seleccionDia');

        //Imprimir dia de la semana
        for (var i = 0; i < 7; i++) {

            d = nDia.getDate();
            m = nDia.getMonth();
            y = nDia.getFullYear();

            var dia = $.datepicker.formatDate('yy-mm-dd',new Date(nDia));
            var fechaSeleccionada = $('#fechaSeleccionada').val();
            var fechaSelectLimpia = fechaSeleccionada.replace(/\-/g, "");

            var horaEstatica = '08:00:00';

            for (var h = 0; h < 28; h++) {
                var fechaLimpia = dia.replace(/\-/g, "");
                var horaLimpia = horaEstatica.replace(/\:/g, "").slice(0, -2);
                var clase = horaLimpia+i;

                $('.'+clase).attr('fecha',dia).attr('hora',horaEstatica)/*.attr('onclick','agregarCita(this)')*/.addClass(horaLimpia+fechaLimpia);

                //Borde del cuerpo seleccionado de la tabla
                $('.'+horaLimpia+fechaSelectLimpia).addClass('seleccionDia');

                var separar = horaEstatica.split(':');
                var horas = separar[0];
                var minutos = separar[1];

                var sumaMinutos = parseFloat(minutos) + 30;
                if (sumaMinutos == 60) {
                    horas = parseFloat(horas) + 1;
                    if (horas <= 9) {
                        horas = '0' + horas;
                    }
                    horaEstatica = horas + ':00:00';
                } else {
                    horaEstatica = horas + ':' + sumaMinutos + ':00';
                }
            }

            nDia = new Date(y, m, d + 1);
            $('.dia'+i).html(d);
            $('.dia0'+i).addClass('dia0'+i+d).attr('fecha',dia).attr('onclick','vistaLateral(this)');
            $('.dia0'+i+dS).addClass('seleccionDia');

        }
        serviciosSemana();
    }

    function reiniciarSemana(fecha) {
        var regresa = '';
        var fecha = new Date(fecha);
        var diaSemana = fecha.getUTCDay();
        if (diaSemana == 1){
            $('#inicioSemana').val(fecha);
        }else{
            regresa = fecha.setDate(fecha.getDate()-1);
            reiniciarSemana(regresa);
        }
    }

    function serviciosSemana() {
        var inicio = $('#inicioSemana').val();
        inicio = $.datepicker.formatDate('yy-mm-dd',new Date(inicio));

        var fin = sacarFecha(inicio,7);
        var horaAjustada = '';
        $('.horaT').html('');
        var indiceAnterior = '';

        $.ajax({
            async: false,
            type: 'GET',
            url: 'servicios/fecha/' + inicio + '/'+ fin,
            success: function (data) {
                /*console.log(data);*/

                if (data != '') {

                    var result = "";

                    for (var i = 0; i < data.length; i++) {

                        var tipo = data[i].tipo;
                        var tipoCita = data[i].tipo;
                        if (tipo == 1) {tipo = 'Consulta';}else{tipo = 'Cirugia';}
                        var fecha = data[i].fecha;
                        var estatus = data[i].estatus;
                        var estado = data[i].estado;
                        var paciente = data[i].paciente;
                        var hora = data[i].hora;
                        var id = data[i].id;
                        var id_cliente = data[i].id_cliente;
                        var id_padecimiento = data[i].id_padecimiento;
                        var padecimiento = data[i].padecimiento;
                        var sintomas = data[i].sintomas;
                        var diagnostico = data[i].diagnostico;

                        estado = obtenerEstado(estado);

                        horaAjustada = ajustarHora(hora);
                        var horaLimpia = horaAjustada.replace(/\:/g, "").slice(0, -2);
                        var fechaLimpia = fecha.replace(/\-/g, "");

                        /*console.log(indiceAnterior+' != '+horaLimpia+fechaLimpia);*/
                        if (indiceAnterior != (horaLimpia+fechaLimpia)){
                            result = '<span id="'+id+'"' +
                                    'tipoCita="'+tipoCita+'" ' +
                                    'class="cursor">' +
                                    '<i class="' + estado + ' fa fa-circle fa-fw" title="' + paciente + ' ' + hora + ' "></i>' +
                                    '</span>';
                        }else {
                            result += '<span id="'+id+'"' +
                                    'tipoCita="'+tipoCita+'" ' +
                                    'class="cursor">' +
                                    '<i class="' + estado + ' fa fa-circle fa-fw" title="' + paciente + ' ' + hora + ' "></i>' +
                                    '</span>';
                        }

                        $('.'+horaLimpia+fechaLimpia).html(result).css("background-color", "").attr('hora',hora).attr('onclick','vistaLateralHora(this)').attr('actividad','si');
                        indiceAnterior = horaLimpia+fechaLimpia;
                    }
                }else {
                    swal("Sin Actividades", "Aun no hay actividades registrados para esta semana.", "info");
                }
            }, error: function (ajaxContext) {
                swal("Espere", "Algo salio mal, reintente de nuevo o comuníquese con su administrador", "warning");
            }
        });
        /*$("td.active").removeClass('active').addClass('activoColor');*/
    }

    function obtenerEstado(estado){
        switch(estado){
            case 0:
                estado = 'default';
                break;
            case 1:
                estado = 'azul';
                break;
            case 2:
                estado = 'verde';
                break;
            case 3:
                estado = 'anaranjado';
                break;
            case 4:
                estado = 'rojo';
                break;
            default:
                estado = 'default'
        }
        return estado;
    }
    function vistaLateral(valor){
        /*var id_servicio = $(valor).attr('id');*/
        var fechaD = $(valor).attr('fecha');

        $.ajax({
            async: false,
            type: 'GET',
            url: 'servicios/fecha/' + fechaD,
            success: function (data) {
                estructuraLateral(data,fechaD);
            }, error: function (ajaxContext) {
                swal("Espere", "Algo salio mal, reintente de nuevo o comuníquese con su administrador", "warning");
            }
        });
    }

    function vistaLateralHora(valor){
        /*var id_servicio = $(valor).attr('id');*/
        var fechaD = $(valor).attr('fecha');
        var horaInicio = $(valor).attr('hora');

        horaInicio = ajustarHora(horaInicio);
        var horaFin = ajustarHoraArriba(horaInicio);

        $.ajax({
            async: false,
            type: 'GET',
            url: 'servicios/fecha/'+fechaD+'/'+ horaInicio+'/'+ horaFin,
            success: function (data) {

                estructuraLateral(data,fechaD)

            }, error: function (ajaxContext) {
                swal("Espere", "Algo salio mal, reintente de nuevo o comuníquese con su administrador", "warning");
            }
        });
    }

    function agregarCita (valor){
        var fechaD = $(valor).attr('fecha');
        var horaInicio = $(valor).attr('hora');

        horaInicio = ajustarHora(horaInicio);
        var horaFin = ajustarHoraArriba(horaInicio);

        window.location.href = 'servicios/cita/'+fechaD+'/'+ horaInicio+'/'+ horaFin;

        /*$.ajax({
            async: false,
            type: 'GET',
            url: 'servicios/cita/'+fechaD+'/'+ horaInicio+'/'+ horaFin,
            error: function (ajaxContext) {
                swal("Espere", "Algo salio mal, reintente de nuevo o comuníquese con su administrador", "warning");
            }
        });*/
    }

    function estructuraLateral(data,fechaD){
        var nombreDia = sacarNombreDia(fechaD);

        var d = extraerDatoFecha(fechaD,d); //Extraer el año,mes o dia de una fecha en formato yyyy-mm-dd (fecha,tipo) tipo: a=año m=mes y d=dia

        var result = "";
        result += '<div class="row"><h3 class="row col-xs-12 text-center">'+nombreDia+' '+d+'</h3></div>';

        if (data != '') {

            for (var i = 0; i < data.length; i++) {

                var tipo = data[i].tipo;
                var tipoCita = data[i].tipo;
                if (tipo == 1) {
                    tipo = 'Consulta';
                } else { tipo = 'Cirugia';}
                var fecha = data[i].fecha;
                var estatus = data[i].estatus;
                var estado = data[i].estado;
                var paciente = data[i].paciente;
                var hora = data[i].hora;
                var id = data[i].id;
                var id_cliente = data[i].id_cliente;
                var id_padecimiento = data[i].id_padecimiento;
                var para = data[i].reagendar;

                var horaLimpia = hora.replace(/\:/g, "");
                var fechaLimpia = fecha.replace(/\-/g, "");
                var dato = fechaLimpia + horaLimpia;

                result += contenido(
                        id, tipo, paciente, estatus, dato, hora, id_cliente, fecha,tipoCita,estado,id_padecimiento,para
                );
            }

        }else{
            result += '<div class="well text-center">Aun no hay actividades registrados para este día.<br>'
        }

        $('.diaLateral').html(result);

        var filtroTipoCita = $('#tipoCita').val();
        filtrarTipoCita(filtroTipoCita);
    }

    function contenido (id,tipo,paciente,estatus,dato,hora,id_cliente,fecha,tipoCita,estado,id_padecimiento,para){
        var result = '';
        var colorEstado = '';
        switch (estado){
            case 0: var colorEstado = "default";
                break;
            case 1: var colorEstado = "azul";
                break;
            case 2: var colorEstado = "verde";
                break;
            case 3: var colorEstado = "anaranjado";
                break;
            case 4: var colorEstado = "rojo";
                break;
            default: colorEstado = "default";
                break;
        }

        var existePreconsultaGuardada = preConsultaGuardada(id_cliente,id);
        /*console.log(existePreconsultaGuardada);*/

        result += '<div class="panel azulPanel" tipoCita="'+tipoCita+'" style="margin-top: 10px;">';

        if (existePreconsultaGuardada == 'noExistePreConsulta'){
            result += '<a onclick="directoConsulta(this)" fecha="'+fecha+'" id="'+id+'" id_padecimiento="'+id_padecimiento+'" id_cliente="'+id_cliente+'" estado="'+estado+'" title="Directo a Consulta." style="color:white;text-decoration: none" class="azulPanel">';
        }else {
            result += '<a href="clientes/historial/'+id_cliente+'/'+id+'" dato="'+dato+'" title="Pasar a Consulta." style="color:white; text-decoration: none" class="azulPanel">';
        }

        result += '<strong>'+hora+' </strong><strong class="pull-right">'+paciente +'</strong>';
        result += '<div class = "panel-heading">';
        result += '<div class = "row">';
        result += '<div class = "text-right"><div class = "huge text-right">' + tipo + '</div><div>';
        result += '</div></a>';
        result += '</div>';
        result += '</div>';
        result += '</div>';
        result += '<div class = "panel-footer">';

        //Botones del footer (ficha lateral)

        //Pago
        if (estatus == 1) {
            result += '<i class="verde fa fa-usd fa-fw" title="Pagado"> </i>';
        } else {
            result += '<a onclick="pagar('+id+')" class="removerDec cursor"> <i class="rojo fa fa-usd fa-fw" title="Pendiente de pago, Marcar como pagado"> </i> </a>';
        }

        //Reagendar
        if (para == 1){
            result += '<a title="Pendiente de Reagendar" href="/servicios/'+id+'/edit" class="removerDec rojo"> <i class="fa fa-refresh fa-fw"> </i></a>';
        } else {
            result += '<a title="Reagendar" href="/servicios/'+id+'/edit" class="removerDec"> <i class="fa fa-refresh fa-fw"> </i></a>';
        }

        //Seguimiento
        if (para == 2){
            if (id_padecimiento != null){
                result += '<a title="Pendiente de Seguimiento" href="/servicios/'+id+'/seguimiento/cita" class="removerDec rojo"> <i class="fa fa-share-square-o fa-fw"> </i></a>';
            }
        } else {
            if (id_padecimiento != null){
                result += '<a title="Seguimiento" href="/servicios/'+id+'/seguimiento/cita" class="removerDec"> <i class="fa fa-share-square-o fa-fw"> </i></a>';
            }
        }


        //Preconsultas
        if (existePreconsultaGuardada == 'noExistePreConsulta'){
            result += '<a onclick="informacionConsulta(this)" fecha="'+fecha+'" id="'+id+'" id_padecimiento="'+id_padecimiento+'" id_cliente="'+id_cliente+'" estado="'+estado+'" title="Valoración pre-consulta." href="#" class="removerDec rojo" data-toggle="modal" data-target="#modalConsulta"> <i class = "fa fa-product-hunt fa-fw"></i></a>';
            //separador1
            /*result += '<span class="separador1"></span>';*/
        }

        //Archivo
        if (id_padecimiento != null) {
            result += '<a onclick="archivos(this)" title="Agregar Archivos" id="' + id + '" id_cliente="' + id_cliente + '" id_padecimiento="' + id_padecimiento + '" class="cursor removerDec" data-toggle="modal" data-target="#modalArchivos"> <i class="fa fa-file-pdf-o fa-fw"> </i></a>';
        }

        //Recordatorio
        result += '<a title="Enviar Recordatorio" id_servicio="'+id+'" id_cliente="'+id_cliente+'" href="#" onclick="crearCorreo(this)"><i class = "fa fa-paper-plane fa-fw"></i></a>';

        //Borrar
        result += '<a href="#" data-slug="servicios" data-id="'+id+'" onclick="return borrarElemento(this)"><i class = "fa fa-times fa-fw" title="Eliminar Cita"> </i></a>';

        //Estado
        result += '<a data-id="'+id+'" onclick="return cambiarEstado(this)" class="cursor"><i class = "'+colorEstado+' fa fa-circle fa-fw" title="Cambiar Estado del progreso de la Cita"> </i></a>';

        /********************************************* Separador del menu lateral *************************************/
        @if(Entrust::can('atender_consulta'))
            //ficha del paciente
            result += '<a href="clientes/historial/'+id_cliente+'" class="pull-right removerDec"> <i class="fa fa-heartbeat fa-fw" title="Ficha del Paciente"> </i> </a>';
            //receta o prerarar cirugia
            if (tipo != 'Consulta') {
                //si no es consulta se supone que es cirugia y puede preparar cirugia
                result += '<a href="/cirugias/'+id+'/preparar" title="Preparar Cirugía" class="removerDec pull-right"> <i class="fa fa-user-md fa-fw"> </i></a>';
            }else{
                //Receta si es consulta se puede crear la receta
                result += '<a onclick="receta(this)" id_servicio="'+id+'" id_cliente="'+id_cliente+'" paciente="'+paciente+'" fecha="'+fecha+'" title="Crear Receta" href="#" class="pull-right removerDec" data-toggle="modal" data-target="#modalReceta"> <i class="fa fa-pencil-square-o fa-fw"> </i></a>';
                //Consulta
                if (existePreconsultaGuardada != 'noExistePreConsulta'){
                    result += '<a onclick="directoConsulta(this)" fecha="'+fecha+'" id="'+id+'" id_padecimiento="'+id_padecimiento+'" id_cliente="'+id_cliente+'" estado="'+estado+'" title="Directo a Consulta." class="pull-right cursor"><i class = "fa fa-product-hunt fa-fw"></i></a>';
                    //separador2
                    /*result += '<span class="separador2"></span>';*/
                }
            }
        @endif
        //Alerta reagendar
        /*result += '<a title="Enviar alerta para reagendar esta Cita" id_servicio="'+id+'" onclick="reagendarAlerta(this)" class="removerDec cursor"> <i class="pull-right fa fa-refresh fa-fw" style="color: rgb(180, 6, 6) !important;"> </i></a>';*/

        result += '<div class = "clearfix"></div>';
        result += '</div>';
        result += '</div>';
        return result;
    }

    //Crea calendario superior derecho y deshabilita fechas fechas registradas como inhabiles
    function calendario_derecho(arrayFecha_inhabil,arrayServicios) {
        $('#sandbox-container .calendario').datepicker({
            format	: "yyyy-mm-dd",
            language: "es",
            todayBtn: "linked",
            startDate: '0d',
            todayHighlight: true,
            autoclose: true,
            datesDisabled: arrayFecha_inhabil,
        });

        //Marca fechas con servicios calendario superior derecho * comentado porque no funciona al dar clik sobre uno de estos dias
        /*$('#sandbox-container .calendario').datepicker('setDate', arrayServicios);*/

        /*$("th.today").css("background-color", "#EEEEEE");
        $("td.active").removeClass('active').addClass('activoColor');*/

    }

    function calendarioSemanal(arrayFecha_inhabil) {
        /*$('.horaT').removeAttr('actividad');*/

        //deshabilitar fechas registradas como inhabiles en calendario semanal
        $('.dia').css("background-color", "");
        $.each(arrayFecha_inhabil, function( index, value ) {
            /*alert( index + ": " + value );*/
            $("th[fecha='"+value+"']").css("background-color", "#BFBABA").removeAttr('onclick').attr('actividad','deshabilitado');
            $("td[fecha='"+value+"']").css("background-color", "#BFBABA").removeAttr('onclick').attr('actividad','deshabilitado');
        });

        checarHorarioInhabiles();

        //deshabilitar hora y fecha de este momento hacia atras
        var hoy = new Date();
        var fechatd = '';
        var hora = '';

        $('.horaT').each( function( index) {
            hora = $(this).attr('hora');
            hora = restaMin(hora);

            fechatd = $( this ).attr('fecha');
            fechatd = fechatd+' '+hora;
            fechatd = darFormatoFecha(fechatd);
            fechatd = new Date(fechatd);
            /*console.log(fechatd +' < '+ hoy);*/

            if ( fechatd < hoy ){
                $(this).attr('actividad','deshabilitado');
            }
        });
    }

    function sacarNombreDia(fecha){
        fecha = new Date(fecha);
        var diaSemana = fecha.getUTCDay();

        switch (diaSemana){
            case 0: var diadelaSemana = "Domingo";
                return diadelaSemana;
                break;
            case 1: var diadelaSemana = "Lunes";
                return diadelaSemana;
                break;
            case 2: var diadelaSemana = "Martes";
                return diadelaSemana;
                break;
            case 3: var diadelaSemana = "Miércoles";
                return diadelaSemana;
                break;
            case 4: var diadelaSemana = "Jueves";
                return diadelaSemana;
                break;
            case 5: var diadelaSemana = "Viernes";
                return diadelaSemana;
                break;
            case 6: var diadelaSemana = "Sábado";
                return diadelaSemana;
                break;
            default: var diadelaSemana = "No se pudo obtener";
                return diadelaSemana;
                break;
        }
    }

    function extraerDatoFecha(fecha,tipo){
        var separar = fecha.split('-');
        var ano = separar[0];
        var mes = separar[1];
        var dia = separar[2];
        var dato = '';

        if (tipo == 'a'){
            dato = ano;
        } else if (tipo == 'm'){
            dato = mes;
        } else {
            dato = dia;
        }
        return dato;
    }

    function sacarFecha(fechaInicio,cantidad){
        endDate = new Date(fechaInicio);
        endDate.setMonth(endDate.getMonth());//puede cambiar por dia=Date o año=FullYear si es año completo, Year si solo son 2 digitos.
        endDate.setDate(endDate.getDate()+parseInt(cantidad));

        /*console.log(endDate);*/
        endDate.setDate(endDate.getDate());
        endDateString = endDate.getFullYear() + '-'
                + ('0' + (endDate.getMonth()+1)).slice(-2) + '-'
                + ('0' + endDate.getDate()).slice(-2);
        /*console.log(endDateString);*/
        return endDateString;
    }

    function ajustarHora(horaAajustar){
        var separar = horaAajustar.split(':');
        var horas = separar[0];
        var minutos = separar[1];
        var horaAjustada = '';

        if (minutos >= '00' && minutos <= '29'){
            minutos = '00';
        }else{
            minutos = '30';
        }
            horaAjustada = horas + ':' + minutos + ':00';
        return horaAjustada;
    }

    function restaMin(horaAajustar){
        var separar = horaAajustar.split(':');
        var horas = separar[0];
        var minutos = separar[1];
        var horaAjustada = '';

        if (minutos == '00'){
            /*horas = parseInt(horas)+1;*/
            minutos = '30';
        }else{
            horas = parseInt(horas)+1;
            minutos = '00';
        }
        horaAjustada = horas + ':' + minutos + ':00';
        return horaAjustada;
    }

    function ajustarHoraArriba(horaAajustar){
        var separar = horaAajustar.split(':');
        var horas = separar[0];
        var minutos = separar[1];
        var horaAjustada = '';

        if (minutos >= '00' && minutos <= '29'){
            minutos = '30';
        }else{
            minutos = '00';
            horas = parseFloat(horas) + 1;
        }
            horaAjustada = horas + ':' + minutos + ':00';
        return horaAjustada;
    }

    function filtroTurno(turno){
        /*console.log(turno);*/
        if(turno == 'manana') {
            $('.pm').addClass('hidden');
            $('.am').removeClass('hidden');
        }else if (turno == 'tarde'){
            $('.pm').removeClass('hidden');
            $('.am').addClass('hidden');
        }else{
            $('.pm').removeClass('hidden');
            $('.am').removeClass('hidden');
        }
    }

    function cambiarEstado(este){
        var id_servicio = $(este).attr('data-id');
        var inputOptions = new Promise(function(resolve) {
            resolve({
                '0': 'Por Llegar',
                '1': 'Espera',
                '2': 'Consulta',
                '3': 'Finalizada',
                '4': 'Falta'
            });
        });

        swal({
            title: 'Seleccione Estado',
            input: 'radio',
            inputOptions: inputOptions,
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            cancelButtonClass: 'textoNegro',
            cancelButtonColor: "#E0E0E0",
            confirmButtonText: "Cambiar",
            confirmButtonColor: "#449D44",
            closeOnConfirm: false,
            inputValidator: function(result) {
                return new Promise(function(resolve, reject) {
                    if (result) {
                        resolve();
                    } else {
                        reject('Necesita seleccionar una de las Opciones');
                    }
                });
            }
        }).then(function(result) {
            $.ajax({
                async: false,
                type: 'GET',
                url: '/servicios/cambioEstado/'+id_servicio+'/'+result,
                success: function () {

                    var inicioCalendario = $('#inicioSemana').val();
                    inicioCalendario = new Date(inicioCalendario);
                    agragarClases(inicioCalendario)

                    var fechaSeleccionada = $('#fechaSeleccionada').val();
                    $("th[fecha='"+fechaSeleccionada+"']").trigger('onclick');

                }, error: function (ajaxContext) {
                    swal("Espere", "Algo salio mal, reintente de nuevo o comuníquese con su administrador", "warning");
                }
            });
        });
    }

    function activarAgenda(){

        $('div.activarAgenda').addClass('hidden');
        $('div.activarCalendario').removeClass('hidden');

        $('.horaT').children('span.agregarCitaMas').remove();
        $('.horaT').attr('onclick','agregarCita(this)');

        $('.horaT').append('<span class="cursor agregarCitaMas pull-right"><i class="fa fa-plus fa-fw" title="Agregar Cita"></i></span>');

        /*$("td[actividad='deshabilitado']").children('span.agregarCitaMas').remove();*/
        $("td[actividad='deshabilitado']").find('span.agregarCitaMas').remove();
        $("td[actividad='deshabilitado']").removeAttr('onclick');

    }

    function activarCalendario(){

        $('div.activarAgenda').removeClass('hidden');
        $('div.activarCalendario').addClass('hidden');

        /*$('.horaT').children('span.agregarCitaMas').remove();*/
        $('.horaT').find('span.agregarCitaMas').remove();

        $("td[actividad='si']").attr('onclick','vistaLateralHora(this)');
    }

    function informacionConsulta(elemento){

        var id_cliente = $(elemento).attr('id_cliente');
        var id_padecimiento = $(elemento).attr('id_padecimiento');
        var id_servicio = $(elemento).attr('id');
        var fecha = $(elemento).attr('fecha');
        var estado = $(elemento).attr('estado');

        cargarPadecimiento(id_cliente,id_padecimiento);

        if(estado == 3){
            swal({
                title: 'Finalizada',
                text: "Esta Cita esta Finalizada",
                type: 'warning',
                showCancelButton: true,
                cancelButtonText: "Cancelar",
                cancelButtonClass: 'textoNegro',
                cancelButtonColor: "#E0E0E0",
                confirmButtonText: "Quiero Continuar",
                confirmButtonColor: "#449D44"
            }).then(function() {

            }, function(dismiss) {
                // dismiss can be 'cancel', 'overlay', 'close', 'timer'
                if (dismiss === 'cancel') {
                    window.location.reload();
                }
            })
        }

        $('#fechaRegreso').val(fecha);
        $('.guardarConsulta').attr('id_cliente', id_cliente);
        $('.guardarConsulta').attr('id_servicio',id_servicio);
        $('.pasarConsulta').attr('id_servicio',id_servicio);
        $('.historialCliente').attr('href','clientes/historial/'+id_cliente);
        $('.pasarConsulta').attr('href','clientes/historial/'+id_cliente+'/'+id_servicio);
        $('.guardarConsulta').removeClass('hidden');
        $('.pasarConsulta').addClass('hidden');

        $.ajax({
            type: 'Get',
            url: '/servicios/datos/ultimaVisita/'+id_cliente,
            async: false,
            success: function(data){

                if (data != ''){
                    $('.ultimaVicita').html('Valoración pre-consulta. '+ "<i class='info fa fa-info-circle' title='Datos de la última Cita. Es recomendable actualizarlos.'></i>");
                    $('#sintomas').val(data.sintomas);
                    $('#temperatura').val(data.temperatura);
                    $('#presion').val(data.presion);
                    $('#glucosa').val(data.glucosa);
                    $('#peso').val(data.peso);
                    $('#estatura').val(data.estatura);

                    if(data.sangre == '0' || data.sexo == '0'){
                        swal("Espere","Faltan Datos del Paciente, favor de actualizarlos en la seccion de Alta de Paciente","warning");
                    }
                    if(data.id_servicio != id_servicio){
                        swal("Datos de la última Cita","Es recomendable actualizarlos","warning");
                    }
                }else{
                    $('.ultimaVicita').html('Valoración pre-consulta.');
                    $('#sintomas').val('');
                    $('#temperatura').val('');
                    $('#presion').val('');
                    $('#glucosa').val('');
                    $('#peso').val('');
                    $('#estatura').val('');

                    swal("Espere","Paciente Nuevo, favor de llenar todos los datos","warning");
                }

                if(data.id_servicio == id_servicio){
                    $('.guardarConsulta').addClass('hidden');
                    $('.pasarConsulta').removeClass('hidden');
                }
            },error: function (ajaxContext) {
                swal("Espere","Algo salio mal, reintente de nuevo","warning");
            }
        });
    }

    function directoConsulta(elemento){

        var id_cliente = $(elemento).attr('id_cliente');
        var id_clienteFin = $(elemento).attr('id_cliente');
        var id_padecimiento = $(elemento).attr('id_padecimiento');
        var id_servicio = $(elemento).attr('id');
        var fecha = $(elemento).attr('fecha');
        var estado = $(elemento).attr('estado');

        cargarPadecimiento(id_cliente,id_padecimiento);

        $('#fechaRegreso').val(fecha);
        $('.guardarConsulta').attr('id_cliente', id_cliente);
        $('.guardarConsulta').attr('id_servicio',id_servicio);
        $('.pasarConsulta').attr('id_servicio',id_servicio);
        $('.historialCliente').attr('href','clientes/historial/'+id_cliente);
        $('.pasarConsulta').attr('href','clientes/historial/'+id_cliente+'/'+id_servicio);
        $('.guardarConsulta').removeClass('hidden');
        $('.pasarConsulta').addClass('hidden');

        $.ajax({
            type: 'Get',
            url: '/servicios/datos/ultimaVisita/'+id_cliente,
            async: false,
            success: function(data){

                if (data != ''){
                    $('#sintomas').val(data.sintomas);
                    $('#temperatura').val(data.temperatura);
                    $('#presion').val(data.presion);
                    $('#glucosa').val(data.glucosa);
                    $('#peso').val(data.peso);
                    $('#estatura').val(data.estatura);

                    var sintomas = data.sintomas;
                    var temperatura = data.temperatura;
                    var presion = data.presion;
                    var glucosa = data.glucosa;
                    var peso = data.peso;
                    var estatura = data.estatura;
                    var fechaRegreso = $('#fechaRegreso').val();

                    $.ajax({
                        type: 'POST',
                        url: '/guardarInfoConsulta/'+id_servicio,
                        data:{
                            _token: $('meta[name=csrf-token]').attr('content'),
                            id_cliente: id_cliente,
                            sintomas: sintomas,
                            temperatura: temperatura,
                            presion: presion,
                            glucosa: glucosa,
                            peso: peso,
                            estatura: estatura,
                            id_padecimiento:id_padecimiento
                        },
                        success: function(){
                            var url = '"'+'/clientes/historial/'+id_cliente+'/'+id_servicio+'"';
                            setTimeout("location.href = "+url,0);

                        },error: function (ajaxContext) {
                            swal("Espere","Algo salio mal, reintente de nuevo","warning");
                        }
                    });

                }else{
                    $('.ultimaVicita').html('Valoración pre-consulta.');
                    $('#sintomas').val('');
                    $('#temperatura').val('');
                    $('#presion').val('');
                    $('#glucosa').val('');
                    $('#peso').val('');
                    $('#estatura').val('');

                    swal("Espere","Paciente Nuevo, favor de llenar todos los datos","warning");
                }
            },error: function (ajaxContext) {
                swal("Espere","Algo salio mal, reintente de nuevo","warning");
            }
        });
    }

    function cargarPadecimiento(id_cliente,id_padecimiento){
        $('.id_padecimiento').html("");
        $.ajax({
            type: 'GET',
            url: '/padecimientosCliente/'+id_cliente,
            success: function(data){
                if (data == ''){
                    $('.clasePadecimiento').addClass('hidden');
                    $('#id_padecimiento').val('0');
                }else{
                    $('.clasePadecimiento').removeClass('hidden');
                    var res = '<option value="0">Seleccionar Padecimiento</option>';
                    for(var i = 0; i < data.length; i++) {
                        res += '<option value="' + data[i].id + '">' + data[i].padecimiento + '</option>';
                    }
                    $('.id_padecimiento').html(res).val(id_padecimiento);
                }
            },error: function (ajaxContext) {
                swal("Espere","Algo salio mal, reintente de nuevo","warning");
            }
        });
    }

    function cargarPadecimientoA(id_cliente,id_padecimiento){
        $('.id_padecimiento').html("");
        $.ajax({
            type: 'GET',
            url: '/padecimientosCliente/'+id_cliente,
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

    function pagar(id){
        $.ajax({
            type: 'POST',
            url: '/servicios/pagar/'+id,
            data:{
                _token: $('meta[name=csrf-token]').attr('content'),
            },
            success: function(data){
                swal("Recibo pagado","","success");
                window.location.reload();
            },error: function (ajaxContext) {
                swal("Espere","Algo salio mal, reintente de nuevo","warning");
            }
        });
    }

    function GuardarConsulta(elemento){
        var id_cliente = $(elemento).attr('id_cliente');
        var id_servicio = $(elemento).attr('id_servicio');
        var sintomas = $('#sintomas').val();
        var temperatura = $('#temperatura').val();
        var presion = $('#presion').val();
        var glucosa = $('#glucosa').val();
        var peso = $('#peso').val();
        var estatura = $('#estatura').val();
        var id_padecimiento = $("#id_padecimiento option:selected").val();
        var fechaRegreso = $('#fechaRegreso').val();

        $.ajax({
            type: 'POST',
            url: '/guardarInfoConsulta/'+id_servicio,
            data:{
                _token: $('meta[name=csrf-token]').attr('content'),
                id_cliente: id_cliente,
                sintomas: sintomas,
                temperatura: temperatura,
                presion: presion,
                glucosa: glucosa,
                peso: peso,
                estatura: estatura,
                id_padecimiento:id_padecimiento
            },
            success: function(data){
                swal("Guardado","","success");
                /*setTimeout("location.href = '/'",0);*/
                $('.guardarConsulta').addClass('hidden');
                $('.pasarConsulta').removeClass('hidden');
            },error: function (ajaxContext) {
                swal("Espere","Algo salio mal, reintente de nuevo","warning");
            }
        });

        var inicioCalendario = $('#inicioSemana').val();
        inicioCalendario = new Date(inicioCalendario);
        agragarClases(inicioCalendario)
    }

    function preConsultaGuardada(id_cliente,id_servicio){
        var respuesta = '';
        $.ajax({
            type: 'Get',
            url: '/servicios/datos/ultimaVisita/'+id_cliente,
            async: false,
            success: function(data){
                if (data != ''){
                    if(data.id_servicio == id_servicio){
                        respuesta = 'existePreConsulta';
                    }else{
                        respuesta = 'noExistePreConsulta';
                    }
                }else{
                    respuesta = 'noExistePreConsulta';
                }
                return respuesta;
            }
        });
        return respuesta;
    }

    function archivos(elemento){
        $('#titulo').val("");
        var id_servicio = $(elemento).attr('id');
        var id_cliente = $(elemento).attr('id_cliente');
        var id_padecimiento = $(elemento).attr('id_padecimiento');
        $('#archivo').addClass('guardarArchivo').attr('id', id_servicio);
        cargarPadecimientoA(id_cliente,id_padecimiento);
    }

    function reagendarAlerta(elemento){
        var id_servicio = $(elemento).attr('id_servicio');
        $.ajax({
            type: 'GET',
            url: '/reagendarAlerta/'+id_servicio,
            success: function(data){
                swal("Enviada","Se envió alerta para reagendar el servicio","success");
                window.setTimeout(function(){location.reload()},500)
            },error: function (ajaxContext) {
                swal("Espere","Algo salio mal, reintente de nuevo","warning");
            }
        });
    }

    function receta(este){

        var hoy = new Date();
        var fecha = $.datepicker.formatDate('yy-mm-dd',new Date(hoy));

        var paciente = $(este).attr('paciente');
        var id_servicio = $(este).attr('id_servicio');
        var id_cliente = $(este).attr('id_cliente');
        $('#receta').val('');
        $('.paciente').html(paciente);
        $('.fecha_receta').html(fecha);
        $('.guardarReceta').attr('id_servicio',id_servicio);
        $('.guardarReceta').attr('id_cliente',id_cliente);
        $('.guardarReceta').attr('paciente',paciente);
        $('.guardarReceta').attr('fecha',fecha);
    }

    function GuardarReceta(este){
        var id_servicio = $(este).attr('id_servicio');
        var paciente = $(este).attr('paciente');
        var fecha = $(este).attr('fecha');
        var id_cliente = $(este).attr('id_cliente');
        var receta = $('#receta').val();
        receta = receta.replace(/\r?\n/g, "<br>");

        if (receta == ""){
            return swal("Receta Vacia","Favor de ingresar la informacion de la Receta","warning");
        }

        $.ajax({
            type: 'POST',
            url: '/dr_simple/servicios/receta',
            data:{
                _token: $('meta[name=csrf-token]').attr('content'),
                id_servicio: id_servicio,
                receta: receta,
                paciente: paciente,
                fecha: fecha,
                id_cliente: id_cliente,
            },success: function(data){
                var result = '';
                result += '<div style="max-height: 25cm">';
                result += '<div class = "row" style="margin-top: 2.5cm;"></div>';
                result += '<div style="margin-left: 1cm;margin-top: 4px;">'+paciente+'</div>';
                result += '<div style="margin-left: 13cm;margin-top: -16px;">'+fecha+'</div>';
                result += '<div class = "row" style="margin-top: 0.5cm;">'+receta+'</div>';
                result += '</div>';

                newWin= window.open("");
                newWin.document.write(result);
                if (!! navigator.userAgent.match(/Trident/gi)) {
                    newWin.document.execCommand('print', false, null);
                } else {
                    newWin.print();
                }
                newWin.close();

                $('#modalReceta').modal('toggle');
                swal("Mandado a imprimir","","success");
                $('#receta').val('');
            },error: function (ajaxContext) {
                swal("Espere","Algo salio mal, reintente de nuevo","warning");
            }
        });
    }

    function darFormatoFecha(fechaYhora){
        var a = fechaYhora.split(" ");
        var d = a[0].split("-");
        var t = a[1].split(":");
        var formateada = new Date(d[0],(d[1]-1),d[2],t[0],t[1],t[2]);
        return formateada;
    }

</script>
{{-- Modal Citas--}}
<div class="modal fade" id="modalCita" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg tamanoModal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Agregar Cita</h4>
            </div>
            {{--<div class="modal-body container-fluid">
                @include('servicios.fields')
            </div>--}}
            <div class="modal-footer container-fluid">
                <a type="button" class="btn btn-success pull-right guardarCita" onclick="GuardarCita(this)">Guardar</a>
            </div>
        </div>
    </div>
</div>
{{--Modal Informacion --}}
<div class="modal fade" id="modalConsulta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg tamanoModal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title ultimaVicita" id="myModalLabel"><i class = "fa fa-product-hunt"></i> Valoración pre-consulta.</h4>
            </div>
            <div class="modal-body container-fluid">
                <fieldset class="informacion">

                    <div class="form-group col-xs-12 col-sm-6">
                        <div class="form-group col-xs-12">
                        {!! Form::label('sintomas', 'Sintomas:') !!}
                        </div>
                        <div class="form-group col-xs-12">
                        {!! Form::textarea('sintomas', null, ['class' => 'form-control','rows'=>'5']) !!}
                        {!! Form::hidden('fechaRegreso', null, ['class' => 'form-control','id' => 'fechaRegreso']) !!}
                        </div>
                        <div class="form-group col-sm-6 col-lg-4 clasePadecimiento">
                            {!! Form::label('id_padecimiento', 'Padecimiento:') !!}
                            <select class = "form-control id_padecimiento" name = "id_padecimiento" id = "id_padecimiento"></select>
                        </div>
                    </div>

                    <div class="form-group col-xs-12 col-sm-6">
                        <div class="form-group col-xs-12">
                            {!! Form::label('datos', 'Datos médicos:') !!}
                        </div>
                        <div class="form-group col-xs-12">
                            <div class="form-group col-md-6">
                                {!! Form::label('temperatura', 'Temperatura:') !!}
                                {!! Form::text('temperatura', null, ['id'=>'temperatura','class'=>'form-control','placeholder'=>'Temperatura']) !!}
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('presion', 'Presion:') !!}
                                {!! Form::text('presion', null, ['id'=>'presion','class'=>'form-control','placeholder'=>'Presión']) !!}
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('glucosa', 'Glucosa:') !!}
                                {!! Form::text('glucosa', null, ['id'=>'glucosa','class'=>'form-control','placeholder'=>'Glucosa']) !!}
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('peso', 'Peso:') !!}
                                {!! Form::text('peso', null, ['id'=>'peso','class'=>'form-control','placeholder'=>'Peso']) !!}
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('estatura', 'Estatura:') !!}
                                {!! Form::text('estatura', null, ['id'=>'estatura','class'=>'form-control','placeholder'=>'Estatura']) !!}
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="modal-footer container-fluid">
                <a type="button" class="btn btn-success pull-right guardarConsulta" onclick="GuardarConsulta(this)">Guardar <i class = "glyphicon glyphicon-floppy-save"></i></a>
                @if(Entrust::can('atender_consulta'))
                <a type="button" class="btn btn-success pull-right pasarConsulta hidden">Pasar a Consulta <i class = "fa fa-arrow-circle-right"></i></a>
                @endif
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

 {{--Modal Receta --}}
<div class="modal fade" id="modalReceta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Receta</h4>
            </div>
            <div class="modal-body container-fluid">
                <div class="col-xs-12 col-sm-6">
                    {!! Form::label('paciente', 'Paciente:') !!}
                    <div class="paciente"></div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    {!! Form::label('fecha_receta', 'Fecha:') !!}
                    <div class="fecha_receta"></div>
                </div>
                <div class="col-xs-12">
                    {!! Form::label('receta', 'Receta:') !!}
                    {!! Form::textarea('receta', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="modal-footer container-fluid">
                <a type="button" class="btn btn-success pull-right guardarReceta" onclick="GuardarReceta(this)">Crear Receta</a>
            </div>
        </div>
    </div>
</div>
@endsection







