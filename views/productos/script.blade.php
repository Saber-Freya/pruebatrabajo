<script>
    $(document).ready(function () {
        /*$("#img_producto").PictureCut({
            InputOfImageDirectory: "picProducto",
            PluginFolderOnServer: "/facturacion/js/piccut/",
            FolderOnServer: "/facturacion/img/uploads/img_producto/",
            EnableCrop: true,
            CropWindowStyle: "bootstrap",
            EnableButton: false,
            CropOrientation: false,
            CropModes: {bannerDeli: true},
            EnableMaximumSize: true,
            MaximumSize: 1024
        });*/
        $('.currency').blur(function () {
            $('.currency').formatCurrency();
        });
        $('.currency').formatCurrency();
        $("#costo_produccion").val($("#results").attr("cp")).formatCurrency();
        $("#precio_sugerido").val($("#results").attr("ps")).formatCurrency();
    });
    function calcularCostos(elem) {
        var totalote = 0;
        $(".recuadro").each(function () {
            var exis = $(elem).parents(".recuadro").attr("existencia");
            var valor = parseFloat($(elem).val());
            var sum = parseInt(exis) + parseFloat($(elem).attr("value"));
            console.log("if( " + valor * parseInt($("#existencia").val()) + " > " + sum + " ) ");
            if (valor * parseInt($("#existencia").val()) > sum) //lista esta validacion
            {
                $(elem).val(exis / parseInt($("#existencia").val()));
                return swal("Error", "No hay tanto de este material", "error");
            } else {
                var total = valor * parseFloat($(elem).siblings(".precioU").html().replace("$", ""));
                $(elem).siblings(".totalito").html("$" + parseFloat(total).toFixed(4));
            }
        });
        $(".recuadro").each(function () {
            totalote += parseFloat($(this).find(".totalito").html().replace("$", ""));
        });
        $("#costo_produccion").val(totalote).formatCurrency();
        var ps = totalote * 1.68;
        $("#precio_sugerido").val(ps).formatCurrency();
    }
    function preGuardado(x) {
        var foto = $("#picProducto").val();
        $("#foto").val(foto);
        var v1 = $("#costo_produccion").asNumber();
        $("#costo_produccion").val(v1);
        v1 = $("#precio_sugerido").asNumber();
        $("#precio_sugerido").val(v1);
        v1 = $("#precio_venta").asNumber();
        $("#precio_venta").val(v1);
        var id = $("#codigo").attr("prod");
        var mat_ids = [];
        var cantidad_mat = [];
        $(".recuadro").each(function () {
            mat_ids.push($(this).attr("id"));
            var cant = $(this).children(".cantidad").val();
            var exis = $(this).attr("existencia");
            var this_cant = cant * $("#existencia").val();
            var mult = parseInt($("#existencia").val());
            if (mult == 0)                mult = 1;
            cantidad_mat.push(cant * mult);
        });
        $.post('/facturacion/productos/update', {
            _token: $('meta[name=csrf-token]').attr('content'),
            id: id,
            codigo: $("#codigo").val(),
            nombre: $("#nombre").val(),
            descripcion: $("#descripcion").val(),
            tipo: $("#tipo").val(),
            existencia: parseInt($("#existencia").val()),
            costo_produccion: $("#costo_produccion").val(),
            precio_sugerido: $("#precio_sugerido").val(),
            precio_venta: $("#precio_venta").val(),
            foto: foto,
            mat_ids: mat_ids,
            cantidad_mat: cantidad_mat
        }).done(function (data) {
            console.log(data);
            if (data) {
                swal("Listo", "Producto Editado con exito", "success");
                location.reload(true);
            } else                    swal("error");
        }).fail(function () {
            swal("Error", "No se pudo completar la edicion de producto", "error");
        });
    }
    function quitarProd(x) {
        $(x).parents('.recuadro').remove();
        calcularCostos($(x).siblings('.cantidad'));
    }    //llamar al onchange del select Material
    function agregarMat() {
        var id = $("#select-material").val();
        if (id == "0")            return;
        var precio = $("#select-material option:selected").attr('precio');
        var cantidad = $("#select-material option:selected").attr('existencia');
        var text = $("#select-material option:selected").text();
        var ids = [];
        $('.recuadro').each(function () {
            console.log(ids);
            ids.push($(this).attr('id'));
        });
        if ($.inArray(id, ids) == -1) {
            var res = "";
            res += "<div id='" + id + "' class='row recuadro' existencia='" + cantidad + "'>";
            res += "<div class='minus col-xs-1' onclick='quitarProd(this)'><i class='fa fa-times'></i></div>";
            res += "<div class='col-xs-5'>" + text + "</div>";
            res += "<input type='text' class='col-xs-2 cantidad formito' value='0' onkeyup='calcularCostos(this)'>";
            res += "<div class='col-xs-2 precioU'>$" + precio + "</div>";
            res += "<div class='col-xs-2 totalito'>$0.00</div></div>";
            $("#seccion_mat").append(res);
            $('.recuadro').each(function () {
                ids += this.id;
            });
        } else            swal("Error", "Ya existe el material seleccionado", "error");
    }
    function calcularDiv() {
        var pv = $("#precio_venta").asNumber();
        var co = $("#costo_produccion").asNumber();
        console.log(co);
        $("#division").val(pv / co);
    }
    function buscar() {
        $.post('/productos/buscar', {
            _token: $('meta[name=csrf-token]').attr('content'),
            numero: $("#busqueda").val()
        }).done(function (data) {
            var newdoc = document.open("text/html", "replace");
            newdoc.write(data);
            newdoc.close();
        }).fail(function () {
            swal("No se logro la busqueda");
        });


    }</script>