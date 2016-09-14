{{--Modal Estudio --}}
<div class="modal fade" id="modalEstudio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Estudio(s) a realizar</h4>
            </div>
            <div class="modal-body container-fluid">
                <div class="invisible">
                    {!! Form::label('select-estudio', 'Seleccionar Estudio:') !!}
                    <select id="select-estudio" data-lista="lista" class="form-control">
                        <option value="0">Selecciona estudio</option>
                        @foreach($estudios as $estudio)
                            <option nombre="{!! $estudio->nombre !!}" value="{!! $estudio->id !!}">{!! $estudio->nombre !!}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-5">
                    {!! Form::label('select-estudio', 'Seleccionar Estudio:') !!} <i class=" info fa fa-info-circle" title="Dar click en el rectangulo de abajo para que aparesca el listado de estudios"></i>
                    <input class="filterinput form-control" type="text" data-lista="lista" id="estudio_val"  placeholder="Seleccione estudio">
                    <ul id="lista" class="lista invisible">
                        @foreach($estudios as $estudio)
                            <li nombre="{!! $estudio->nombre !!}" value="{!! $estudio->id !!}">{!! $estudio->nombre !!}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="form-group col-sm-5">
                    {!! Form::label('proveedor', 'Proveedor:') !!}
                    <select name="proveedor" id="proveedor" class="form-control"></select>
                </div>

                <div class="col-sm-2">
                    {!! Form::button('<i class = "fa fa-plus-circle" style="margin-top: 18px;"></i>', ['class' => 'btn btn-icon-sucess', 'onclick' => "agregarEstudioC(this)" , 'title' => 'Agregar estudio Seleccionado']) !!}
                </div>

                <div class="col-xs-12 estudios-header table-responsive" style="margin-top: 10px">
                    <table class="table table-bordered" id="seccion-estudios">
                        <thead>
                        <tr>
                            <th>Estudio</th>
                            <th>Proveedor</th>
                            <th>Quitar</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer container-fluid">
                <button type="button" class="btn btn-default" data-dismiss="modal">Finalizar seleccion de estudios</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('ready',function() {
        //Estudios
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
            $("#lista").addClass('invisible').removeClass('show');
        });

        var estudio = $('#select-estudio').val();
        cargarProveedores(estudio);

        @if($estudiosEC != null)
            console.log('editar');
            var res = "";
            @foreach($estudiosEC as $estudioEC)

                res += "<tr id="+'{!! $estudioEC->id !!}'+" control_id="+'{!! $estudioEC->control_id !!}'+">";
                res += "<td>"+'{!! $estudioEC->estudio_nombre !!}'+"</td>";
                res += "<td>"+'{!! $estudioEC->proveedor_nombre !!}'+"</td>";
                res += "<td><div class='minus col-xs-1' onclick='quitarElemento(this)'><i class='fa fa-times' title='Quitar solo este producto'></i></div></td>";
                res += "</tr>";
            @endforeach
            $("#seccion-estudios").append(res);
        @endif
    });

    $("#select-estudio").on("change", function(){
        var estudio = $(this).val();
        cargarProveedores(estudio);
    });

    $(".filterinput").on('focusout',function(){
        waitingDialog.show('Cargando Proveedores...', {dialogSize: 'md', progressType: 'warning'});
        $("#select-estudio").delay(1000).queue(function(){
            $('#lista').addClass('invisible').removeClass('show');
            var estudio = $('#select-estudio').val();
            cargarProveedores(estudio);
            $(this).dequeue(); //para que no se detengan
            waitingDialog.hide();
        });
    });

    function cargarProveedores(estudio){
        $.ajax({
            type   : "GET",
            url    : "/dr_basico/proveedores/estudio/"+estudio,
            async: false,
            success: function (data) {
                /*console.log(data);*/
                 if(data){
                    var res = "";
                    for(var i=0; i<data.length; i++){
                        res += "<option value='"+data[i]['id']+"' control_id='"+data[i]['control_id']+"'>"+data[i]['nombre']+"</option>";
                    }
                    $("#proveedor").html(res);
                }
            },error: function (data) {
                var errors = data.responseJSON;
                var mensaje = "";
                $.each(errors, function(index, value) {
                    mensaje += value+'\n';
                });
                swal("Espere",mensaje,"warning");
            }
        });
    }

    function agregarEstudioC(){
        var select = $("#select-estudio option:selected").text();
        var lista = $("input[data-lista='lista']").val();

        // $.trim remueve espacios en blanco al comienzo y al final
        if($.trim(select) == $.trim(lista)) {

            var ids = [];
            $('#seccion-estudios tr').each(function(){
                ids.push($(this).attr('id'));
            });

            var id = $("#select-estudio").val();
            var control_id = $("#proveedor option:selected").attr('control_id');

            if ($.inArray(id,ids) == -1){

                var estudio = $("#select-estudio option:selected").text();
                var nombre = $("#select-estudio option:selected").attr('nombre');
                var proveedor = $("#proveedor option:selected").text();

                var ay = "";
                ay += "<tr id="+id+" control_id="+control_id+">";
                ay += "<td>"+nombre+"</td>";
                ay += "<td>"+proveedor+"</td>";
                ay += "<td><div class='minus col-xs-1' onclick='quitarElemento(this)'><i class='fa fa-times' title='Quitar solo este producto'></i></div></td>";
                ay += "</tr>";
                $("#seccion-estudios").append(ay);

                $("input[data-lista='lista']").val("");
                $('#proveedor').empty().append('whatever');
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
</script>