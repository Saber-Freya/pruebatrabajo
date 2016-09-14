@include('proveedores.script')
<div class = "row">
    <div class = "col-xs-6 col-md-3">
        <h2 class = "page-header">Proveedor</h2>
    </div>
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('nombre', 'Nombre:') !!}
    {!! Form::text('nombre', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('calle', 'Calle:') !!}
    {!! Form::text('calle', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('no_ext', 'No. Exterior:') !!}
    {!! Form::text('no_ext', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('no_int', 'No. Interior:') !!}
    {!! Form::text('no_int', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('colonia', 'Colonia:') !!}
    {!! Form::text('colonia', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('tel', 'Teléfono:') !!}
    {!! Form::text('tel', null, ['class' => 'form-control','id' => 'tel', 'maxlength' => '10',
    'onkeypress'=>'return soloNumeros(event)']) !!}
</div>

<div class="grupo col-xs-12 pad0">
    <div class="col-xs-12 titulo page-header-sub">Estudios</div>
    <div class="invisible">
        {!! Form::label('select-estudio', 'Seleccionar Estudio:') !!}
        <select id="select-estudio" data-lista="lista" class="form-control">
            <option value="0">Selecciona estudio</option>
            @foreach($estudios as $estudio)
                <option nombre="{!! $estudio->nombre !!}" value="{!! $estudio->id !!}">{!! $estudio->nombre !!}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-10 col-md-4">
        {!! Form::label('select-estudio', 'Seleccionar Estudio:') !!} <i class=" info fa fa-info-circle" title="Dar click en el rectangulo de abajo para que aparesca el listado de estudios"></i>
        <input class="filterinput form-control" type="text" data-lista="lista" id="campo_producto"  placeholder="Seleccione estudio">
        <ul id="lista" class="lista invisible">
            @foreach($estudios as $estudio)
                <li nombre="{!! $estudio->nombre !!}" value="{!! $estudio->id !!}">{!! $estudio->nombre !!}</li>
            @endforeach
        </ul>
    </div>
    <div class="col-sm-2">
        {!! Form::button('<i class = "fa fa-plus-circle" style="margin-top: 18px;"></i>', ['class' => 'btn btn-icon-sucess', 'onclick' => "agregarEstudio(this)" , 'title' => 'Agregar estudio Seleccionado']) !!}
    </div>
    <div class="col-md-6 estudios-header table-responsive" style="margin-top: 10px">
        <table class="table table-bordered" id="seccion-estudios">
            <thead>
            <tr>
                <th>Estudio</th>
                <th>Quitar</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!--- Submit Field --->
<div class="form-group col-sm-12">
     {!! Form::button('Guardar <i class = "glyphicon glyphicon-floppy-save"></i>', ['class' => 'btn btn-success guardarProveedor',
        'onclick'=>'guardarProveedor()']) !!}
        <a class = "btn btn-danger" href = "{!! route('proveedores.index') !!}">
            Cancelar <i class = "glyphicon glyphicon-floppy-remove"></i>
        </a>
</div>

<script>
    var proveedor = null;
    @if(isset($proveedor))
        proveedor = {!! $proveedor !!};
    @endif
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
            $(".lista").addClass('invisible').removeClass('show');
        });

        if(proveedor != null){
            console.log('editar');
            $(".guardarProveedor").attr("accion","e");
            $(".guardarProveedor").attr("id",proveedor.id);
            if (proveedor.receta == '1'){$("#receta").trigger("click");}

            var res = "";
            @if(isset($estudiosE))
                @foreach($estudiosE as $estudioE)
                    <?php
                        $super   = json_encode( $estudioE );
                        $estudioE = json_decode( $super, true );
                    ?>
                    res += "<tr id="+'{!! $estudioE['id'] !!}'+">";
                    res += "<td>"+'{!! $estudioE['nombre'] !!}'+"</td>";
                    res += "<td><div class='minus col-xs-1' onclick='quitarElemento(this)'><i class='fa fa-times' title='Quitar solo este producto'></i></div></td>";
                    res += "</tr>";
                @endforeach
                $("#seccion-estudios").append(res);
            @endif
        }
    });

    function soloNumeros(e){
        var keynum = window.event ? window.event.keyCode : e.which;
        console.log(keynum);
        if ((keynum == 8) || (keynum == 46))
            return true;
        return /\d/.test(String.fromCharCode(keynum));
    }
</script>