<script>
    function agregarEstudio(){
        var select = $("#select-estudio option:selected").text();
        var lista = $("input[data-lista='lista']").val();

        // $.trim remueve espacios en blanco al comienzo y al final
        if($.trim(select) == $.trim(lista)) {

            var ids = [];
            $('#seccion-estudios tr').each(function(){
                ids.push($(this).attr('id'));
            });

            var id = $("#select-estudio").val();

            if ($.inArray(id,ids) == -1){

                var estudio = $("#select-estudio option:selected").text();
                var nombre = $("#select-estudio option:selected").attr('nombre');

                var ay = "";
                ay += "<tr id=" + id + ">";
                ay += "<td>"+nombre+"</td>";
                ay += "<td><div class='minus col-xs-1' onclick='quitarElemento(this)'><i class='fa fa-times' title='Quitar solo este producto'></i></div></td>";
                ay += "</tr>";
                $("#seccion-estudios").append(ay);

                $("input[data-lista='lista']").val("");
            }else{
                return swal("Espere","Este Estudio ya esta agregado", "warning");
            }
        }else {
            return swal("Espere", "Seleccione un Estudio valido", "warning");
        }
    }

    function quitarElemento(x){
        $(x).parents('tr').remove();
    }

    function guardarProveedor(){

        var estudios = [];
        var estudio = {};
        $("#seccion-estudios tr").each(function(){
            var id = $(this).attr("id");
            if(id!="" && id!=undefined){
                estudio = {
                    id:id,
                };
                estudios.push(estudio);
            }
        });

        if (estudios == ''){estudios = 'vacio';}
        var nombre = $("#nombre").val();
        var calle = $("#calle").val();
        var no_ext = $("#no_ext").val();
        var no_int = $("#no_int").val();
        var colonia = $("#colonia").val();
        var tel = $("#tel").val();
        var accion = $(".guardarProveedor").attr("accion");

        /*if(nombre == ""){swal("Espere","El nombre del proveedor es necesario","Warning");}*/

        if (accion != 'e'){

            $.ajax({
                type: 'POST',
                url: '/dr_basico/proveedores/guardar',
                data: {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    nombre: nombre,
                    calle: calle,
                    no_ext: no_ext,
                    no_int: no_int,
                    colonia: colonia,
                    tel: tel,
                    estudios: estudios,

                },success: function (data) {
                    swal("Guardado","","success");
                    setTimeout("location.href = '/dr_basico/proveedores'",0);
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

        }else{
            var id = $(".guardarProveedor").attr("id");
            $.ajax({
                type: 'POST',
                url: '/dr_basico/proveedores/editar/'+id,
                data: {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    nombre: nombre,
                    calle: calle,
                    no_ext: no_ext,
                    no_int: no_int,
                    colonia: colonia,
                    tel: tel,
                    estudios: estudios,

                },success: function (data) {
                    swal("Actualizado","","success");
                    setTimeout("location.href = '/dr_basico/proveedores'",0);
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

    }
</script>