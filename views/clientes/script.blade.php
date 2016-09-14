<script>
    $(document).on('ready',function() {
        $('#fecha_nacimiento').datepicker({
            format        : "yyyy-mm-dd",
            todayBtn      : "linked",
            language      : "es",
            orientation   : "auto",
            autoclose     : true,
            todayHighlight: true,
            minDate: 0
        });
    });

    function exportarExcel() {
        var inicio = $('#fecha_inicio').val();
        var final = $('#fecha_final').val();
        if(inicio!='' && final!=''){
            $.ajax({
                type: 'GET',
                url: '/clientes/excel/'+inicio+'/'+final,
                success: function(data){
                    window.open('/clientes/excel/'+inicio+'/'+final);
                }
            })
        }else{
            swal('Faltan Datos', 'Es necesario Fecha Inicial y Final.', 'warning');
        }
    }

    function agregarEmail(){

        var email = $("#email").val();
        if(email !="") {
            var ids = [];

            $('#seccion-emails tr').each(function(){
                ids.push($(this).attr('id'));
            });

            var res = "";
            res += "<tr id='"+email+"'><td class='email'>" + email + "</td>";
            res += "<td><div class='minus col-xs-1' onclick='quitarEmail(this)'><i class='fa fa-times' title='Quitar solo este E-mail'></i></div></td></tr>";
            $("#seccion-emails").append(res);
            $('#select-email').append('<option value="'+email+'" selected="selected">'+email+'</option>');
            $("#email").val("");

        }else { return swal("Falta Correo", "", "warning"); }
    }

    function quitarEmail(x){
        $(x).parents('tr').remove();
    }

    function Guardar(valor){

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
        /* -------- ficha medica ---------- */
        var asma = ($("#asma").prop("checked"))? 0:1;
        var ulsera = ($("#ulsera").prop("checked"))? 0:1;
        var fiebre = ($("#fiebre").prop("checked"))? 0:1;
        var diabetes = ($("#diabetes").prop("checked"))? 0:1;
        var cardiacas = ($("#cardiacas").prop("checked"))? 0:1;
        var convulsiones = ($("#convulsiones").prop("checked"))? 0:1;
        var tuberculosis = ($("#tuberculosis").prop("checked"))? 0:1;
        var mareos = ($("#mareos").prop("checked"))? 0:1;
        var dolor_cabeza = ($("#dolor_cabeza").prop("checked"))? 0:1;
        var emocionales = ($("#emocionales").prop("checked"))? 0:1;
        var hernias = ($("#hernias").prop("checked"))? 0:1;
        var arterial = ($("#arterial").prop("checked"))? 0:1;

        var asma_coment = $("#asma_coment").val();
        var ulsera_coment = $("#ulsera_coment").val();
        var fiebre_coment = $("#fiebre_coment").val();
        var diabetes_coment = $("#diabetes_coment").val();
        var cardiacas_coment = $("#cardiacas_coment").val();
        var convulsiones_coment = $("#convulsiones_coment").val();
        var tuberculosis_coment = $("#tuberculosis_coment").val();
        var mareos_coment = $("#mareos_coment").val();
        var dolor_cabeza_coment = $("#dolor_cabeza_coment").val();
        var emocionales_coment = $("#emocionales_coment").val();
        var hernias_coment = $("#hernias_coment").val();
        var arterial_coment = $("#arterial_coment").val();
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

        //Correos
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

        var este = $(valor);

        var id_cliente = este.attr('dato-id');

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
        // ficha medica
        formdata.append( 'asma', asma );
        formdata.append( 'ulsera', ulsera );
        formdata.append( 'fiebre', fiebre );
        formdata.append( 'diabetes', diabetes );
        formdata.append( 'cardiacas', cardiacas );
        formdata.append( 'convulsiones', convulsiones );
        formdata.append( 'tuberculosis', tuberculosis );
        formdata.append( 'mareos', mareos );
        formdata.append( 'dolor_cabeza', dolor_cabeza );
        formdata.append( 'emocionales', emocionales );
        formdata.append( 'hernias', hernias );
        formdata.append( 'arterial', arterial );

        formdata.append( 'asma_coment', asma_coment );
        formdata.append( 'ulsera_coment', ulsera_coment );
        formdata.append( 'fiebre_coment', fiebre_coment );
        formdata.append( 'diabetes_coment', diabetes_coment );
        formdata.append( 'cardiacas_coment', cardiacas_coment );
        formdata.append( 'convulsiones_coment', convulsiones_coment );
        formdata.append( 'tuberculosis_coment', tuberculosis_coment );
        formdata.append( 'mareos_coment', mareos_coment );
        formdata.append( 'dolor_cabeza_coment', dolor_cabeza_coment );
        formdata.append( 'emocionales_coment', emocionales_coment );
        formdata.append( 'hernias_coment', hernias_coment );
        formdata.append( 'arterial_coment', arterial_coment );
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

        if(id_cliente == undefined){
            console.log('nuevo cliente');

            waitingDialog.show('Guardando..', {dialogSize: 'sm', progressType: 'warning'});

            $.ajax({
                type: 'POST',
                url: 'guardar',
                data:formdata,
                processData: false,
                contentType: false,
                success: function(data){
                    waitingDialog.hide();
                    swal("Guardado","","success");
                    setTimeout("location.href = '/clientes'",0);

                },error: function (ajaxContext) {
                    waitingDialog.hide();
                    swal("Espere","Algo salio mal, reintente de nuevo","warning");
                }
            });
        }else{
            console.log('editar cliente')

            waitingDialog.show('Actualizando...', {dialogSize: 'sm', progressType: 'warning'});

            $.ajax({
                type: 'POST',
                url: 'editar',
                data:formdata,
                processData: false,
                contentType: false,success: function(data){
                    waitingDialog.hide();
                    swal("Actualizado","","success");
                    setTimeout("location.href = '/clientes'",0);
                },error: function (ajaxContext) {
                    waitingDialog.hide();
                    swal("Espere","Algo salio mal, reintente denuevo","warning");
                }
            });
        }
    }

    function historialCliente(valor) {
        var self = $(valor);
        var id = self.attr('data-id');
        $('#myModal').modal();
        $.ajax({
            type   : "GET",
            url    : "/clientes/historial/lista/" + id,
            success: function (data) {
                /*console.log(data);*/
                $('#paciente').html(data[0].paciente);

                var result = "";
                result += "<table class='table'>";
                result += "<thead>";
                result += "<th>Fecha</th>";
                result += "<th>Tipo</th>";
                result += "<th>Cirugia</th>";
                result += "<th>Diagnostico</th>";
                result += "<th>Servicio</th>";
                result += "</thead>";
                result += "<tbody>";

                result += "</tr>";
                for (var i = 0; i < data.length; i++) {
                    result += "<tr>";
                        result += "<td>" + data[i].fecha + "</td>";
                        if(data[i].tipo == 1){var tipo = "Consulta";}else{var tipo = "Cirugia";}
                        result += "<td>" + tipo + "</td>";
                        if(data[i].cirugia == null){var cirugia = " ";}else{var cirugia = data[i].cirugia;}
                        result += "<td>" + cirugia + "</td>";
                        result += "<td>" + data[i].diagnostico + "</td>";
                        result += "<td>" + data[i].id + "</td>";
                    result += "</tr>";
                }
                result += "</tbody>";
                result += "</table>";
                $('.tablaHistorial').html(result);
            }
        });
    }

    function mostrarCampo(que){
        var tieneClase = "";
        switch (que) {
            case 'asma_coment':
                tieneClase = $(".asma_coment").hasClass('hidden');
                    if (tieneClase == true) {
                        $(".asma_coment").removeClass('hidden');
                    }else {
                        $(".asma_coment").addClass('hidden');
                    }
                break;
            case 'ulsera_coment':
                tieneClase = $(".ulsera_coment").hasClass('hidden');
                    if (tieneClase == true) {
                        $(".ulsera_coment").removeClass('hidden');
                    }else {
                        $(".ulsera_coment").addClass('hidden');
                    }
                break;
            case 'fiebre_coment':
                tieneClase = $(".fiebre_coment").hasClass('hidden');
                    if (tieneClase == true) {
                        $(".fiebre_coment").removeClass('hidden');
                    }else {
                        $(".fiebre_coment").addClass('hidden');
                    }
                break;
            case 'diabetes_coment':
                tieneClase = $(".diabetes_coment").hasClass('hidden');
                    if (tieneClase == true) {
                        $(".diabetes_coment").removeClass('hidden');
                    }else {
                        $(".diabetes_coment").addClass('hidden');
                    }
                break;
            case 'cardiacas_coment':
                tieneClase = $(".cardiacas_coment").hasClass('hidden');
                    if (tieneClase == true) {
                        $(".cardiacas_coment").removeClass('hidden');
                    }else {
                        $(".cardiacas_coment").addClass('hidden');
                    }
                break;
            case 'convulsiones_coment':
                tieneClase = $(".convulsiones_coment").hasClass('hidden');
                    if (tieneClase == true) {
                        $(".convulsiones_coment").removeClass('hidden');
                    }else {
                        $(".convulsiones_coment").addClass('hidden');
                    }
                break;
            case 'tuberculosis_coment':
                tieneClase = $(".tuberculosis_coment").hasClass('hidden');
                    if (tieneClase == true) {
                        $(".tuberculosis_coment").removeClass('hidden');
                    }else {
                        $(".tuberculosis_coment").addClass('hidden');
                    }
                break;
            case 'mareos_coment':
                tieneClase = $(".mareos_coment").hasClass('hidden');
                    if (tieneClase == true) {
                        $(".mareos_coment").removeClass('hidden');
                    }else {
                        $(".mareos_coment").addClass('hidden');
                    }
                break;
            case 'dolor_cabeza_coment':
                tieneClase = $(".dolor_cabeza_coment").hasClass('hidden');
                    if (tieneClase == true) {
                        $(".dolor_cabeza_coment").removeClass('hidden');
                    }else {
                        $(".dolor_cabeza_coment").addClass('hidden');
                    }
                break;
            case 'emocionales_coment':
                tieneClase = $(".emocionales_coment").hasClass('hidden');
                    if (tieneClase == true) {
                        $(".emocionales_coment").removeClass('hidden');
                    }else {
                        $(".emocionales_coment").addClass('hidden');
                    }
                break;
            case 'hernias_coment':
                tieneClase = $(".hernias_coment").hasClass('hidden');
                    if (tieneClase == true) {
                        $(".hernias_coment").removeClass('hidden');
                    }else {
                        $(".hernias_coment").addClass('hidden');
                    }
                break;
            case 'arterial_coment':
                tieneClase = $(".arterial_coment").hasClass('hidden');
                    if (tieneClase == true) {
                        $(".arterial_coment").removeClass('hidden');
                    }else {
                        $(".arterial_coment").addClass('hidden');
                    }
                break;
            default:
                tieneClase = $(".comentariosHa").hasClass('hidden');
                if (tieneClase == true) {
                    $(".comentariosHa").removeClass('hidden');
                }else {
                    $(".comentariosHa").addClass('hidden');
                }
        }
    }

</script>