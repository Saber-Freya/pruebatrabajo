<script>
    $(document).on('ready',function() {
        var pen = pendientes();
        var rea = reagendar();
        /*console.log(rea);
        console.log(pen);*/

        if (pen == true || rea == true){
            campanitaOn();
        }else{
            campanitaOff();
        }
    });

    function campanitaOn(){
        //campana de color
        $('.campanitaOn').removeClass('hidden');
        $('.campanita').addClass('hidden');
        $('.campanita').addClass('alert-on');
    }

    function campanitaOff(){
        //campana sin color
        $('.campanitaOn').addClass('hidden');
        $('.campanita').removeClass('hidden');
        $('.campanita').removeClass('alert-on');
    }

    function pendientes(){
        var valor = false;
        $.ajax({
            async  : false,
            type   : "GET",
            url    : "/servicios/pendientes/hoy",
            success: function (data) {
                if(data == ""){
                    $('.boton_pendiente').removeClass('alert-on');
                    valor = false;
                }else{
                    $('.boton_pendiente').addClass('alert-on');
                    valor = true;
                }
            }
        });
        reagendar();
        return valor;
    }

    function reagendar(){
        var valor = false;
        $.ajax({
            async  : false,
            type   : "GET",
            url    : "/servicios/reagendar/cita",
            success: function (data) {
                if(data == ""){
                    $('.boton_reagendar').removeClass('alert-on');
                    valor = false;
                }else{
                    $('.boton_reagendar').addClass('alert-on');
                    valor = true;
                }
            }
        });
        return valor;
    }

    function reagendarLista(){
        $.ajax({
            async  : false,
            type   : "GET",
            url    : "/servicios/reagendar/cita",
            success: function (data) {
                /*console.log(data);*/
                if(data == ""){
                    swal("No se encuentran citas pendientes de reagendar o dar seguimiento", "", "info");
                }else{
                    $('.boton_reagendar').addClass('alert-on');
                    valor = true;

                    var result = "";
                    result += "<table class='table'>";
                    result += "<thead>";
                    result += "<th>Fecha</th>";
                    result += "<th>Hora</th>";
                    result += "<th>Paciente</th>";
                    result += "<th>Para</th>";
                    result += "<th>Acción</th>";
                    result += "</thead>";
                    result += "<tbody>";

                    result += "</tr>";
                    for (var i = 0; i < data.length; i++) {
                        if(data[i].reagendar == 1) { var para = 'Reagendar';}else{var para ='Seguimiento';}

                        result += '<tr>';
                            result += '<td>' + data[i].fecha + '</td>';
                            result += '<td>' + data[i].hora + '</td>';
                            result += '<td>' + data[i].paciente + '</td>';
                            result += '<td>' + para + '</td>';
                            result += '<td>';
                            if(data[i].reagendar == 1) {
                                result += '<a title="Reagendar" href="/servicios/' + data[i].id + '/edit" class="removerDec"> <i class="fa fa-refresh"> </i></a>';
                            }else{
                                result += '<a title="Reagendar" href="/servicios/' + data[i].id + '/seguimiento/cita" class="removerDec"> <i class="fa fa-share-square-o"> </i></a>';
                            }
                            result += '</td>';
                        result += '</tr>';
                    }
                    result += "</tbody>";
                    result += "</table>";
                    $('.tablaContenido').html(result);
                }
            }
        });
        return valor;
    }
</script>