<script>
    function agregarPadecimiento(){
        var select = $("#select-padecimiento option:selected").text();
        var lista = $("input[data-lista='listaM']").val();

        // $.trim remueve espacios en blanco al comienzo y al final
        if($.trim(select) == $.trim(lista)) {

            var ids = [];
            $('#seccion-padecimientos tr').each(function(){
                ids.push($(this).attr('id'));
            });

            var id = $("#select-padecimiento").val();

            if ($.inArray(id,ids) == -1){

                var padecimiento = $("#select-padecimiento option:selected").text();
                var nombre = $("#select-padecimiento option:selected").attr('nombre');

                var ay = "";
                ay += "<tr id=" + id + ">";
                ay += "<td>"+nombre+"</td>";
                ay += "<td><div class='minus col-xs-1' onclick='quitarElemento(this)'><i class='fa fa-times' title='Quitar solo este producto'></i></div></td>";
                ay += "</tr>";
                $("#seccion-padecimientos").append(ay);

                $("input[data-lista='lista']").val("");
            }else{
                return swal("Espere","Este Padecimiento ya esta agregado", "warning");
            }
        }else {
            return swal("Espere", "Seleccione un Padecimiento valido", "warning");
        }
    }

    function quitarElemento(x){
        $(x).parents('tr').remove();
    }

    function guardarMedicamento(){

        var padecimientos = [];
        var padecimiento = {};
        $("#seccion-padecimientos tr").each(function(){
            var id = $(this).attr("id");
            if(id!="" && id!=undefined){
                padecimiento = {
                    id:id,
                };
                padecimientos.push(padecimiento);
            }
        });

        if (padecimientos == ''){padecimientos = 'vacio';}
        var componente = $("#componente").val();
        var marca = $("#marca").val();
        var receta = ($("#receta").prop("checked"))? 0:1;
        var accion = $(".guardarMedicamento").attr("accion");

        if (accion != 'e'){
                $.ajax({
                    type: 'POST',
                    url: '/dr_basico/medicamentos/guardar',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        componente: componente,
                        marca: marca,
                        receta: receta,
                        padecimientos: padecimientos,

                    },success: function (data) {
                        swal("Guardado","","success");
                        if (accion != 'r') {
                            setTimeout("location.href = '/dr_basico/medicamentos'", 0);
                        }else{

                            window.location.reload();
                        }
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
            var id = $(".guardarMedicamento").attr("id");
            $.ajax({
                type: 'POST',
                url: '/dr_basico/medicamentos/editar/' + id,
                data: {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    componente: componente,
                    marca: marca,
                    receta: receta,
                    padecimientos: padecimientos,

                }, success: function (data) {
                    swal("Actualizado", "", "success");
                    setTimeout("location.href = '/dr_basico/medicamentos'", 0);
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