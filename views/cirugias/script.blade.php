<script>
    $(document).on('ready',function() {
        $('#contenido .input-fecha').datepicker({
            format: "yyyy-mm-dd",
            language: "es",
            todayBtn: "linked",
            todayHighlight: true,
            autoclose:true,
        });

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

        var hoy = moment(new Date()).format('YYYY-MM-DD');
        $('#fecha_pago').val(hoy);

        //ocultar fecha si no esta encendido
        $('#status').on( "switchChange.bootstrapSwitch", function(){
            var status = ($("#status").prop("checked"))? 1:0;
            if (status == 1) {
                $('.fecha_cryo').removeClass('hidden');
            } else {
                $('.fecha_cryo').addClass('hidden');
            }
        });

        @if(isset($cirugia))
            $('.guardarCirugia').attr('accion','e');
            $('.guardarCirugia').attr('id_cirugia','{!! $cirugia->id !!}');

            $('#convenio').val('{!! $cirugia->convenio !!}');
            $('#renta2').val('{!! $cirugia->renta !!}');
            $('#renta').val('{!! $cirugia->renta !!}');
            $('#recibo').val('{!! $cirugia->recibo !!}');
            $('#plaza').val('{!! $cirugia->plaza !!}');
            if ('{!! $cirugia->status !!}' == '0'){
                $("#status").trigger("click");
                $('#fecha_pago').val('');
            }else{
                $('#fecha_pago').val('{!! $cirugia->fecha_pago !!}');
            }
            if ('{!! $cirugia->iva !!}' != '0.00'){$("#porcentajeIva").trigger("click");}

            $('#comentarios').val('{!! $cirugia->comentarios !!}');

            //cargar auxiliares para editar
            @if(count($auxiliaresE))
                <?php  ?>
                    var aux = "";
                @foreach($auxiliaresE as $auxiliar)
                    var id = '{!! $auxiliar->id_auxiliar !!}';
                    var nombre = '{!! $auxiliar->nombre !!}';
                    var apellido = '{!! $auxiliar->apellido !!}';

                    aux += "<tr id="+id+">";
                    aux += "<td>"+nombre+" "+apellido+"</td>";
                    aux += "<td><div class='minus col-xs-1' onclick='quitarElemento(this)'><i class='fa fa-times' title='Quitar solo este producto'></i></div></td>";
                    aux += "</tr>";
                @endforeach
                $("#seccion-auxiliares").append(aux);
            @endif

            //cargar materiales para editar
            @if(count($materialesE))
                <?php  ?>
                    var mate = "";
                @foreach($materialesE as $material)
                    var id = '{!! $material->id_material !!}';
                    var cantidad = '{!! $material->cantidad !!}';
                    var material = '{!! $material->nom_prod !!}';
                    var descripcion = '{!! $material->descripcion !!}';
                    var precio = '{!! $material->precio !!}';

                    precio = parseFloat(precio);
                    var pre = precio.toFixed(2);
                    var precioFormato = formatNumber.new(pre, "$");

                    var importe = parseFloat(precio * cantidad);
                    var imp = importe.toFixed(2);
                    var importeFormato = formatNumber.new(imp, "$");

                    mate += "<tr id=" + id + ">";
                    mate += "<td cantidad='"+cantidad+"' class='cantidad'>"+cantidad+"</td>";
                    mate += "<td>"+material+"</td>";
                    mate += "<td>"+descripcion+"</td>";
                    mate += "<td>"+precioFormato+"</td>";
                    mate += "<td importe='"+importe+"' class='importe'>"+importeFormato+"</td>";
                    mate += "<td><div class='minus col-xs-1' onclick='quitarElemento(this)'><i class='fa fa-times' title='Quitar solo este producto'></i></div></td>";
                    mate += "</tr>";
                @endforeach
                $("#seccion-materiales").append(mate);
                calcularTotal();
            @endif
        @endif

    });

    function agregarAuxiliar(){
        var select = $("#select-auxiliar option:selected").text();
        var lista = $("input[data-lista='lista']").val();


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
        $('.seccion-totales').removeClass('hidden');
    }

    function guardarCirugia(){
        var accion = $('.guardarCirugia').attr('accion');
        var id_cirugia = $('.guardarCirugia').attr('id_cirugia');

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

        /*console.log(auxiliares);*/
        if (materiales == ''){materiales = 'vacio';}
        if (auxiliares == ''){auxiliares = 'vacio';}

        var id_servicio = $("#id_servicio").val();
        var convenio = $("#convenio").val();
        var renta = $("#renta").val();
        var recibo = $("#recibo").val();
        var plaza = $("#plaza").val();
        var status = ($("#status").prop("checked"))? 1:0;
        var fecha_pago = $("#fecha_pago").val();
        var subtotal = $("#subtotal").val();
        var iva = $("#iva").val();
        var total = $("#total").val();
        var comentarios = $("#comentarios").val();
        console.log(fecha_pago);

        waitingDialog.show('Guardando...', {dialogSize: 'sm', progressType: 'warning'});
        $.ajax({
            type: 'POST',
            url: '/dr_basico/cirugias/guardar',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                accion: accion,
                id_cirugia: id_cirugia,
                id_servicio: id_servicio,
                convenio: convenio,
                renta: renta,
                recibo: recibo,
                plaza: plaza,
                status: status,
                fecha_pago: fecha_pago,
                subtotal: subtotal,
                iva: iva,
                total: total,
                auxiliares: auxiliares,
                materiales: materiales,
                comentarios: comentarios,

            },success: function (data) {
                waitingDialog.hide();
                swal("Guardado","","success");
                setTimeout("location.href = '/dr_basico/cirugias'",0);
            },error: function (ajaxContext) {
                waitingDialog.hide();
                swal("Espere","Algo salio mal, reintente de nuevo","warning");
            }
        });
    }

</script>