{{--Modal CrearReceta --}}
<div class="modal fade" id="modalReceta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Receta <i class="fa fa-info-circle" title="Los medicamentos que requieran receta médica no podrán ser impresos en la función Venta Directa. Para la Receta Medica es necesario que imprima una receta de prueba y tomar las medidas necesarias para el membrete."></i> </h4>
            </div>
            <div class="modal-body container-fluid">
                <div class="col-xs-12 col-sm-6">
                    {!! Form::label('paciente', 'Paciente:') !!}
                    <div class="paciente"></div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    {!! Form::label('fecha_receta', 'Fecha:') !!}
                    <div class="fecha_receta"></div>
                </div>
                <div class="col-xs-12 areaReceta hidden">
                    {!! Form::label('receta', 'Receta:') !!}
                    {!! Form::textarea('receta', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-sm-6 col-lg-4 areaTipoReceta">
                    {!! Form::label('tipoReceta', 'Tipo de Receta:') !!}<BR>
                    <input id="tipoReceta" type="checkbox" data-off-text="Receta Medica" data-off-color="primary"  data-on-text="Venta Directa" data-on-color="primary" checked="false" class="form-control">
                </div>
                <div class="grupo col-xs-12 pad0 areaMedicamentos" style="margin-top: 15px">
                    <div class="col-xs-12 titulo page-header-sub">Receta</div>

                    <div class="col-sm-1" >
                        <a class="btn btn-icon-sucess" style="margin-top: 9px;" data-toggle="modal" data-target="#modalMedicamentos" title="Agregar Nuevo Medicamento"><i class="fa fa-plus-square"></i></a>
                    </div>

                    <div class="invisible">
                        {!! Form::label('select-medicamento', 'Seleccionar Medicamento:') !!}
                        <select id="select-medicamento" data-lista2="lista2" class="form-control">
                            <option value="0">Selecciona medicamento</option>
                            @foreach($medicamentos as $medicamento)
                                <option componente="{!! $medicamento->componente !!}" value="{!! $medicamento->id !!}" tipo="{!! $medicamento->receta !!}">{!! $medicamento->componente !!} | {!! $medicamento->marca !!}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-sm-10 col-md-4">
                        {!! Form::label('select-medicamento', 'Seleccionar Medicamento:') !!} <i class=" info fa fa-info-circle" title="Dar click en el rectangulo de abajo para que aparesca el listado de medicamentos"></i>
                        <input class="filterinput2 form-control" type="text" data-lista2="lista2" id="campo_producto"  placeholder="Seleccione medicamento">
                        <ul id="lista2" class="lista invisible">
                            @foreach($medicamentos as $medicamento)
                                <li componente="{!! $medicamento->componente !!}" value="{!! $medicamento->id !!}" tipo="{!! $medicamento->receta !!}">{!! $medicamento->componente !!} | {!! $medicamento->marca !!}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="form-group col-sm-6 col-lg-4">
                        {!! Form::label('periodo', 'Periodo:') !!}
                        {!! Form::text('periodo', null, ['class' => 'form-control', 'maxlength' => '60']) !!}
                    </div>

                    <div class="col-sm-1">
                        {!! Form::button('<i class = "fa fa-plus-circle" style="margin-top: 18px;"></i>', ['class' => 'btn btn-icon-sucess', 'onclick' => "agregarMedicamento(this)" , 'title' => 'Agregar Medicamento Seleccionado']) !!}
                    </div>
                    <div class="col-xs-12 medicamentos-header table-responsive" style="margin-top: 10px">
                        <table class="table table-bordered" id="seccion-medicamentos">
                            <thead>
                            <tr>
                                <th>Medicamento</th>
                                <th>Periodo</th>
                                <th>Quitar</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer container-fluid">
                <a type="button" class="btn btn-success pull-right guardarReceta recetaMedica hidden" data-accion="m" onclick="guardarReceta(this)" style="margin-left: 5px;">Imprimir Receta Medica <i class="fa fa-print"></i></a>
                <a type="button" class="btn btn-success pull-right guardarReceta ventaDirecta" data-accion="s" onclick="guardarReceta(this)">Imprimir Venta Directa <i class="fa fa-file-o"></i></a>

                <a type="button" class="btn btn-warning pull-right reimprimirReceta tipo0" data-accion="m" onclick="reimprimirReceta(this)">Reimprimir Receta Medica <i class="fa fa-print"></i></a>
                <a type="button" class="btn btn-warning pull-right reimprimirReceta tipo1" data-accion="s" onclick="reimprimirReceta(this)">Reimprimir Venta Directa <i class="fa fa-file-o"></i></a>
            </div>
        </div>
    </div>
</div>

{{--Modal HistorialReceta --}}
<div class="modal fade" id="historialRecetas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-pencil-square-o fa-fw"></i> Historial de Recetas</h4>
            </div>
            <div class="modal-body container-fluid">
                <div class="row">
                    <div class = "table-responsive col-xs-12">
                        <table class="table">
                            <thead>
                                <th>Fecha</th>
                                <th>Receta</th>
                                <th>Reimprimir</th>
                            </thead>
                            <tbody>
                            @foreach($recetas as $receta)
                                <tr>
                                    <td>{!! $receta->fecha !!}</td>
                                    <td>{!! $receta->receta !!}</td>
                                    <td><a class="removerDec" href="#" onclick="recetaReimpresion(this)"
                                           dato-fecha="{!! $receta->fecha !!}" dato-receta="{!! $receta->receta !!}"
                                           paciente="{!! $cliente->nombre !!} {!! $cliente->apellido !!}"
                                           dato-nombre="{!! $receta->nombre_receta !!}"
                                           data-toggle="modal" data-target="#modalReceta" title="ALZAM .5 MG TAB 30 - algo sdas\r\n">
                                            <i class="fa fa-print"> </i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
{!! Form::open(['route' => 'medicamentos.store']) !!}
@include ('modals.medicamentos')
{!! Form::close() !!}

<script>
    $("#tipoReceta").bootstrapSwitch();
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

        $('.guardarMedicamento').attr('accion','r');

        {{--@if(isset($recetas))
            $('.areaTipoReceta').addClass('hidden');
        @endif--}}
    });

    $('#tipoReceta').on( "switchChange.bootstrapSwitch", function(e, data){
        var tipoReceta = ($("#tipoReceta").prop("checked"))? 1:0;
        if (tipoReceta == 1) {
            console.log('venta directa');
            $("li[tipo='0']").removeClass("hidden");
            $(".recetaMedica").addClass("hidden");
            $(".ventaDirecta").removeClass("hidden");
        } else {
            console.log('receta medica');
            $("li[tipo='0']").addClass('hidden');
            $("li[tipo='1']").removeClass('hidden');
            $(".ventaDirecta").addClass("hidden");
            $(".recetaMedica").removeClass("hidden");
        }
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
        $('.guardarReceta').attr('fecha',fecha).attr('id_cliente',id_cliente).attr('paciente',paciente);
        $('.areaReceta').addClass('hidden');
        $('.areaMedicamentos').removeClass('hidden');

        $("#tipoReceta").bootstrapSwitch('disabled',false);
        $("#seccion-medicamentos").children().children('tr').remove();
    }

    function recetaReimpresion(este){

        var fecha = $(este).attr('dato-fecha');
        var paciente = $(este).attr('paciente');
        var receta = $(este).attr('dato-receta');
        var nombre = $(este).attr('dato-nombre');
        var tipoReceta = $(este).attr('tipoReceta');
        receta = receta.replace( /(?:\\[rn])+/g , "\n");
        /*receta = receta.replace('n', "R");*/
        $('.areaTipoReceta').addClass('hidden');

        $('.paciente').html(paciente);
        $('.fecha_receta').html(fecha);
        $('#receta').val(receta).attr('readonly','');
        $('.guardarReceta').addClass('hidden');
        $('.reimprimirReceta').attr('fecha',fecha).attr('paciente',paciente).attr('nombre',nombre).removeClass('hidden');
        $('.areaReceta').removeClass('hidden');
        $('.areaMedicamentos').addClass('hidden');

        $('#historialRecetas').modal('hide');

        if (tipoReceta == 0){
            $('.tipo0').removeClass('hidden');
            $('.tipo1').addClass('hidden');
        }else{
            $('.tipo1').removeClass('hidden');
            $('.tipo0').addClass('hidden');
        }
    }

    function guardarReceta(este){
        var tipoReceta = ($("#tipoReceta").prop("checked"))? 1:0;
        var fecha = $(este).attr('fecha');
        var id_cliente = $(este).attr('id_cliente');
        var componente = $(este).attr('componente');
        var paciente = $(este).attr('paciente');
        var accion = $(este).attr('data-accion');

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
                tipoReceta: tipoReceta,
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

                $("#tipoReceta").bootstrapSwitch('disabled',true);
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