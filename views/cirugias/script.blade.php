<script>
    $(document).on('ready',function() {
        $('#contenido .input-fecha').datepicker({
            format: "yyyy-mm-dd",
            language: "es",
            todayBtn: "linked",
            todayHighlight: true,
            autoclose:true,
        });

        /*********************************************** Listas Search **********************************************/

        //Auxiliarea
        $(".filterinput").on("click",function(){
            var lista = $(this).data("lista");
            $("#"+lista+" li").on("click",function(){
                $("#"+lista).addClass('invisible').removeClass('show');
                var value = $(this).attr("value");
                $("select[data-lista='"+lista+"']").val(value).trigger("onchange");
                $(".filterinput[data-lista='"+lista+"']").val($(this).text());
            })
            $("#"+lista).addClass('show').removeClass('invisible');
        });
        $(".filterinput").change( function () {
            var filter = $(this).val();
            var lista = $(this).data("lista");
            if (filter) {
                $("#"+lista).find("li:not(:contains(" + filter + "))").addClass("invisible").removeClass("show");
                $("#"+lista).find("li:contains(" + filter + ")").addClass("show").removeClass("invisible");
            } else {
                $("#"+lista).find("li").addClass("show").removeClass("invisible");
            }
        }).keyup( function () {
            $(this).change();
            //Ocultar añadido
            $(".lista").addClass('invisible').removeClass('show');
        });

        //Materiales
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

        //ocultar fecha si no esta encendido
        $('#cryo').on( "switchChange.bootstrapSwitch", function(){
            var cryo = ($("#cryo").prop("checked"))? 1:0;
            if (cryo == 1) {
                $('.fecha_cryo').removeClass('hidden');
            } else {
                $('.fecha_cryo').addClass('hidden');
            }
        });

    });

    function agregarAuxiliar(){
        var select = $("#select-auxiliar option:selected").text();
        var lista = $("input[data-lista='lista']").val();
        /*console.log('select '+select);
        console.log('lista '+lista);*/

        // $.trim remueve espacios en blanco al comienzo y al final
        if($.trim(select) == $.trim(lista)) {

            var ids = [];
            $('#seccion-auxiliares tr').each(function(){
                ids.push($(this).attr('id'));
            });

            var id = $("#select-auxiliar").val();

            if ($.inArray(id,ids) == -1){

                var auxiliar = $("#select-auxiliar option:selected").text();
                var nombre = $("#select-auxiliar option:selected").attr('nombre');
                var apellido = $("#select-auxiliar option:selected").attr('apellido');

                var ay = "";
                ay += "<tr id=" + id + ">";
                    ay += "<td>"+nombre+" "+apellido+"</td>";
                    ay += "<td><div class='minus col-xs-1' onclick='quitarElemento(this)'><i class='fa fa-times' title='Quitar solo este producto'></i></div></td>";
                ay += "</tr>";
                $("#seccion-auxiliares").append(ay);

                $("input[data-lista='lista']").val("");
            }else{
                return swal("Espere","Este Auxiliar ya esta agregado", "warning");
            }
        }else {
            return swal("Espere", "Seleccione un Auxiliar valido", "warning");
        }
    }

    function agregarMaterial(){
        var select = $("#select-material option:selected").text();
        var lista = $("input[data-lista2='lista2']").val();

        // $.trim remueve espacios en blanco al comienzo y al final
        if($.trim(select) == $.trim(lista)) {

            var ids = [];
            $('#seccion-materiales tr').each(function(){
                ids.push($(this).attr('id'));
            });

            var id = $("#select-material").val();

            if ($.inArray(id,ids) == -1){
                var cantidad = parseInt( $("#cantidad").val());
                if(cantidad<1 || isNaN(cantidad)){
                    return swal("Espere","Indique una cantidad", "warning");
                }

                var material = $("#select-material option:selected").text();
                var descripcion = $("#select-material option:selected").attr("descripcion");

                var precio = $("#select-material option:selected").attr("precio");
                precio = parseFloat(precio);
                var pre = precio.toFixed(2);
                var precioFormato = formatNumber.new(pre, "$");

                var importe = parseFloat(precio * cantidad);
                var imp = importe.toFixed(2);
                var importeFormato = formatNumber.new(imp, "$");

                $(".seccion-totales").removeClass("hidden");
                var ay = "";
                ay += "<tr id=" + id + ">";
                    ay += "<td cantidad='"+cantidad+"' class='cantidad'>"+cantidad+"</td>";
                    ay += "<td>"+material+"</td>";
                    ay += "<td>"+descripcion+"</td>";
                    ay += "<td>"+precioFormato+"</td>";
                    ay += "<td importe='"+importe+"' class='importe'>"+importeFormato+"</td>";
                    ay += "<td><div class='minus col-xs-1' onclick='quitarElemento(this)'><i class='fa fa-times' title='Quitar solo este producto'></i></div></td>";
                ay += "</tr>";

                $("#seccion-materiales").append(ay);
                $("#cantidad").val("");
                calcularTotal();

                $("input[data-lista2='lista2']").val("");
            }else{
                return swal("Espere","Este Material ya esta agregado", "warning");
            }
        }else {
            return swal("Espere", "Seleccione un Material valido", "warning");
        }
    }

    function quitarElemento(x){
        $(x).parents('tr').remove();
        calcularTotal();
    }

    function calcularTotal(){
        var total = 0;
        var subtotal=0;
        var iva=0;
        var porentajeIva=0;
        var divisa = "Pesos MXN";
        var totalLetra = "";

        porentajeIva = ($("#porcentajeIva").prop("checked"))? .16:0;
        divisa = ($("#divisaValor").prop("checked"))? "Dolares USD":"Pesos M.N.";

        $("#seccion-materiales .importe").each(function() {
            subtotal += parseFloat($(this).attr('importe'));
        });

        iva = subtotal*porentajeIva;
        total = iva+subtotal;

        var sub = subtotal.toFixed(2);
        var subtotalFormato = formatNumber.new(sub, "$");

        var iv = iva.toFixed(2);
        var ivaFormato = formatNumber.new(iv, "$");

        var tot = total.toFixed(2);
        var totalFormato = formatNumber.new(tot, "$");


        $(".subtotal").html(subtotalFormato);
        $("#subtotal").val(subtotal);
        $(".iva").html(ivaFormato);
        $("#iva").val(iva);
        $(".total").html(totalFormato);
        $("#total").val(total);
        totalLetra = nn(total);
        $("#conletra").html(totalLetra+" "+divisa);
    }

    function guardarCirugia(){

        var materiales = [];
        var conceptos = {};
        $("#seccion-materiales tr").each(function(){
            var id = $(this).attr("id");
            if(id!="" && id!=undefined){
                conceptos = {
                    id:id,
                    cantidad:$(this).find('.cantidad').attr('cantidad'),
                };
                materiales.push(conceptos);
            }
        });

        var auxiliares = [];
        var auxiliar = {};
        $("#seccion-auxiliares tr").each(function(){
            var id = $(this).attr("id");
            if(id!="" && id!=undefined){
                auxiliar = {
                    id:id,
                };
                auxiliares.push(auxiliar);
            }
        });

        console.log(auxiliares);
        if (materiales == ''){materiales = 'vacio';}
        if (auxiliares == ''){auxiliares = 'vacio';}

        var id_servicio = $("#id_servicio").val();
        var convenio = $("#convenio").val();
        var renta = $("#renta").val();
        var recibo = $("#recibo").val();
        var laser = $("#laser").val();
        var cryo = ($("#cryo").prop("checked"))? 1:0;
        var fecha_cryo = $("#fecha_cryo").val();
        var subtotal = $("#subtotal").val();
        var iva = $("#iva").val();
        var total = $("#total").val();

        waitingDialog.show('Guardando...', {dialogSize: 'sm', progressType: 'warning'});
        $.ajax({
            type: 'POST',
            url: '/cirugias/guardar',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                id_servicio: id_servicio,
                convenio: convenio,
                renta: renta,
                recibo: recibo,
                laser: laser,
                cryo: cryo,
                fecha_cryo: fecha_cryo,
                subtotal: subtotal,
                iva: iva,
                total: total,
                auxiliares: auxiliares,
                materiales: materiales,

            },success: function (data) {
                waitingDialog.hide();
                swal("Guardado","","success");
                setTimeout("location.href = '/cirugias'",0);
            },error: function (ajaxContext) {
                waitingDialog.hide();
                swal("Espere","Algo salio mal, reintente de nuevo","warning");
            }
        });

    }

</script>