<script>
        $(document).ready(function(){
            $('.fecha').datepicker({
                format	: "yyyy-mm-dd",
                todayBtn      : "linked",
                clearBtn      : true,
                language      : "es",
                startDate: '0d',
                autoclose     : true,
                todayHighlight: true
            }).datepicker("setDate", new Date());

            $('.currency').blur(function()
            {
                $('.currency').formatCurrency();
            });
            $('.currency').formatCurrency();


            $("#recibo").on("change", function(){
                var valor = $("#recibo").val().trim();
                @if(isset($fact))
                var modulo = "{!! $fact !!}";
                console.log(valor);
                if( valor == "factura" && modulo == 0)
                    $("#seccion-idFact").removeClass("invisible");
                else
                    $("#liga-facturacion").addClass("invisible");
                @endif
            });

            $("#guardarPago").on("click",function(e){
                console.log("Guardar Pago");
                $.post('{!! url("/cuentas/registrarPago") !!}', {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    envio: $("#envio").val(),
                    contrarecibo: $("#contrarecibo").val(),
                    pago: $("#pago").val(),
                    forma: $("#forma").val(),
                    refpago: $("#refpago").val(),
                    refdepo: $("#refdepo").val(),
                    idpago: $(".idpago").val()
                })
                        .done(function(data)
                        {
                            swal({
                                title:"Listo",
                                text:"Pago realizado exitosamente",
                                type:"success"
                            }).then(function(){
                                location.reload(true);
                            });

                        })
                        .fail(function(xhr, textStatus, errorThrown)
                       {
                           errorShow(xhr.responseText);
                       })
                        .always(function() {})
            });

            $(".btn.toggle[data-parent]").on("click",function(){
                //By Jenice
                var content = $(this).data("parent");
                $("#"+content+" .toggle").toggle();
            });

            $("#guardarAbono").on("click",function(e) {
                //JGVS approved 050816
                $("#modalAbonos").modal('hide');
                var idPago = $(".idpago").val();
                var horaLocal = sacarHoraLocal();

                $.post('{!! url("/cuentas/registrarAbono") !!}', {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    fechaabono: $("#fechaabono").val(),
                    cantidadabono: $("#cantidadabono").val(),
                    horaLocal: horaLocal,
                    idpago: $(".idpago").val()
                })
                        .done(function (data) {
                            if(data){
                                if(data==0){
                                    swal({
                                      title: "Abono registrado exitosamente.",
                                      text: "Se ha cubierto el pago",
                                      type: "success",
                                      showCancelButton: false,
                                      closeOnConfirm: true
                                    }).then(function(){
                                       $("#tb_pagos").find("."+idPago).find("button[title='Generar pago']").trigger("click");
                                    });

                                }else{
                                    swal({
                                      title: "Abono registrado exitosamente.",
                                      text: "Nuevo saldo: $"+data,
                                      type: "success",
                                      showCancelButton: false,
                                      closeOnConfirm: false
                                    }).then(function(){
                                        location.reload();
                                    });
                                }
                            }else{
                                swal("Error", "Ingresaste una cantidad mayor que el saldo restante de ese pago", "error");
                            }
                            $('#cantidadabono').val('');

                        }).fail(function(xhr, textStatus, errorThrown){
                           errorShow(xhr.responseText);
                       });
            });
            $("#generarRecibo").on("click",function(e) {
                var recibo = $("#recibo").val().trim();
                @if(isset($fact))
                var modulo = "{!! $fact !!}";
                $(this).text("Generando recibo...").addClass("disabled");
                $("body").css("cursor", "progress");

                if($("#chknumfact").is(":checked")){
                    console.log("Facturacion externa");
                    var idFact = $("#idFact").val().trim();
                    //Si el modulo de facturacion no esta activa y elige FACTURA
                    $.post('{!! url("/cuentas/guardarFacturaExterna") !!}', {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        recibo: recibo,
                        idFact: idFact,
                        id_pagos: $(this).attr('idpagos').split(","),
                        id_cliente: $("#clienterecibo").val(),
                        aseguradora: $("#aseguradorarecibo").val()
                    })
                            .done(function (data) {
                                swal({
                                    title: "Listo",
                                    text:"Se guardo exitosamente los datos de factura",
                                    type:"success"
                                }).then(function(){
                                    location.reload();
                                });

                            })
                            .fail(function(xhr, textStatus, errorThrown)
                           {

                               errorShow(xhr.responseText);
                           })
                            .always(function () {
                                $("#generarRecibo").removeClass("disabled").text("Generar recibo");
                                $("body").css("cursor", "default");
                            })

                }else{
                    console.log("Genero Recibo");
                    $.post('{!! url("/cuentas/generarRecibo") !!}', {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        recibo: recibo,
                        id_pagos: $(this).attr('idpagos').split(","),
                        id_cliente: $("#clienterecibo").val(),
                        aseguradora: $("#aseguradorarecibo").val()
                    })
                            .done(function (data) {
                                if(data == "NO RFC"){
                                    swal("Error", "El cliente que selecciono no tiene datos de facturacion", "error");
                                }
                                else if(data['exito'] == 0){
                                    swal("Error", "Error de facturacion: "+data["facModerna"], "error");
                                }
                                else{
                                    $("#modalGenerar").modal("hide");
                                    swal({
                                        title: "Listo",
                                        text:"Se guardo exitosamente los datos de factura",
                                        type:"success"
                                    }).then(function(){
                                        if(recibo == "factura")
                                            verPDF("cuentas/verRecibo/f/"+data,true);
                                        else
                                           verPDF("cuentas/verRecibo/"+data,true);
                                    });

                                    //location.reload();

                                }
                            })
                            .fail(function(xhr, textStatus, errorThrown)
                           {
                               errorShow(xhr.responseText);
                           })
                            .always(function () {
                                $("#generarRecibo").removeClass("disabled").text("Generar recibo");
                                $("body").css("cursor", "default");
                            })
                }
                @endif
            });
            $("#enviarCorreo").on("click", function(e){
                var uuid = $("#enviar-correo").attr("uuid");
                var cadenaCorreos = $("#enviar-correo").val().trim();
                var correos = cadenaCorreos.split(", ");
                $(this).text("Enviando...").addClass("disabled");
                $.post('{!! url("/cuentas/enviarFacturaEmail") !!}', {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    uuid: uuid,
                    correos: correos
                })
                .done(function (data) {
                    if(data.length==0){
                        swal("Listo", "Se envió correctamente el documento", "success");
                        $("#modalEnviar").modal("toggle");
                        $("#enviar-correo").val("");
                        $("#enviar-correo").attr("uuid");
                    }
                    else{
                        var fail = data.join(", ");
                        swal("Error", "No se pudo enviar el documento a "+fail, "error");
                    }
                })
                 .fail(function(xhr, textStatus, errorThrown)
                   {
                       errorShow(xhr.responseText);
                   })
                .always(function () {
                    $("#enviarCorreo").text("Enviar").removeClass("disabled");
                })
            });
        });

        $(".chek-list").on("click", function(){
            var cliente = 0;
            var actual = parseInt($(this).attr("cliente"));
            var hay = $('.chek-list:checked').not(this).attr("cliente");


            if(this.checked)
            {

                if(hay)
                {
                    cliente = $('.chek-list:checked').not(this).attr("cliente");
                }
                else
                {
                    cliente = $('.chek-list:checked').attr("cliente");
                }
                if(cliente != actual)
                    $(this).prop("checked", false);
            }
            else{
                cliente = $('.chek-list:checked:first').attr("cliente");
            }
        });
        function mostrarAbonos(){
        //By Jenice
             $.post('{!! url("/cuentas/getAbonosByPago") !!}', {
                 _token: $('meta[name=csrf-token]').attr('content'),
                 idpago: $(".idpago").val()
             }).done(function (data) {
                    var result = "";
                    var abonos = data[0];
                    var totales = data[1];
                    var infoPago = data[2];
                    var saldo = 0;
                 if(abonos.length>0){

                    for(var i=0;i<abonos.length;i++){
                        result += "<tr><td>"+abonos[i].fecha+" "+abonos[i].hora+"</td><td class='dinero'>"+abonos[i].cantidad+"</td><td class='dinero'>"+abonos[i].saldo+"</td><tr>";
                        saldo = abonos[i].saldo;
                    }

                 }else{
                    result = "<tr><td colspan='2'>No hay abonos registrados.</td><tr>";
                 }

                 var a = parseFloat(infoPago.pago);
                 var b = (isNaN(parseFloat(totales.cantidad)))? 0:parseFloat(totales.cantidad);

                 $("#tPago").html("$"+thousands(a.toFixed(2)));
                 $("#tAbonado").html("$"+thousands(b.toFixed(2)));
                 $("#tSaldo").html("$"+thousands(saldo.toFixed(2)));

                 $("#tbHistorialAbonos tbody").html(result);
                 $("#modalAbonos .pagina").toggle();
                 setDineroFormat();

             }).fail(function(xhr, textStatus, errorThrown){
                errorShow(xhr.responseText);
            });
        }
        function checarRecibo(valor){
            if(valor=="factura"){
                $("#clienterecibo option[data-factura='0']").hide();
                $("#aseguradorarecibo").parent().show();
                $("#numfactdiv").show();
            }else{
                $("#clienterecibo option").show();
                $("#aseguradorarecibo").parent().hide();
                $("#numfactdiv").hide();
            }
        }

        function repoProductos()
        {
            $("#modalProds").modal("toggle");
            var fecha = new Date();
            fecha = (fecha.getUTCMonth() + 1) + "/" + fecha.getDate() + "/" + fecha.getFullYear() + " " +fecha.getHours() + ":" + fecha.getMinutes();
            var nombre = fecha.replace("/","_");
            nombre= nombre.replace("/","_");
            nombre = nombre.replace(" ","");
            nombre = nombre.replace(":","");

            var inicio = $("#fecha_inicio").datepicker({ dateFormat: 'YYYY-mm-dd'}).val();
            var final = $("#fecha_final").datepicker({ dateFormat: 'YYYY-mm-dd'}).val();

            console.log("Inicio: "+ inicio+"\n Final: "+ final);
            $.post('{!! url("/productos/reporte") !!}', {
                _token: $('meta[name=csrf-token]').attr('content'),
                fecha:fecha,
                inicio: inicio,
                final: final,
                nombre:nombre
            })
                    .done(function(data)
                    {
                        if(data)
                        {
                            $("#reporte-link").attr("href", "productos/reporte2/"+nombre);
                            $("#reporte-link").addClass("show").removeClass("invisible");

                            window.open("productos/reporte2/"+nombre); //post a ventasController(a)buscarReporte, devuelve el reporte en la vista de pdf
                        }
                    })
                    .fail(function()
                    {
                        alert("No se pudo generar el reporte, intentelo de nuevo");
                    });
        }
        function generarPDF(x)
        {
            var id = $(x).attr('presupuesto');

            $.post('{!! url("/productos/generarPDF") !!}', {
                _token: $('meta[name=csrf-token]').attr('content'),
                id: id
            })
                .done(function (data) {
                    window.open("presupuestos/pdf/"+data);
                })
                .fail(function () {
                    swal("Error", "Se pudo conectar con el servidor", "error");
                });

        }
        function generarOrden(x){
            var id_material = $(x).attr('material');
            var id_producto = $(x).attr('cuenta');
            $("#modalMateriales").modal("toggle");
            swal({
                title: "Generar Orden de Compra",
                text: "Ingresa la cantidad del material del cual deseas la orden de compra",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: true,
                inputPlaceholder: "Cantidad"
                }).then(function(inputValue){
                  if (inputValue === false)
                      return false;
                  if (inputValue === "")
                  {
                      swal.showInputError("You need to write something!");
                      return false
                  }

                  $.post('{!! url("/cuentas/generarOrdenCompra") !!}', {
                      _token: $('meta[name=csrf-token]').attr('content'),
                      id_material: id_material,
                      id_producto: id_producto,
                      cantidad: inputValue
                  })
                  .done(function (data) {
                      console.log(data);
                      window.open("cuentas/verOrden/template_orden_compra");
                  })
                  .fail(function () {
                      swal("Error", "Se pudo conectar con el servidor", "error");
                  });
                });


        }

        function abrirEnviar(x){
            $("#enviar-correo").val("");
            $("#modalEnviar").modal("show");
            var datos = $(x).parent().parent().data("datos");
            var cliente = datos['id_cliente'];
            var uuid = datos['id_factura'];
            console.log("buscar contactos");
            $.post('{!! url("/general/getContactosByCliente") !!}', {
                _token: $('meta[name=csrf-token]').attr('content'),
                id:cliente
            }).done(function(data){

                /*var result = "<input type='checkbox' class='chkEmail' value='"+datos["email"]+"'> "+datos["cliente"]+" <small>"+datos["email"]+"</small><br>";

                for(var i=0;i<data.length;i++){
                    result += "<input type='checkbox' class='chkEmail' value='"+data[i].email_personal+"'> "+data[i].nombre+" <small>"+data[i].email_personal+"</small><br>";
                }*/

                result = '';
                for(var i=0;i<data.length;i++){
                    result += "<input type='checkbox' class='chkEmail' value='"+data[i].email+"'> "+data[i].email+"<br>";
                }

                $("#contact-list").html(result);
                $(".chkEmail").on("click",function(){
                    var cadenaMails = $("#enviar-correo").val();
                    var email = $(this).val();
                    //console.log(email);
                    if($(this).is(':checked')){
                        //se quiere agregar
                        if (cadenaMails.indexOf(email)==-1) {
                        //si no esta, lo agregamos
                            if(cadenaMails=="")
                             cadenaMails += email;
                            else
                            cadenaMails += ", "+email;
                        }
                    }
                    else{
                        //se quiere quitar, se usara split por si se repite
                        var partes = cadenaMails.split(",");
                        for(var i=0;i<partes.length;i++){
                            if(partes[i].trim()==email){
                                partes.splice(i,1);
                            }
                        }
                        cadenaMails = partes.join(", ");

                    }
                    $("#enviar-correo").val(cadenaMails);
                });
            })
            .fail(function(xhr, textStatus, errorThrown)
           {
               errorShow(xhr.responseText);
           });
            $("#enviar-correo").attr("uuid", uuid);
        }
        function cancelar(obj){
            var datos = $(obj).parent().parent().data("datos");
            swal({
                    title: "Cancelar",
                    text: "Esta seguro de cancelar?",
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonText: "No",
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Continuar",
                    closeOnConfirm: true
                    }).then(function(){
                      $.post('{!! url("/cuentas/cancelarPago") !!}', {
                          _token: $('meta[name=csrf-token]').attr('content'),
                          idpago: datos["id"],
                          recibo: datos["recibo"]
                      })
                      .done(function(data)
                      {
                          location.reload();
                      })
                      .fail(function(xhr, textStatus, errorThrown)
                         {
                             errorShow(xhr.responseText);
                         });
                    });

        }
        function pago(obj){
            var datos = $(obj).parent().parent().data("datos");
            $("#modalPagar").modal('show');
            $(".orden").text(datos.orden);
            $("#vencimiento").val(datos.fecha);
            $("#envio").val(datos.fecha_envio);
            $("#contrarecibo").val(datos.fecha_contrarecibo);
            $("#pago").val(datos.fecha_pago_cliente);
            $("#forma").val(datos.forma_pago);
            $("#refpago").val(datos.num_papeleta);
            $("#refdepo").val(datos.ref_depo);
            $(".idpago").val(datos.id);

        }
        function abono(obj){
            var datos = $(obj).parent().parent().data("datos");
            $("#modalAbonos").modal('show');
            $(".orden").text(datos.orden);
            $(".idpago").val(datos.id);
            $('#pageAbonar').css('display','');
            $('#pageHistorial').removeClass('oculto');
            mostrarAbonos();
        }
        function generar(obj){
            var cliente = "";
            var datos = $(obj).parent().parent().data("datos");
            console.log(datos);
            var conceptos = [];
            var distintos = 0;
            checarRecibo($("#recibo").val());
            $(".chek-list:checked").each(function(){
                console.log(cliente+" != "+$(this).attr("cliente"));
                if(cliente!=$(this).attr("cliente") && cliente!="") {
                    distintos = 1;
                }
                else {
                    cliente = $(this).attr("cliente");
                    conceptos.push($(this).attr("idpago"))
                }
            });
            if(conceptos.length == 0)
            {
                conceptos.push($(obj).siblings('input').attr("idpago"));
            }
            if(distintos==0)
            {
                $(".cliente").text(datos.cliente);
                var data = JSON.stringify(conceptos);
                $(".conceptos").val(data);
                $("#modalGenerar").modal("show");
                $("#generarRecibo").attr("idpagos", conceptos);
                $("#clienterecibo").val(datos.id_cliente);
                $("#aseguradorarecibo").val(datos.aseguradoraCliente);
            }
            else{
                swal("Atencion", "Los conceptos deben corresponder al mismo cliente", "warning");
            }
        }

    </script>