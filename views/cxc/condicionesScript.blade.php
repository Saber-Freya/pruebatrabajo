<script>
/*********Condiciones de pago**************************************/
    $(document).ready(function(){
        var hoy = new Date();
        $(".fechaCondicion #fecha-inicial").val("");
        $(".fechaCondicion #fecha-inicial").datepicker({
            format      : "yyyy-mm-dd",
            language    : "es",
            startDate   : hoy,
            autoclose   : true,
            todayHighlight: true,
            changeDate: function(dateText) {
                console.log("cambio de fecha inicial "+dateText);
                calcularCredito();
            }
        });
    });

    function calcularCredito() {
        console.log("calcularCredito");
        if($(".fechaCondicion #fecha-inicial").val()=="" || !$(".fechaCondicion #fecha-inicial").is(":visible"))
            return;

        var cantidadFechas = parseInt($("#cantidad-fechas").val());
        var periodoFechas = $("#periodo-fechas").val();
        $('.btn-guardar-fechas').addClass('show').removeClass('invisible');


        var i=1;
        var date = $(".fechaCondicion #fecha-inicial").datepicker('getDate');
        var hoy = new Date();
        hoy.setHours(0,0,0,0);

        if (hoy > date)
            return swal("Uppps", "La Fecha Inicial no puede ser menor que la fecha del día de hoy, ", "error");


        $('.btn-guardar-fechas').addClass('invisible').removeClass('show');
        $('.btn-pasar-cuenta').addClass('show').removeClass('invisible');
        $('.instrucciones').addClass('show').removeClass('invisible');

        var total = $('#totalote').text();
        total = total.replace("$","").replace(",","");
        total = parseFloat(total);

        var cantidadFechas = parseInt($('#cantidad-fechas').val());

        var monto = total / cantidadFechas;
        var saldo = total;
        var s = "";



        for(i=1; i<cantidadFechas+1; i++){

            s += "<div class='col-sm-4 col-xs-6' align='center'><input id='txtFecha"+i+"' class='form-controlito monto' value='$"+monto+"' readonly='readonly' data-saldoBefore='"+saldo+"'><div class='fechis fecha"+i+"'></div></div>";
            s += "";
            saldo -= monto;
        }

        $('.monto').formatCurrency();
        $("#calendarios").html(s);

        /*Para calcular el interes*/
        var interes = 0;
        var interesPercent = $("#interes-mensual").val();
        var interesDate = $("#fecha-inicial").datepicker('getDate');
        interesDate.setMonth(interesDate.getMonth()+1);



        //Switch que asigna las fechas de las fechas ya puestas
        for(i=1; i<cantidadFechas+1; i++)
        {


            $(".fecha"+i).datepicker({
                format      : "yyyy-mm-dd",
                language    : "es",
                changeMonth: true,
                changeYear: true,
                todayHighlight: true
            }).datepicker('setDate', date);

            if(periodoFechas=="sem"){
                date.setDate(date.getDate()+7);
            }
            else if(periodoFechas=="qui"){
                date.setDate(date.getDate()+15);
            }
            else if(periodoFechas=="men"){
                date.setMonth(date.getMonth()+1);
            }
            else{
                return;
            }

            if(date>interesDate){
                var valor = $("#txtFecha"+i).data("saldobefore");
                var nuevo = valor*(interesPercent/100);
                interes += nuevo;
                interesDate.setMonth(interesDate.getMonth()+1);
            }


        }
        var totalWInteres = total+interes;
        $("#intereses").text("$"+thousands(interes.toFixed(2)));
        $("#deuda-intereses").text("$"+thousands(totalWInteres.toFixed(2)));
         var montoTmp = totalWInteres/cantidadFechas;
         montoTmp = thousands(parseFloat(montoTmp).toFixed(2));
         $(".monto").val("$"+montoTmp);


    }

    function guardarPagos() {
        var id = $("#totalote").attr("presupuesto");
        var fechas = [];
        var total = $("#totalote").text().replace("$","").replace(",","");
        var interes = $("#interes").text().replace("$","").replace(",","");
        var id_presupuesto = id;
        var monto = $('.monto:first').val().replace("$","").replace(",","");
        var cliente = $('#id_cliente').attr('data-value');

        $(".fechis").each(function(){
            var fecha = $(this).datepicker( "getDate" );
            //A mano :(
            var y = fecha.getFullYear();
            var m = fecha.getMonth();
            var d = fecha.getDate();
            if(d<10)
                d = "0"+d;
            if(m<10)
                m = "0"+m;
            fechas.push(y+"-"+m+"-"+d);
            //fechas.push(fecha);
        });

        console.log(fechas);

        $.post('{!! url("/cuentas/guardarPagos") !!}', {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    fechas: fechas,
                    pago: monto,
                    id_presupuesto: id_presupuesto,
                    total: total,
                    cliente:cliente,
                    interes:interes
                })
                .done(function (data) {
                     console.log("Venta: "+data[0]);
                     console.log("Pagos: "+data[1]);
                     swal({
                        title:"Listo",
                        text: "Se guardó la cita con exito",
                        type: "success"
                     }).then(function(){
                        document.location.href = '{!! url("/servicios") !!}';
                     })

                })
                .fail(function(xhr, textStatus, errorThrown)
                   {
                       errorShow(xhr.responseText);
                   });
    }

    function abrirCondiciones(presupuesto){
        var total = presupuesto.costo;
        total = parseFloat(total);
        $("#modalAceptar").modal("toggle");
        $("#totalote").text("$"+total.toFixed(2));
        $("#intereses").text("$0.00");
        $("#deuda-intereses").text(total);
        $("#interes-mensual").val(0);
        $("#totalote").attr("presupuesto", presupuesto.id);
        $("#modalCondiciones").modal("show");
    }
</script>