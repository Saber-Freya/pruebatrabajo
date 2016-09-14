{{--Modal Medicamentos --}}
<div class="modal fade" id="modalMedicamentos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Receta <i class="fa fa-info-circle" title="Para la receta membretada es necesario que imprima una receta de prueba y tomar las medidas necesarias para el membrete."></i> </h4>
            </div>
            <div class="modal-body container-fluid">
                @include('medicamentos.fields')
                <div class="form-group col-xs-12">
                    {!! Form::button('Guardar <i class = "glyphicon glyphicon-floppy-save"></i>', ['class' => 'btn btn-success guardarMedicamento','accion' => 'r',
                        'onclick'=>'guardarMedicamento()']) !!}
                </div>
            </div>
            <div class="modal-footer container-fluid">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('ready',function() {
        //Medicamentos
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
    });

    function receta(este){

        var hoy = new Date();
        var fecha = $.datepicker.formatDate('yy-mm-dd',new Date(hoy));

        var paciente = $(este).attr('paciente');
        var id_cliente = $(este).attr('id_cliente');
        $('.paciente').html(paciente);
        $('.fecha_receta').html(fecha);
        $('#receta').val('').removeAttr('readonly');
        $('.reimprimirReceta').addClass('hidden');
        $('.guardarReceta').attr('fecha',fecha).attr('id_cliente',id_cliente).attr('paciente',paciente).removeClass('hidden');
        $('.areaReceta').addClass('hidden');
        $('.areaMedicamentos').removeClass('hidden');
    }

    function recetaReimpresion(este){

        var fecha = $(este).attr('dato-fecha');
        var paciente = $(este).attr('paciente');
        var receta = $(este).attr('dato-receta');
        var nombre = $(este).attr('dato-nombre');
        console.log(receta);
        receta = receta.replace( /(?:\\[rn])+/g , "\n");
        receta = receta.replace('n', "R");
        console.log(receta);

        $('.paciente').html(paciente);
        $('.fecha_receta').html(fecha);
        $('#receta').val(receta).attr('readonly','');
        $('.guardarReceta').addClass('hidden');
        $('.reimprimirReceta').attr('fecha',fecha).attr('paciente',paciente).attr('nombre',nombre).removeClass('hidden');
        $('.areaReceta').removeClass('hidden');
        $('.areaMedicamentos').addClass('hidden');
    }

    function guardarReceta(este){
        var fecha = $(este).attr('fecha');
        var id_cliente = $(este).attr('id_cliente');
        var componente = $(este).attr('componente');
        var paciente = $(este).attr('paciente');
        var accion = $(este).attr('data-accion');
        /*var receta = $('#receta').val();*/

        var medicamentos = [];
        var medicamento = {};
        $("#seccion-medicamentos tr").each(function(){
            var id = $(this).attr("id");
            var componente = $(this).children('td.componente').text();
            var periodo = $(this).children('td.periodo').text();
            if(id!="" && id!=undefined){
                medicamento = {
                    id:id,
                    componente:componente,
                    periodo:periodo,
                };
                medicamentos.push(medicamento);
            }
        });

        if (medicamentos == ''){medicamentos = 'vacio';}
        /*console.log(medicamentos);*/

        if(medicamentos == 'vacio') {return swal("Espere","Es necesario apregar Medicamento","warning");}

        $.ajax({
            type: 'POST',
            url: '/dr_basico/servicios/receta',
            data:{
                _token: $('meta[name=csrf-token]').attr('content'),
                fecha: fecha,
                id_cliente: id_cliente,
                paciente:   paciente,
                medicamentos:   medicamentos,
                /*receta:     receta,*/
                accion:accion,
            },success: function(data){
                /*receta = receta.replace(/\r?\n/g, "<br>");*/
                console.log(data);
                if (accion == 'm') {
                    var result = '';
                    result += '<div class = "row" style="margin-top: 2.5cm;"></div>';
                    result += '<div style="margin-left: 1cm;margin-top: 4px;">' + paciente + '</div>';
                    result += '<div style="margin-left: 13cm;margin-top: -16px;">' + fecha + '</div>';
                    for (var i = 0; i < medicamentos.length; i++) {
                        result += '<div class = "row" style="margin-top: 0.5cm;">' + medicamentos[i].componente + ' - ' + medicamentos[i].periodo + '</div>';
                    }
                    newWin = window.open("");
                    newWin.document.write(result);

                    if (!!navigator.userAgent.match(/Trident/gi)) {
                        newWin.document.execCommand('print', false, null);
                    } else {
                        newWin.print();
                    }
                    newWin.close();
                }else{
                    verPDF('/dr_basico/uploads/recetas/' + data)
                }
                $('#modalReceta').modal('toggle');
                swal("Mandado a imprimir","","success");
                $('#receta').val('');
            },error: function (ajaxContext) {
                swal("Espere","Algo salio mal, reintente de nuevo","warning");
            }
        });
    }

    function reimprimirReceta(este){
        var fecha = $(este).attr('fecha');
        var paciente = $(este).attr('paciente');
        var receta = $('#receta').val();
        var nombre = $(este).attr('nombre');
        var accion = $(este).attr('data-accion');
        receta = receta.replace(/\r?\n/g, "<br>");

        if (accion == 'm'){
            var result = '';
            result += '<div class = "row" style="margin-top: 2.5cm;"></div>';
            result += '<div style="margin-left: 1cm;margin-top: 4px;">'+paciente+'</div>';
            result += '<div style="margin-left: 13cm;margin-top: -16px;">'+fecha+'</div>';
            result += '<div class = "row" style="margin-top: 0.5cm;">'+receta+'</div>';
            newWin= window.open("");
            newWin.document.write(result);

            if (!! navigator.userAgent.match(/Trident/gi)) {
                newWin.document.execCommand('print', false, null);
            } else { newWin.print(); }
            newWin.close();
        }else{
            verPDF('/dr_basico/uploads/recetas/' + nombre);
        }

        $('#modalReceta').modal('toggle');
        swal("Mandado a imprimir","","success");
        $('#receta').val('');
    }

    function agregarMedicamento(){
        var select = $("#select-medicamento option:selected").text();
        var lista2 = $("input[data-lista2='lista2']").val();

        // $.trim remueve espacios en blanco al comienzo y al final
        if($.trim(select) == $.trim(lista2)) {

            var ids = [];
            $('#seccion-medicamentos tr').each(function(){
                ids.push($(this).attr('id'));
            });

            var id = $("#select-medicamento").val();

            if ($.inArray(id,ids) == -1){

                var medicamento = $("#select-medicamento option:selected").text();
                var componente = $("#select-medicamento option:selected").attr('componente');
                var periodo = $("#periodo").val();

                if(periodo == "")return swal("Espere","Es necesario el periodo", "warning");

                var ay = "";
                ay += "<tr id=" + id + ">";
                ay += "<td class='componente'>"+componente+"</td>";
                ay += "<td class='periodo'>"+periodo+"</td>";
                ay += "<td><div class='minus col-xs-1' onclick='quitarElemento(this)'><i class='fa fa-times' title='Quitar solo este producto'></i></div></td>";
                ay += "</tr>";
                $("#seccion-medicamentos").append(ay);

                $("input[data-lista2='lista2']").val("");
                $("#periodo").val("");
            }else{
                return swal("Espere","Este Medicamento ya esta agregado", "warning");
            }
        }else {
            return swal("Espere", "Seleccione un Medicamento valido", "warning");
        }
    }

    function quitarElemento(x){
        $(x).parents('tr').remove();
    }

</script>