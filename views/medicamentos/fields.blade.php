@include('medicamentos.script')
<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('componente', 'Componente Químico:') !!}
    {!! Form::text('componente', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('marca', 'Marca:') !!}
    {!! Form::text('marca', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('receta', '¿Necesita Receta o Prescripción?') !!}<br>
    <input id="receta" type="checkbox" data-off-text="SI" data-on-text="NO" checked="false" class="form-control switch">
</div>

<div class="grupo col-xs-12 pad0">
    <div class="col-xs-12 titulo page-header-sub">Padecimientos</div>
    <div class="invisible">
        {!! Form::label('select-padecimiento', 'Seleccionar padecimiento:') !!}
        <select id="select-padecimiento" data-lista="listaM" class="form-control">
            <option value="0">Selecciona padecimiento</option>
            @foreach($padecimientosM as $padecimiento)
                <option nombre="{!! $padecimiento->nombre !!}" value="{!! $padecimiento->id !!}">{!! $padecimiento->nombre !!}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-10 col-md-4">
        {!! Form::label('select-padecimiento', 'Seleccionar padecimiento:') !!} <i class=" info fa fa-info-circle" title="Dar click en el rectangulo de abajo para que aparesca el listado de padecimientos"></i>
        <input class="filterinput form-control" type="text" data-lista="listaM" id="campo_producto"  placeholder="Seleccione padecimiento">
        <ul id="listaM" class="lista invisible">
            @foreach($padecimientosM as $padecimiento)
                <li nombre="{!! $padecimiento->nombre !!}" value="{!! $padecimiento->id !!}">{!! $padecimiento->nombre !!}</li>
            @endforeach
        </ul>
    </div>
    <div class="col-sm-2">
        {!! Form::button('<i class = "fa fa-plus-circle" style="margin-top: 18px;"></i>', ['class' => 'btn btn-icon-sucess', 'onclick' => "agregarPadecimiento(this)" , 'title' => 'Agregar padecimiento Seleccionado']) !!}
    </div>
    <div class="col-md-6 padecimientos-header table-responsive" style="margin-top: 10px">
        <table class="table table-bordered" id="seccion-padecimientos">
            <thead>
                <tr>
                    <th>Padecimiento</th>
                    <th>Quitar</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
    var medicamento = null;
    @if(isset($medicamento))
        medicamento = {!! $medicamento !!};
    @endif
    $(document).on('ready',function() {
        //Padecimientos
        $(".filterinput").on("click",function(){
            console.log('entro');
            var listaM = $(this).data("lista");
            $("#"+listaM+" li").on("click",function(){
                $("#"+listaM).addClass('invisible').removeClass('show');
                var value = $(this).attr("value");
                $("select[data-lista='"+listaM+"']").val(value).trigger("onchange");
                $(".filterinput[data-lista='"+listaM+"']").val($(this).text());
            })
            $("#"+listaM).addClass('show').removeClass('invisible');
        });
        $(".filterinput").change( function () {
            var filter = $(this).val();
            var listaM = $(this).data("lista");
            if (filter) {
                $("#"+listaM).find("li:not(:contains(" + filter + "))").addClass("invisible").removeClass("show");
                $("#"+listaM).find("li:contains(" + filter + ")").addClass("show").removeClass("invisible");
            } else {
                $("#"+listaM).find("li").addClass("show").removeClass("invisible");
            }
        }).keyup( function () {
            $(this).change();
            //Ocultar añadido
            $(".lista").addClass('invisible').removeClass('show');
        });

        if(medicamento != null){
            console.log('editar');
            $(".guardarMedicamento").attr("accion","e");
            $(".guardarMedicamento").attr("id",medicamento.id);
            if (medicamento.receta == '1'){$("#receta").trigger("click");}

            var res = "";
            @if(isset($padecimientosME))
                @foreach($padecimientosME as $padecimientoE)
                    <?php
                        $super   = json_encode( $padecimientoE );
                        $padecimientoE = json_decode( $super, true );
                    ?>
                    res += "<tr id="+'{!! $padecimientoE['id'] !!}'+">";
                    res += "<td>"+'{!! $padecimientoE['nombre'] !!}'+"</td>";
                    res += "<td><div class='minus col-xs-1' onclick='quitarElemento(this)'><i class='fa fa-times' title='Quitar solo este producto'></i></div></td>";
                    res += "</tr>";
                @endforeach
                $("#seccion-padecimientos").append(res);
            @endif
        }

    });
</script>