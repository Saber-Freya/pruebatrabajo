<script>
    var servicios = null;
    var seguimiento = null;
    var recibofecha = null;
    var reciboinicio = null;
    var recibofin = null;
    @if(isset($servicio))
        servicios = {!!$servicio!!};
    @endif
    @if(isset($seguimiento))
        seguimiento = 'seguimiento';
    @endif
    @if(isset($recibofecha))
        recibofecha = '{!!$recibofecha!!}';
        reciboinicio = '{!!$reciboinicio!!}';
        recibofin = '{!!$recibofin!!}';
    @endif

    $(document).on('ready',function() {
        $('.sandbox-container .fecha').datepicker({
            format          : "yyyy-mm-dd",
            todayBtn        : "linked",
            language        : "es",
            autoclose       : true,
            todayHighlight  : true
        });

        var hoy = new Date();
        var fechaHoy = $.datepicker.formatDate('yy-mm-dd',new Date(hoy));
        $('.sandbox-container .fecha').datepicker('setDate', fechaHoy);

        var arrayFecha_inhabil = checarFechaInhabiles();
        var arrayServicios = buscarFechaServicios();

        calendario(arrayFecha_inhabil,arrayServicios);

        $('#sandbox-containerCitas .calendario').on('changeDate', function (e) {
            $('#hora').val('');
            var fecha = e.date;

            var fechaSeleccionada = $.datepicker.formatDate('yy-mm-dd',new Date(fecha));
            $('#fecha').val(fechaSeleccionada);

            var tipo =$('#tipo').val();
            cargarTipo(tipo);
            deshabilitarHorarioTipo(fechaSeleccionada,tipo);
        });

        $(".guardar,.cancelar").addClass('invisible');

        var fecha = $('#fecha').val();

        var tipo =$('#tipo').val();
        cargarTipo(tipo);
        $('#tipo').on('change', function(){
            $('#hora').val('');
            var tipo = $(this).val();
            cargarTipo(tipo);
            if(recibofecha != null) {
                crearHorario(reciboinicio,recibofin);
            }
            var fecha = $('#fecha').val();
            deshabilitarHorarioTipo(fecha,tipo);
        });

        var id_cliente =$('#id_cliente').val();
        cargarPadecimiento(id_cliente);
        $('#id_cliente').on('change', function(){
            var id_cliente = $(this).val();
            cargarPadecimiento(id_cliente);
        });


        if(servicios != null) {
            console.log('editar');
            $(".guardarServicio").attr('dato-id', servicios.id);
            $(".alertaReagendar").attr('id_servicio', servicios.id);
            $(".alertaSeguimiento").attr('id_servicio', servicios.id);
            $(".id_cliente").attr("disabled", true);
            $(".id_padecimiento").attr("disabled", true);
            $(".fechaReagendar").removeClass("hidden");
            $("#tipo").attr("disabled", true);
            $(".areaClientenuevo").addClass("hidden");
            crearHorario('08:00:00','21:30:00');
            deshabilitarHorarioTipo(fecha,tipo);
            $("#id_padecimiento").val(servicios.id_padecimiento);
            if(seguimiento != null) {
                console.log(seguimiento);
                $("#tipo").removeAttr('disabled');
                $("#id_costo").removeAttr('disabled');
                $(".guardarServicio").attr('accion','s');
            }else{
                $(".guardarServicio").attr('accion','e');
            }
        }else{
            console.log('nuevo');
        }

        if(recibofecha != null) {
            $('#fecha').val(recibofecha);
            crearHorario(reciboinicio,recibofin);
            deshabilitarHorarioTipo(recibofecha,tipo);
        }

        $('#id_costo').on('change', function(){
            calcularPagos()
        });

        $('#hora').on( "change", function() {
            console.log('entro');
            $('.time-picker').addClass('hidden');
        });

        $('#hora').on( "click", function() {
            $('.time-picker').removeClass('hidden');
        });

        @if(isset($reagendar))
            seguimiento = 'seguimiento';
        @endif

        });

    function calcularPagos(){

        var costo = $('#id_costo option:selected').attr('costo');
        var cirugia = $('#id_costo option:selected').text();

        if (costo == undefined){costo = 0.00;}
        var costoFormato = formatNumber.new(costo, "$");
        $('#costoVer').val(costoFormato);
        $('#costo').val(costo);
        $('#cirugia').val(cirugia);
        $('.totalote').html(costoFormato);
        var inte = $("#interes-mensual").val();
        if($("#interes-mensual").val() == ""){
            $("#interes-mensual").val(0);
            inte =0;
        }
        var total= $('#costo').val();;
        var porcentaje = parseFloat(inte)/100;
        var intereses = total*porcentaje;
        var interesesFormato = intereses.toFixed(2);
        interesesFormato = formatNumber.new(interesesFormato, "$");
        var deudaTotal =parseFloat(total)+intereses;
        var deudaTotalFormato = deudaTotal.toFixed(2)
        deudaTotalFormato = formatNumber.new(deudaTotalFormato, "$");

        $("#deuda-interesesVer").html(deudaTotalFormato);
        $("#interesesVer").html(interesesFormato);
        $("#deuda-intereses").val(deudaTotal);
        $("#intereses").val(intereses);

    }

    function cargarTipo(tipo){

        if (tipo == 1){
            $(".cirugiaSet").addClass('hidden');
            //Cargar horario de Consultas
            /*cargarHorario('1');*/
        }else{
            $(".cirugiaSet").removeClass('hidden');
            /*cargarHorario('2');*/
        }
        cargarCostos(tipo);
    }

    function cargarPadecimiento(id_cliente){
        $.ajax({
            type: 'GET',
            url: '/padecimientosCliente/'+id_cliente,
            success: function(data){
                /*console.log(data)*/
                if (data == ''){
                    $('.clasePadecimiento').addClass('hidden');
                    $('#id_padecimiento').val('0');
                }else{
                    $('.clasePadecimiento').removeClass('hidden');
                    var res = '<option value="0">Seleccionar Padecimiento</option>';
                    for(var i = 0; i < data.length; i++) {
                        res += '<option value="' + data[i].id + '">' + data[i].padecimiento + '</option>';
                    }
                    $('#id_padecimiento').html(res);
                }

            },error: function (ajaxContext) {
                swal("Espere","Algo salio mal, reintente de nuevo","warning");
            }
        });
    }

    function  crearHorario(reciboinicio,recibofin){
        var separar = recibofin.split(':');
        var horas = separar[0];
        var minutos = separar[1]-parseInt(1);

        jQuery(function() {
            $(".hora").timePicker({
                startTime: reciboinicio,  //Indica el inicio
                endTime: new Date(0, 0, 0, horas, minutos, 0),  //Indica que la fecha de fin es a las 15:30
                show24Hours: true, //Formato de fechas AM y PM
                separator:':', //Separador de horas y minutos
                step: 1 //Frecuencia de cada intervalo
            });
        });
    }

    function checarFechaInhabiles(){
        //Calendario de la derecha
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

    function calendario(arrayFecha_inhabil,arrayServicios) {
        $('#sandbox-containerCitas .calendario').datepicker({
            format	: "yyyy-mm-dd",
            language: "es",
            todayBtn: "linked",
            startDate: '0d',
            todayHighlight: true,
            autoclose: true,
            datesDisabled: arrayFecha_inhabil,
        });
        /*$('#sandbox-containerCitas .calendario').datepicker('setDate', arrayServicios)*/
    }

    function GuardarCita(valor){
        var este = $(valor);
        var id_servicio = este.attr('dato-id');
        var tipo =$('#tipo').val();
        if(id_servicio == undefined){
            /*if(tipo == 1){*/
            console.log('crear');
            guardarConsulta(tipo);
            /*}else{
             console.log('crear');
             guardarCirugia(tipo);
             }*/
        }else{
            /*if(tipo == 1){*/
                console.log('editar');
                editarConsulta(tipo,id_servicio);
            /*}else{
                console.log('editar');
                editarCirugia(tipo,id_servicio);
            }*/
        }
    }

    function guardarConsulta(tipo) {
        var cliente = $("#id_cliente").val();
        var fecha = $("#fecha").val();
        var id_padecimiento = $("#id_padecimiento option:selected").val();
        var hora = $("#hora").val();
        var cirugia = $("#cirugia").val();
        var diagnostico = $("#diagnostico").val();
        var id_costo = $("#id_costo").val();
        var costo = $("#costo").val();
        waitingDialog.show('Guardando...', {dialogSize: 'sm', progressType: 'warning'});
        $.ajax({
            type: 'POST',
            url: '/servicios/guardar',
            data:{
                _token: $('meta[name=csrf-token]').attr('content'),
                id_cliente: cliente,
                tipo: tipo,
                fecha: fecha,
                hora: hora,
                cirugia: cirugia,
                diagnostico: diagnostico,
                id_padecimiento: id_padecimiento,
                id_costo: id_costo,
                costo: costo,
            },success: function(data){
                waitingDialog.hide();
                swal("Guardado","","success");
                setTimeout("location.href = '/'",0);

            },error: function (data) {
                waitingDialog.hide();
                var errors = data.responseJSON;
                var mensaje = "";

                $.each(errors, function(index, value) {
                    mensaje += value+'\n';
                });
                console.log(mensaje);
                swal("Espere",mensaje,"warning");
            }
        });
    }

    function editarConsulta(tipo,id_servicio) {
        var cliente = $("#id_cliente").val();
        var fecha = $("#fecha").val();
        var hora = $("#hora").val();
        var id_padecimiento = $("#id_padecimiento option:selected").val();
        var diagnostico = $("#diagnostico").val();
        var cirugia = $("#cirugia").val();
        var id_costo = $("#id_costo").val();
        var costo = $("#costo").val();
        var accion = $(".guardarServicio").attr('accion');
        waitingDialog.show('Actualizando...', {dialogSize: 'sm', progressType: 'warning'});
        $.ajax({
            type: 'POST',
            url: '/servicios/editar',
            data:{
                _token: $('meta[name=csrf-token]').attr('content'),
                id_servicio: id_servicio,
                id_cliente: cliente,
                tipo: tipo,
                fecha: fecha,
                hora:hora,
                diagnostico: diagnostico,
                cirugia: cirugia,
                accion: accion,
                id_padecimiento: id_padecimiento,
                id_costo: id_costo,
                costo: costo,
            },success: function(data){
                waitingDialog.hide();
                swal("Actualizado","","success");
                setTimeout("location.href = '/'",0);

            },error: function (data) {
                waitingDialog.hide();
                var errors = data.responseJSON;
                var mensaje = "";

                $.each(errors, function(index, value) {
                    mensaje += value+'\n';
                });
                console.log(mensaje);
                swal("Espere",mensaje,"warning");
            }
        });
    }

    function agregarCliente(){
        var factura = $("#check-factura").hasClass('active');
        var nombre = $("#nombre").val().trim();
        var apellido = $("#apellido").val().trim();
        var calle = $("#calle").val().trim();
        var no_exterior = $("#no_exterior").val().trim();
        var no_interiior = $("#no_interiior").val().trim();
        var colonia = $("#colonia").val().trim();
        var tel = $("#tel").val().trim();
        var sangre = $("#sangre").val();
        var sexo = $("#sexo").val();
        var fecha_nacimiento = $("#fecha_nacimiento").val();

        /* -------- Solo si acepta factura ---------- */
        var nom_comercial = $("#nom_comercial").val().trim();
        var razon_social = $("#razon_social").val().trim();
        var rfc = $("#rfc").val().trim();
        var metodo_pago = $("#metodo_pago").val().trim();
        var num_cuenta = $("#num_cuenta").val().trim();
        var calle_pub = $("#calle_pub").val().trim();
        var num_ext = $("#num_ext").val().trim();
        var num_int = $("#num_int").val().trim();
        var col = $("#col").val().trim();
        var pais = $("#pais").val().trim();
        var edo = $("#edo").val();
        var cd = $("#cd").val();
        var cp = $("#cp").val().trim();
        var localidad = $("#localidad").val().trim();
        /* ------------------------------------------- */

        // ----------> Validaciones <---------------
        if(!validarNoVacio(nombre)) return swal("Espere", "Es necesario agregar el nombre de contacto", "info");
        if((tel != '') && (!validar10Digitos(tel))) return swal("Espere", "El Telefono debe de ser de 10 digitos", "info");
        if(factura && !validarNoVacio(nom_comercial)) return swal("Espere", "Es necesario agregar el nombre comercial de la empresa", "info");
        if(factura && !validarNoVacio(razon_social)) return swal("Espere", "Es necesario agregar la razón social de la empresa", "info");
        if(factura && !validarRFC(rfc)) return swal("Espere", "El RFC tiene que ser de 12 o 13 dígitos unicamente", "info");
        if(factura && !validarNoVacio(metodo_pago)) return swal("Espere", "Es necesario agregar un metodo de pago", "info");
        if(factura && !validarNoVacio(calle)) return swal("Espere", "Es necesario agregar la calle", "info");
        if(factura && !validarNoVacio(num_ext)) return swal("Espere", "Es necesario agregar el numero exterior", "info");
        if(factura && !validarNoVacio(col)) return swal("Espere", "Es necesario agregar la colonia", "info");
        if(factura && !validarNoVacio(pais)) return swal("Espere", "Es necesario ingresar el país", "info");
        if(factura && edo == 0) return swal("Espere", "Es necesario seleccionar un estado", "info");
        if(factura && cd == 0) return swal("Espere", "Es necesario seleccionar una ciudad", "info");
        if(factura && !validarNoVacio(cp)) return swal("Espere", "Es necesario ingresar el código postal", "info");
        if(factura && !validarNoVacio(localidad)) return swal("Espere", "Es necesario ingresar la localidad", "info");

        //Contactos
        var contactos = [];

        $("#seccion_contactos > .contacto").each(function (i) {
            var obj = {};
            var contacto = $(this).find('.contacto').html().trim();
            var tel_per = $(this).find('.tel_per').html().trim();
            var email_per = $(this).find('.email_per').html().trim();

            obj["contacto"] = contacto;
            obj["tel_per"] = tel_per;
            obj["email_per"] = email_per;

            contactos.push(obj);
            i++;
        });

        //correos del cliente
        var emails = [];
        var email = {};
        $("#seccion-emails tr").each(function(){
            var id = $(this).attr("id");
            if(id!="" && id!=undefined){
                email = {
                    id:id
                };
                emails.push(email);
            }
        });

        if(nombre=='') {
            swal("Espere", "Es necesario el Nombre", "warning");
            return false;
        }

        if(apellido=='') {
            swal("Espere", "Es necesario el Apellido", "warning");
            return false
        }

        var archivoFoto = $("#foto")[0].files[0];

        emails = JSON.stringify(emails);
        contactos = JSON.stringify(contactos);

        var formdata = new FormData();
        formdata.append( 'foto', archivoFoto );
        formdata.append( '_token', $('meta[name=csrf-token]').attr('content') );
        formdata.append( 'emails', emails );
        formdata.append( 'nombre', nombre );
        formdata.append( 'apellido', apellido );
        formdata.append( 'calle', calle );
        formdata.append( 'no_exterior', no_exterior );
        formdata.append( 'no_interiior', no_interiior );
        formdata.append( 'colonia', colonia );
        formdata.append( 'tel', tel );
        formdata.append( 'sangre', sangre );
        formdata.append( 'sexo', sexo );
        formdata.append( 'fecha_nacimiento', fecha_nacimiento );
        // datos para factura
        formdata.append( 'nom_comercial', nom_comercial );
        formdata.append( 'razon_social', razon_social );
        formdata.append( 'rfc', rfc );
        formdata.append( 'metodo_pago', metodo_pago );
        formdata.append( 'num_cuenta', num_cuenta );
        formdata.append( 'calle_pub', calle_pub );
        formdata.append( 'num_ext', num_ext );
        formdata.append( 'num_int', num_int );
        formdata.append( 'col', col );
        formdata.append( 'pais', pais );
        formdata.append( 'edo', edo );
        formdata.append( 'cd', cd );
        formdata.append( 'cp', cp );
        formdata.append( 'localidad', localidad );
        formdata.append( 'contactos', contactos );

        console.log('nuevo cliente');
        waitingDialog.show('Guardando..', {dialogSize: 'sm', progressType: 'warning'});

        $.ajax({
            type: 'POST',
            url: '/clientes/guardar',
            data:formdata,
            processData: false,
            contentType: false,
            success: function(data){
                waitingDialog.hide();
                swal("Guardado","","success");
                setTimeout("location.href = '/servicios/create'",0);

            },error: function (ajaxContext) {
                waitingDialog.hide();
                swal("Espere","Algo salió mal, reintente de nuevo","warning");
            }
        });
    }

    function deshabilitarHorarioFecha(fecha){
        var hoora="";
        $.ajax({
            async: false,
            type: 'GET',
            url: '/servicios/fecha/' + fecha,
            success: function (data) {

                for (var i = 0; i < data.length; i++) {
                    hoora = data[i].hora.slice(0, -3);
                    $('.time-picker ul li[class="muestraHora '+hoora+'"]').addClass('hidden');
                    /*$('#hora option[value="'+data[i].hora+'"]').html('<strong>'+data[i].hora+' '+data[i].paciente +'</strong>');*/
                }

            }, error: function (ajaxContext) {
                swal("Espere", "Algo salió mal, reintente de nuevo o comicarse con su administrador", "warning");
            }
        });
    }

    function deshabilitarHorarioTipo(fecha,tipo){

        if(tipo == 1){var nombreTipo = 'Consultas';}else{var nombreTipo = 'Cirugías';}

        fecha = new Date(fecha);
        var no_dia = fecha.getUTCDay();

        var hooraDe="";
        var hooraA="";
        var horaAajustar="";
        $('.muestraHora').addClass('hidden');

        $.ajax({
            async: false,
            type: 'GET',
            url: '/horarios/cita/'+no_dia+'/'+tipo,
            success: function (data) {
                /*console.log(data);*/
                var tieneHora = false;
                if (data == ''){
                    $('.muestraHora').addClass('hidden');
                }else{
                    for (var i = 0; i < data.length; i++) {
                        hooraDe = data[i].horaDe.slice(0, -3);
                        hooraA = data[i].horaA.slice(0, -3);

                        $('.time-picker ul li').each( function( index) {
                            /*console.log( $( this ).text() +' < '+ hooraDe +' y '+ $( this ).text() +' > '+ hooraA);*/
                            if ( ($( this ).text() >= hooraDe) && ( $( this ).text() < hooraA) ){
                                $( this ).removeClass('hidden');
                                tieneHora = true;
                            }
                        });
                    }
                }
                var fecha = $('#fecha').val();
                deshabilitarHorarioFecha(fecha);

                if(tieneHora == false){
                    swal("No se encuentra Horario", "Verificar horarios disponibles de "+nombreTipo+" para el Día de la Cita en sección Altas > Horarios.", "info");
                }

            }, error: function (ajaxContext) {
                swal("Espere", "Algo salió mal, reintente de nuevo o comicarse con su administrador", "warning");
            }
        });
    }

    function cargarCostos(tipo){
        $('.id_costo').val(0);;
        $.ajax({
            type: 'GET',
            url: '/costos/cargar/todo',
            success: function(data){
                if (data == ''){
                    $('#id_costo').val('0');
                }else{

                    var res = '';
                        res = '<option value="0">Seleccione Titulo</option>';
                    for(var i = 0; i < data.length; i++) {
                        res += '<option value="'+data[i].id+'" tipo="'+data[i].tipo+'" costo="'+data[i].costo+'" style="display: none">' + data[i].titulo + '</option>';
                    }

                    $('.id_costo').html(res);

                    $("option[tipo='" + tipo + "']").removeAttr('style');
                }

                if(servicios != null) {
                    console.log(servicios);
                    if(tipo == 1){
                        $('.id_costo').val(servicios.id_costo).attr("disabled","disabled");
                    }

                    var costoFormato = formatNumber.new(servicios.costo, "$");
                    $('#costoVer').val(costoFormato);

                    if(seguimiento == null) {
                        $('.id_costo').val(servicios.id_costo).attr("disabled", "disabled");
                    }else{
                        $("#id_costo").removeAttr('disabled');
                    }
                }

            },error: function (ajaxContext) {
                swal("Espere","Algo salio mal, reintente de nuevo","warning");
            }
        });
    }

    var formatNumber = {
        separador: ",", // separador para los miles
        sepDecimal: '.', // separador para los decimales
        formatear:function (num){
            num +='';
            var splitStr = num.split('.');
            var splitLeft = splitStr[0];
            var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
            var regx = /(\d+)(\d{3})/;
            while (regx.test(splitLeft)) {
                splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
            }
            return this.simbol +' '+ splitLeft  +splitRight;
        },
        new:function(num, simbol){
            this.simbol = simbol ||'';
            return this.formatear(num);
        }
    }

    function calcularCredito() {
        if($("#interes-mensual").val() == ""){
            $("#interes-mensual").trigger("onkeyup");
        }

        var cantidadFechas = parseInt($("#cantidad-fechas").val());
        var periodoFechas = $("#periodo-fechas").val();
        $('.btn-guardar-fechas').addClass('show').removeClass('invisible');


        var i=1;
        var date = $("#fecha-inicial").datepicker('getDate');
        var hoy = new Date();
        hoy.setHours(0,0,0,0);
        // console.log("Hoy: "+hoy+"\nEleccion: "+date);
        if (hoy > date)
            return swal("Espere", "La Fecha Inicial no puede ser menor que la fecha del día de hoy, ", "warning");

        $('#interes-mensual').removeAttr("readonly");
        $('#cantidad-fechas').removeAttr("readonly");
        $('#periodo-fechas').removeAttr("readonly");
        //Calcular semana cada dia de la semana, mes cada dia del mes que se eligio y quincena cada dos semanas

        $('#interes-mensual').attr("readonly", "readonly");
        $('#cantidad-fechas').attr("readonly", "readonly");
        $('#periodo-fechas').attr("readonly", "readonly");
        $('.btn-guardar-fechas').addClass('invisible').removeClass('show');
        $('.btn-pasar-cuenta').addClass('show').removeClass('invisible');
        var monto = parseFloat($('#deuda-intereses').val()) / parseInt($('#cantidad-fechas').val());
        var montoFormato = monto.toFixed(2);
        montoFormato = formatNumber.new(montoFormato, "$");

        var s= "";
        for(i=1; i<cantidadFechas+1; i++){
            s += "<div class='col-sm-4 col-xs-6' align='center'><input class='form-controlito monto' value='"+montoFormato+"' readonly='readonly'><div class='fechis fecha"+i+"'></div></div>";
            s += ""
        }

        /*$('.monto').formatCurrency();*/
        $(".construirFechas").html(s);

        //Switch que asigna las fechas de las fechas ya puestas
        switch (periodoFechas){
            case "sem":
                for(i=1; i<cantidadFechas+1; i++){
                    $(".fecha"+i).datepicker({
                        dateFormat    : "yy-mm-dd",
                        language: "es",
                        changeMonth: true,
                        changeYear: true,
                        todayHighlight: true
                    }).datepicker('setDate', date);
                    date.setDate(date.getDate()+7);
                }
                break;
            case "qui":
                for(i=1; i<cantidadFechas+1; i++){
                    $(".fecha"+i).datepicker({
                        dateFormat    : "yy-mm-dd",
                        language: "es",
                        changeMonth: true,
                        changeYear: true,
                        todayHighlight: true
                    }).datepicker('setDate', date);
                    date.setDate(date.getDate()+15);
                }
                break;
            case "men":
                date = $("#fecha-inicial").datepicker('getDate');
                for(i=1; i<cantidadFechas+1; i++){
                    $(".fecha"+i).datepicker({
                        dateFormat    : "yy-mm-dd",
                        language: "es",
                        changeMonth: true,
                        changeYear: true,
                        todayHighlight: true
                    }).datepicker('setDate', date);
                    date.setMonth(date.getMonth()+1);
                }
                break;
        }
    }

    function reagendarAlerta(elemento){
        var id_servicio = $(elemento).attr('id_servicio');
        var para = $(elemento).attr('para');
        $.ajax({
            type: 'GET',
            url: '/reagendarAlerta/'+id_servicio+'/'+para,
            success: function(data) {
                if (para == 1) {
                    swal("Enviada", "Se envió alerta para reagendar esta Cita", "success");
                } else {
                swal("Enviada", "Se envió alerta para dar seguimiento a esta Cita", "success");
                }
                window.setTimeout(function(){location.reload()},5000)
            },error: function (ajaxContext) {
                swal("Espere","Algo salio mal, reintente de nuevo","warning");
            }
        });
    }

</script>