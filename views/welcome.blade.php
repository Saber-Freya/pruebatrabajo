@extends('app')@section('content')

<div class="col-xs-12 visible-xs">
    <div class="col-xs-6"><i class="default fa fa-circle fa-fw"></i> Por llegar </div>
    <div class="col-xs-6"><i class="azul fa fa-circle fa-fw"></i> Espera </div>
    <div class="col-xs-6"><i class="verde fa fa-circle fa-fw"></i> Consulta </div>
    <div class="col-xs-6"><i class="anaranjado fa fa-circle fa-fw"></i> Finalizada </div>
    <div class="col-xs-6"><i class="rojo fa fa-circle fa-fw"></i> Falta </div>
</div>

<div class = "col-xs-12 col-sm-4 col-md-offset-9 col-md-3 " id="sandbox-container" style="z-index: 3;" align='center'>
    <div class="calendario"></div>
</div>

{{--seccion Invisible--}}
<input id="inicioSemana" class="hidden">
<input id="fechaSeleccionada" class="hidden">

<div class = "col-md-9 semana subirSemana" style="z-index: 2;">

    <div class="form-group col-sm-6 col-sm-offset-1 col-md-3 col-md-offset-0">
        {!! Form::select('tipoCita', [
            '0'=>'Filtrar tipo de Cita:',
            '1'=>'Consulta',
            '2'=>'Cirugía',
            '3'=>'Todo'
        ], null, ['id' => 'tipoCita','class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-6 col-sm-offset-1 col-md-2 col-md-offset-0" align="center">
        <button type="button" class="btn btn-default botonManana" onclick="filtroTurno('manana')">Mañana</button>
    </div>

    <div class="form-group col-sm-6 col-sm-offset-1 col-md-2 col-md-offset-0" align="center">
        <button type="button" class="btn btn-default botonTarde" onclick="filtroTurno('tarde')">Tarde</button>
    </div>

    <div class="form-group col-sm-6 col-sm-offset-1 col-md-2 col-md-offset-0" align="center">
        <button type="button" class="btn btn-default botonTodo botonActivo" onclick="filtroTurno('todo')">Todo</button>
    </div>

    <div class="form-group col-sm-6 col-sm-offset-1 col-md-3 col-md-offset-0 activarAgenda" align="center">
        @if(Entrust::can('crear_servicios'))
            <button type="button" class="btn btn-default botonActiva" onclick="activarAgenda()"> Activar Agenda</button>
        @endif
    </div>

    <div class="form-group col-sm-6 col-sm-offset-1 col-md-3 col-md-offset-0 activarCalendario hidden" align="center">
        <button type="button" class="btn btn-default botonDesactiva" onclick="activarCalendario()"> Desactivar Agenda</button>
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

@if(Entrust::can('ver_servicios'))
    <div class = "col-md-3 diaLateral" style="z-index: 1;margin-top: 10px;"></div>
@endif
<script>
    $(document).on('ready',function() {

        var arrayFecha_inhabil = checarFechaInhabiles();
        //var arrayServicios = buscarFechaServicios();

        // deshabilitar fechas y poner servicios en el calendario derecho
        calendario_derecho(arrayFecha_inhabil/*,arrayServicios*/);

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
                window.location.reload();
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
        //Calendario de la derecha
        var fecha_inhabil = "";
        var arrayFecha_inhabil = [];
        $.ajax({
            type   : "GET",
            url    : "/dr_basico/inhabilesD",
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
        $.ajax({
            type   : "GET",
            url    : "/dr_basico/inhabilesH",
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
        var servicios = "";
        var arrayServicios = [];
        $.ajax({
            type   : "GET",
            url    : "/dr_basico/servicios/hoy/Delante",
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

                $('.'+clase).attr('fecha',dia).attr('hora',horaEstatica).addClass(horaLimpia+fechaLimpia);

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
                                    'class="cursor" col-xs-1>' +
                                    '<i class="' + estado + ' fa fa-circle fa-fw" title="' + paciente + ' ' + hora + ' "></i>' +
                                    '</span>';
                        }else {
                            result += '<span id="'+id+'"' +
                                    'tipoCita="'+tipoCita+'" ' +
                                    'class="cursor col-xs-1">' +
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
    }

    function obtenerEstado(estado){
        switch(estado){
            case '0':
                estado = 'default';
                break;
            case '1':
                estado = 'azul';
                break;
            case '2':
                estado = 'verde';
                break;
            case '3':
                estado = 'anaranjado';
                break;
            case '4':
                estado = 'rojo';
                break;
            default:
                estado = 'default'
        }
        return estado;
    }

    function vistaLateral(valor){
        var fechaD = $(valor).attr('fecha');

        $.ajax({
            async: false,
            type: 'GET',
            url: '/dr_basico/servicios/fecha/' + fechaD,
            success: function (data) {
                estructuraLateral(data,fechaD);
            }, error: function (ajaxContext) {
                swal("Espere", "Algo salio mal, reintente de nuevo o comuníquese con su administrador", "warning");
            }
        });
    }

    function vistaLateralHora(valor){
        var fechaD = $(valor).attr('fecha');
        var horaInicio = $(valor).attr('hora');

        horaInicio = ajustarHora(horaInicio);
        var horaFin = ajustarHoraArriba(horaInicio);
        horaFin = menosUnMin(horaFin);

        $.ajax({
            async: false,
            type: 'GET',
            url: '/dr_basico/servicios/fecha/'+fechaD+'/'+ horaInicio+'/'+ horaFin,
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
    }

    function estructuraLateral(data,fechaD){
        var nombreDia = sacarNombreDia(fechaD);

        var d = extraerDatoFecha(fechaD,d); //Extraer el año,mes o dia de una fecha en formato yyyy-mm-dd (fecha,tipo) tipo: a=año m=mes y d=dia

        var result = "";
        result += '<div class="row"><h3 class="row col-xs-12 text-center">'+nombreDia+' '+d+'</h3></div>';
        console.log(data);
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
            case '0': colorEstado = "default";
                break;
            case '1': colorEstado = "azul";
                break;
            case '2': colorEstado = "verde";
                break;
            case '3': colorEstado = "anaranjado";
                break;
            case '4': colorEstado = "rojo";
                break;
            default: colorEstado = "default";
                break;
        }

        var existePreconsultaGuardada = preConsultaGuardada(id_cliente,id);
        var existeCirugiaGuardada = cirugiaGuardada(id);
        console.log(existeCirugiaGuardada);

        result += '<div class="panel azulPanel" tipoCita="'+tipoCita+'" style="color:white;margin-top: 10px;">';

        @if(Entrust::can('atender_consulta'))
            if (existePreconsultaGuardada == 'noExistePreConsulta'){
                if(tipo == 'Consulta') {
                    result += '<a onclick="directoConsulta(this)" fecha="'+fecha+'" id="'+id+'" id_padecimiento="'+id_padecimiento+'" id_cliente="'+id_cliente+'" estado="'+estado+'" title="Directo a Consulta." style="color:white;text-decoration: none" class="azulPanel cursor">';
                }else{
                    result += '<a href="/dr_basico/cirugias/'+id+'/preparar" onclick="inicioCirugia('+id+','+id_cliente+')" title="Pasar a Cirugía." style="color:white;text-decoration: none" class="azulPanel cursor">';
                }
            }else {
                result += '<a href="clientes/historial/'+id_cliente+'/'+id+'" dato="'+dato+'" title="Pasar a Consulta." style="color:white; text-decoration: none" class="azulPanel">';
            }
        @endif

        result += '<strong>'+hora+' </strong><strong class="pull-right">'+paciente +'</strong>';
        result += '<div class = "panel-heading">';
        result += '<div class = "row">';
        result += '<div class = "text-right"><div class = "huge text-right">' + tipo + '@if(Entrust::can('atender_consulta'))<i class="fa fa-arrow-circle-right fa-fw"></i>@endif</div><div>';
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
        @if(Entrust::can('ver_servicios'))
            if (para == 1){
                result += '<a title="Pendiente de Reagendar" href="/dr_basico/servicios/'+id+'/edit" class="removerDec rojo" style="padding-left: 16px;"> <i class="iconosfuente reagendar"> </i></a>';
            } else {
                result += '<a title="Reagendar" href="/dr_basico/servicios/'+id+'/edit" class="removerDec" style="padding-left: 16px;"> <i class="iconosfuente reagendar"> </i></a>';
            }
        @endif

        //Preconsultas
        if(tipo == 'Consulta') {
            if (existePreconsultaGuardada == 'noExistePreConsulta'){
                result += '<a onclick="informacionConsulta(this)" fecha="' + fecha + '" id="' + id + '" id_padecimiento="' + id_padecimiento + '" id_cliente="' + id_cliente + '" estado="' + estado + '" title="Valoración pre-consulta." href="#" class="removerDec rojo" data-toggle="modal" data-target="#modalConsulta"> <i class = "fa fa-product-hunt fa-fw"></i></a>';
            }
        }

        //Archivo
        if (id_padecimiento != null) {
            result += '<a onclick="archivos(this)" title="Agregar Archivos" id="' + id + '" id_cliente="' + id_cliente + '" id_padecimiento="' + id_padecimiento + '" class="cursor removerDec" data-toggle="modal" data-target="#modalArchivos"> <i class="fa fa-file-pdf-o fa-fw"> </i></a>';
        }

        //Recordatorio
        result += '<a title="Enviar Recordatorio" id_servicio="'+id+'" id_cliente="'+id_cliente+'" href="#" onclick="crearCorreo(this)"><i class = "fa fa-paper-plane fa-fw"></i></a>';

        //Estado
        result += '<a data-id="'+id+'" onclick="return cambiarEstado(this)" class="cursor"><i class = "'+colorEstado+' fa fa-circle fa-fw" title="Cambiar Estado del progreso de la Cita"> </i></a>';

        //Seguimiento
        @if(Entrust::can('crear_servicios'))
            if (para == 2){
                if (id_padecimiento != null){
                    result += '<a title="Pendiente de Seguimiento" href="/dr_basico/servicios/'+id+'/seguimiento/cita" class="removerDec rojo"> <i class="fa fa-share-square-o fa-fw"> </i></a>';
                }
            } else {
                if (id_padecimiento != null){
                    result += '<a title="Seguimiento" href="/dr_basico/servicios/'+id+'/seguimiento/cita" class="removerDec"> <i class="fa fa-share-square-o fa-fw"> </i></a>';
                }
            }
        @endif

        /********************************************* Separador del menu lateral *************************************/
        @if(Entrust::can('eliminar_servicios'))
            //Borrar
            result += '<a href="#" data-slug="servicios" data-id="'+id+'" onclick="return borrarElemento(this)" class="pull-right" style="margin-right: -20px;margin-bottom: -15px;">';
            result +=   '<span class="fa-stack" style="margin-top: 12px;">';
            result +=       '<i class = "fa fa-times fa-stack-1x" style="font-size: 11px;" title="Borrar Cita"></i>';
            result +=       '<i class="fa fa-circle-o fa-stack-1x rojo" style="font-size: 16px;"></i>';
            result +=   '</span>';
            result += '</a>';
        @endif

        //Historial del paciente
        @if(Entrust::can('atender_consulta'))
        result += '<a href="clientes/historial/'+id_cliente+'" class="pull-right removerDec"> <i class="glyphicon glyphicon-paste fa-fw" title="Historial del Paciente"> </i> </a>';
        //receta o prerarar cirugia
        if (tipo != 'Consulta') {
            //si no es consulta se supone que es cirugia y puede preparar cirugia
            if (existeCirugiaGuardada == 'noExisteCirugiaGuardada') {
                result += '<a href="/dr_basico/cirugias/' + id + '/preparar" title="Preparar Cirugía" onclick="inicioCirugia(' + id + ',' + id_cliente + ')" class="removerDec pull-right"> <i class="fa fa-user-md fa-fw rojo"> </i></a>';
            }else {
                result += '<a href="/dr_basico/cirugias/' + id + '/preparar" title="Preparar Cirugía" onclick="inicioCirugia(' + id + ',' + id_cliente + ')" class="removerDec pull-right"> <i class="fa fa-user-md fa-fw"> </i></a>';
            }
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
            datesDisabled: arrayFecha_inhabil
        });

        //Marca fechas con servicios calendario superior derecho * comentado porque no funciona al dar clik sobre uno de estos dias
        /*$('#sandbox-container .calendario').datepicker('setDate', arrayServicios);*/

        /*$("th.today").css("background-color", "#EEEEEE");
         $("td.active").removeClass('active').addClass('activoColor');*/
    }

    function calendarioSemanal(arrayFecha_inhabil) {

        //deshabilitar fechas registradas como inhabiles en calendario semanal
        $('.dia').css("background-color", "");
        $.each(arrayFecha_inhabil, function( index, value ) {
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
        }else{  minutos = '30'; }
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

    function menosUnMin(horaAajustar){
        var separar = horaAajustar.split(':');
        var horas = separar[0];
        var minutos = separar[1];
        var horaAjustada = '';

        if (minutos == '30'){
            minutos = '29';
        }else if(minutos == '00'){
            minutos = '59';
            horas = parseFloat(horas) - 1;
        }
        horaAjustada = horas + ':' + minutos + ':00';
        return horaAjustada;
    }

    function filtroTurno(turno){
        /*console.log(turno);*/
        if(turno == 'manana') {
            $('.pm').addClass('hidden');
            $('.am').removeClass('hidden');
            $('.botonManana').addClass('botonActivo');
            $('.botonTarde').removeClass('botonActivo');
            $('.botonTodo').removeClass('botonActivo');
        }else if (turno == 'tarde'){
            $('.pm').removeClass('hidden');
            $('.am').addClass('hidden');
            $('.botonManana').removeClass('botonActivo');
            $('.botonTarde').addClass('botonActivo');
            $('.botonTodo').removeClass('botonActivo');
        }else{
            $('.pm').removeClass('hidden');
            $('.am').removeClass('hidden');
            $('.botonManana').removeClass('botonActivo');
            $('.botonTarde').removeClass('botonActivo');
            $('.botonTodo').addClass('botonActivo');
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
                type: 'POST',
                url: '/dr_basico/servicios/cambioEstado/'+id_servicio+'/'+result,
                data:{
                    _token: $('meta[name=csrf-token]').attr('content')
                },success: function () {

                    /*var inicioCalendario = $('#inicioSemana').val();
                    inicioCalendario = new Date(inicioCalendario);
                    agragarClases(inicioCalendario)

                    var fechaSeleccionada = $('#fechaSeleccionada').val();
                    $("th[fecha='"+fechaSeleccionada+"']").trigger('onclick');*/
                    window.location.reload();

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
        $("td[actividad='deshabilitado']").find('span.agregarCitaMas').remove();
        $("td[actividad='deshabilitado']").removeAttr('onclick');

    }

    function activarCalendario(){

        $('div.activarAgenda').removeClass('hidden');
        $('div.activarCalendario').addClass('hidden');
        $('.horaT').find('span.agregarCitaMas').remove();
        $("td[actividad='si']").attr('onclick','vistaLateralHora(this)');

    }

    //preconsulta
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
        $('.historialCliente').attr('href','clientes/historial/'+id_cliente);
        $('.pasarConsulta').attr('href','clientes/historial/'+id_cliente+'/'+id_servicio);
        $('.guardarConsulta').removeClass('hidden');
        $('.pasarConsulta').addClass('hidden');

        $.ajax({
            type: 'Get',
            url: '/dr_basico/servicios/datos/ultimaVisita/'+id_cliente,
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

                    swal("Paciente Nuevo","Favor de llenar todos los datos","warning");
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
        /*var id_clienteFin = $(elemento).attr('id_cliente');*/
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
            url: '/dr_basico/servicios/datos/ultimaVisita/'+id_cliente,
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
                        url: '/dr_basico/guardarInfoConsulta/'+id_servicio,
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
                        },success: function(){
                            var url = '"'+'/dr_basico/clientes/historial/'+id_cliente+'/'+id_servicio+'"';
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
            url: '/dr_basico/padecimientosCliente/'+id_cliente,
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

    function pagar(id){
        swal({
            title: 'Espere',
            text: "¿Seguro de marcar como pagado?",
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            cancelButtonClass: 'textoNegro',
            cancelButtonColor: "#E0E0E0",
            confirmButtonColor: "#449D44",
            confirmButtonText: 'Si, estoy seguro'
        }).then(function() {

            $.ajax({
                type: 'POST',
                url: '/dr_basico/servicios/pagar/'+id,
                data:{
                    _token: $('meta[name=csrf-token]').attr('content')
                },
                success: function(data){
                    swal("Recibo pagado","","success");
                    window.location.reload();
                },error: function (ajaxContext) {
                    swal("Espere","Algo salio mal, reintente de nuevo","warning");
                }
            });
        })
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
            url: '/dr_basico/guardarInfoConsulta/'+id_servicio,
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
            url: '/dr_basico/servicios/datos/ultimaVisita/'+id_cliente,
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

    function cirugiaGuardada(id_servicio){
        var respuesta = '';
        $.ajax({
            type: 'Get',
            url: '/dr_basico/cirugias/existe/cirugiaGuardada/'+id_servicio,
            async: false,
            success: function(data){
                if (data != ''){
                    if(data.id_servicio == id_servicio){
                        respuesta = 'existeCirugiaGuardada';
                    }else{
                        respuesta = 'noExisteCirugiaGuardada';
                    }
                }else{
                    respuesta = 'noExisteCirugiaGuardada';
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
            url: '/dr_basico/reagendarAlerta/'+id_servicio,
            success: function(data){
                swal("Enviada","Se envió alerta para reagendar el servicio","success");
                window.setTimeout(function(){location.reload()},500)
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

    function inicioCirugia(id_servicio,id_cliente){

        $.ajax({
            type: 'POST',
            url: '/dr_basico/inicioCirugia/hora',
            data:{
                _token: $('meta[name=csrf-token]').attr('content'),
                id_cliente: id_cliente,
                id_servicio: id_servicio
            }
        });
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

{{--Modal Preconsulta --}}
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
                            {!! Form::textarea('sintomas', null, ['class' => 'form-control','rows'=>'5','maxlength' => 225]) !!}
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
                                {!! Form::text('temperatura', null, ['id'=>'temperatura','class'=>'form-control','placeholder'=>'Temperatura','maxlength' => 10]) !!}
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('presion', 'Presion:') !!}
                                {!! Form::text('presion', null, ['id'=>'presion','class'=>'form-control','placeholder'=>'Presión','maxlength' => 10]) !!}
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('glucosa', 'Glucosa:') !!}
                                {!! Form::text('glucosa', null, ['id'=>'glucosa','class'=>'form-control','placeholder'=>'Glucosa','maxlength' => 10]) !!}
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('peso', 'Peso:') !!}
                                {!! Form::text('peso', null, ['id'=>'peso','class'=>'form-control','placeholder'=>'Peso','maxlength' => 10]) !!}
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('estatura', 'Estatura:') !!}
                                {!! Form::text('estatura', null, ['id'=>'estatura','class'=>'form-control','placeholder'=>'Estatura','maxlength' => 10]) !!}
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
{{--Modal CrearReceta --}}
<div class="modal fade" id="modalReceta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Receta <i class="fa fa-info-circle" title="Los medicamentos que requieran receta médica no podrán ser impresos en la función Venta Directa. Para la Receta Medica es necesario que imprima una receta de prueba y tomar las medidas necesarias para el membrete."></i> </h4>
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
                <div class="col-xs-12 areaReceta hidden">
                    {!! Form::label('receta', 'Receta:') !!}
                    {!! Form::textarea('receta', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-sm-6 col-lg-4">
                    {!! Form::label('tipoReceta', 'Tipo de Receta:') !!}<BR>
                    <input id="tipoReceta" type="checkbox" data-off-text="Receta Medica" data-off-color="primary"  data-on-text="Venta Directa" data-on-color="primary" checked="false" class="form-control">
                </div>
                <div class="grupo col-xs-12 pad0 areaMedicamentos" style="margin-top: 15px">
                    <div class="col-xs-12 titulo page-header-sub">Receta</div>
                    <div class="invisible">
                        {!! Form::label('select-medicamento', 'Seleccionar Medicamento:') !!}
                        <select id="select-medicamento" data-lista2="lista2" class="form-control">
                            <option value="0">Selecciona medicamento</option>
                            @foreach($medicamentos as $medicamento)
                                <option componente="{!! $medicamento->componente !!}" value="{!! $medicamento->id !!}" tipo="{!! $medicamento->receta !!}">{!! $medicamento->componente !!} | {!! $medicamento->marca !!}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-10 col-md-4">
                        {!! Form::label('select-medicamento', 'Seleccionar Medicamento:') !!} <i class=" info fa fa-info-circle" title="Dar click en el rectangulo de abajo para que aparesca el listado de medicamentos"></i>
                        <input class="filterinput2 form-control" type="text" data-lista2="lista2" id="campo_producto"  placeholder="Seleccione medicamento">
                        <ul id="lista2" class="lista invisible">
                            @foreach($medicamentos as $medicamento)
                                <li componente="{!! $medicamento->componente !!}" value="{!! $medicamento->id !!}" tipo="{!! $medicamento->receta !!}">{!! $medicamento->componente !!} | {!! $medicamento->marca !!}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="form-group col-sm-6 col-lg-4">
                        {!! Form::label('periodo', 'Periodo:') !!}
                        {!! Form::text('periodo', null, ['class' => 'form-control', 'maxlength' => '60']) !!}
                    </div>

                    <div class="col-sm-2">
                        {!! Form::button('<i class = "fa fa-plus-circle" style="margin-top: 18px;"></i>', ['class' => 'btn btn-icon-sucess', 'onclick' => "agregarMedicamento(this)" , 'title' => 'Agregar Medicamento Seleccionado']) !!}
                    </div>
                    <div class="col-xs-12 medicamentos-header table-responsive" style="margin-top: 10px">
                        <table class="table table-bordered" id="seccion-medicamentos">
                            <thead>
                            <tr>
                                <th>Medicamento</th>
                                <th>Periodo</th>
                                <th>Quitar</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer container-fluid">
                <a type="button" class="btn btn-success pull-right guardarReceta recetaMedica hidden" data-accion="m" onclick="guardarReceta(this)" style="margin-left: 5px;">
                    Imprimir Receta Medica <i class="fa fa-print"></i>
                </a>
                <a type="button" class="btn btn-success pull-right guardarReceta ventaDirecta" data-accion="s" onclick="guardarReceta(this)">
                    Imprimir Venta Directa <i class="fa fa-file-o"></i>
                </a>
            </div>
        </div>
    </div>
</div>


<script>
    $("#tipoReceta").bootstrapSwitch();
    $(document).on('ready',function() {
        //Medicamentos
        $(".filterinput2").on("click",function(){
            var lista2 = $(this).data("lista2");
            $("#"+lista2+" li").on("click",function(){
                $("#"+lista2).addClass('invisible').removeClass('show');
                var value = $(this).attr("value");
                $("select[data-lista2='"+lista2+"']").val(value).trigger("onchange");
                $(".filterinput2[data-lista2='"+lista2+"']").val($(this).text());
            })
            $("#"+lista2).addClass('show').removeClass('invisible');
        });
        $(".filterinput2").change( function () {
            var filter = $(this).val();
            var lista2 = $(this).data("lista2");
            if (filter) {
                $("#"+lista2).find("li:not(:contains(" + filter + "))").addClass("invisible").removeClass("show");
                $("#"+lista2).find("li:contains(" + filter + ")").addClass("show").removeClass("invisible");
            } else {
                $("#"+lista2).find("li").addClass("show").removeClass("invisible");
            }
        }).keyup( function () {
            $(this).change();
            //Ocultar añadido
            $(".lista2").addClass('invisible').removeClass('show');
        });

        $('.guardarMedicamento').attr('accion','r');
    });

    $('#tipoReceta').on( "switchChange.bootstrapSwitch", function(e, data){
        var tipoReceta = ($("#tipoReceta").prop("checked"))? 1:0;
        if (tipoReceta == 1) {
            console.log('venta directa');
            $("li[tipo='0']").removeClass("hidden");
            $(".recetaMedica").addClass("hidden");
            $(".ventaDirecta").removeClass("hidden");
        } else {
            console.log('receta medica');
            $("li[tipo='0']").addClass('hidden');
            $("li[tipo='1']").removeClass('hidden');
            $(".ventaDirecta").addClass("hidden");
            $(".recetaMedica").removeClass("hidden");
        }
    });

    function receta(este){

        var hoy = new Date();
        var fecha = $.datepicker.formatDate('yy-mm-dd',new Date(hoy));

        var paciente = $(este).attr('paciente');
        var id_cliente = $(este).attr('id_cliente');
        $('.paciente').html(paciente);
        $('.fecha_receta').html(fecha);
        $('#receta').val('').removeAttr('readonly');
        $('.reimprimirReceta').addClass('hidden');
        $('.guardarReceta').attr('fecha',fecha).attr('id_cliente',id_cliente).attr('paciente',paciente);
        $('.areaReceta').addClass('hidden');
        $('.areaMedicamentos').removeClass('hidden');

        $("#tipoReceta").bootstrapSwitch('disabled',false);
        $("#seccion-medicamentos").children().children('tr').remove();
    }

    function guardarReceta(este){
        var tipoReceta = ($("#tipoReceta").prop("checked"))? 1:0;
        var fecha = $(este).attr('fecha');
        var id_cliente = $(este).attr('id_cliente');
        var componente = $(este).attr('componente');
        var paciente = $(este).attr('paciente');
        var accion = $(este).attr('data-accion');
        /*var receta = $('#receta').val();*/

        var medicamentos = [];
        var medicamento = {};
        $("#seccion-medicamentos tr").each(function(){
            var id = $(this).attr("id");
            var componente = $(this).children('td.componente').text();
            var periodo = $(this).children('td.periodo').text();
            if(id!="" && id!=undefined){
                medicamento = {
                    id:id,
                    componente:componente,
                    periodo:periodo,
                };
                medicamentos.push(medicamento);
            }
        });

        if (medicamentos == ''){medicamentos = 'vacio';}
        /*console.log(medicamentos);*/

        if(medicamentos == 'vacio') {return swal("Espere","Es necesario apregar Medicamento","warning");}

        $.ajax({
            type: 'POST',
            url: '/dr_basico/servicios/receta',
            data:{
                _token: $('meta[name=csrf-token]').attr('content'),
                fecha: fecha,
                id_cliente: id_cliente,
                paciente:   paciente,
                medicamentos:   medicamentos,
                tipoReceta: tipoReceta,
                /*receta:     receta,*/
                accion:accion,
            },success: function(data){
                /*receta = receta.replace(/\r?\n/g, "<br>");*/
                console.log(data);
                if (accion == 'm') {
                    var result = '';
                    result += '<div class = "row" style="margin-top: 2.5cm;"></div>';
                    result += '<div style="margin-left: 1cm;margin-top: 4px;">' + paciente + '</div>';
                    result += '<div style="margin-left: 13cm;margin-top: -16px;">' + fecha + '</div>';
                    for (var i = 0; i < medicamentos.length; i++) {
                        result += '<div class = "row" style="margin-top: 0.5cm;">' + medicamentos[i].componente + ' - ' + medicamentos[i].periodo + '</div>';
                    }
                    newWin = window.open("");
                    newWin.document.write(result);

                    if (!!navigator.userAgent.match(/Trident/gi)) {
                        newWin.document.execCommand('print', false, null);
                    } else {
                        newWin.print();
                    }
                    newWin.close();
                }else{
                    verPDF('/dr_basico/uploads/recetas/' + data)
                }
                $('#modalReceta').modal('toggle');
                swal("Mandado a imprimir","","success");
                $('#seccion-medicamentos').children().children('tr').remove();
            },error: function (ajaxContext) {
                swal("Espere","Algo salio mal, reintente de nuevo","warning");
            }
        });
    }

    function agregarMedicamento(){
        var select = $("#select-medicamento option:selected").text();
        var lista2 = $("input[data-lista2='lista2']").val();

        // $.trim remueve espacios en blanco al comienzo y al final
        if($.trim(select) == $.trim(lista2)) {

            var ids = [];
            $('#seccion-medicamentos tr').each(function(){
                ids.push($(this).attr('id'));
            });

            var id = $("#select-medicamento").val();

            if ($.inArray(id,ids) == -1){

                var medicamento = $("#select-medicamento option:selected").text();
                var componente = $("#select-medicamento option:selected").attr('componente');
                var periodo = $("#periodo").val();

                if(periodo == "")return swal("Espere","Es necesario el periodo", "warning");

                var ay = "";
                ay += "<tr id=" + id + ">";
                ay += "<td class='componente'>"+componente+"</td>";
                ay += "<td class='periodo'>"+periodo+"</td>";
                ay += "<td><div class='minus col-xs-1' onclick='quitarElemento(this)'><i class='fa fa-times' title='Quitar solo este producto'></i></div></td>";
                ay += "</tr>";
                $("#seccion-medicamentos").append(ay);

                $("input[data-lista2='lista2']").val("");
                $("#periodo").val("");

                $("#tipoReceta").bootstrapSwitch('disabled',true);
            }else{
                return swal("Espere","Este Medicamento ya esta agregado", "warning");
            }
        }else {
            return swal("Espere", "Seleccione un Medicamento valido", "warning");
        }
    }

    function quitarElemento(x){
        $(x).parents('tr').remove();
    }

</script>
@endsection







