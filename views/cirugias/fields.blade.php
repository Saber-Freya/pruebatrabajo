<div class="col-xs-12 panel panel-default" style="margin-top: 30px;padding-top: 15px;">

    <div class="form-group col-sm-6 col-lg-2">
        {!! Form::label('convenio', 'Convenio:') !!}
        {!! Form::text('convenio', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-6 col-lg-2">
        {!! Form::label('renta2', 'Renta con IVA:') !!}
        {!! Form::text('renta2', null, ['class' => 'form-control', 'placeholder'=>'$', 'onkeyup' => 'servicio(this.form)']) !!}
    </div>
    {!! Form::hidden('renta', null, ['id' => 'renta']) !!}
    <script>
        function servicio(form) {
            $('#renta2').priceFormat({
                prefix: '$ ',
                centsSeparator: '.',
                thousandsSeparator: ',',
                allowNegative: 'true'
            })
            var renta = $('#renta2').unmask();
            renta = renta / 100;
            $('#renta').val(renta);
            return;
        }
    </script>

    <div class="form-group col-sm-6 col-lg-2">
        {!! Form::label('recibo', 'Recibo:') !!}
        {!! Form::text('recibo', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-6 col-lg-2">
        {!! Form::label('plaza', 'Plaza:') !!}<i class=" info fa fa-info-circle" title="Indicar el lugar de la Cirugía."></i>
        {!! Form::text('plaza', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-6 col-lg-2" align="center">
        {!! Form::label('status', 'Estatus de pago:') !!}<BR>
        <input id="status" type="checkbox" data-off-text="No Pagado" data-on-text="Pagado" checked="false" class="form-control switch">
    </div>

    <div class="form-group col-sm-6 col-lg-2 fecha_cryo">
        {!! Form::label('fecha_pago', 'Fecha de Pago:') !!}<i class=" info fa fa-info-circle" title="Capture la fecha en la que se realice el pago."></i>
        <div class = "input-group" id="contenido">
            {!! Form::text('fecha_pago', null, ['class' => 'form-control input-fecha']) !!}
            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
        </div>
    </div>

    <div class="form-group col-xs-12">
        {!! Form::label('comentarios', 'Comentarios:') !!}
        {!! Form::textarea('comentarios', null, ['class' => 'form-control','rows' => '4']) !!}
    </div>
</div>

<div class="col-xs-12" style="margin-top: 50px;">
    <div class="col-sm-5" style="border-right-style: inset;">
        <div class="invisible">
            {!! Form::label('select-auxiliar', 'Seleccionar Auxiliar:') !!}
            <select id="select-auxiliar" data-lista="lista" class="form-control">
                <option value="0">Selecciona Auxiliar</option>
                @foreach($auxiliares as $auxiliar)
                    <option nombre="{!! $auxiliar->nombre !!}" apellido="{!! $auxiliar->apellido !!}" value="{!! $auxiliar->id !!}">{!! $auxiliar->id !!} | {!! $auxiliar->nombre !!} {!! $auxiliar->apellido !!}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-10">
            {!! Form::label('select-auxiliar', 'Seleccionar Auxiliar:') !!} <i class=" info fa fa-info-circle" title="Dar click en el rectangulo de abajo para que aparesca el listado de Auxiliares"></i>
            <input class="filterinput form-control" type="text" data-lista="lista" id="campo_producto"  placeholder="Seleccione Auxiliar">
            <ul id="lista" class="lista invisible">
                @foreach($auxiliares as $auxiliar)
                    <li nombre="{!! $auxiliar->nombre !!}" apellido="{!! $auxiliar->apellido !!}" value="{!! $auxiliar->id !!}">{!! $auxiliar->id !!} | {!! $auxiliar->nombre !!} {!! $auxiliar->apellido !!}</li>
                @endforeach
            </ul>
        </div>
        <div class="col-sm-2">
            {!! Form::button('<i class = "fa fa-plus-circle" style="margin-top: 18px;"></i>', ['class' => 'btn btn-icon-sucess', 'onclick' => "agregarAuxiliar(this)" , 'title' => 'Agregar Auxiliar Seleccionado']) !!}
        </div>
        <div class="col-sm-12 auxiliares-header table-responsive" style="margin-top: 10px">
            <table class="table table-bordered" id="seccion-auxiliares">
                <thead>
                <tr>
                    <th>Auxiliar</th>
                    <th>Quitar</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div class="col-sm-7">
        <div class="invisible">
            {!! Form::label('select-material', 'Seleccionar Auxiliar:') !!}
            <select id="select-material" data-lista2="lista2" class="form-control">
                <option value="0">Selecciona Material</option>
                @foreach($materiales as $material)
                    <option material="{!! $material->nom_prod !!}"
                            descripcion="{!! $material->descripcion_prod !!}"
                            precio="{!! $material->precio_unitario !!}"
                            value="{!! $material->id !!}">{!! $material->id !!} | {!! $material->nom_prod !!}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-5">
            {!! Form::label('select-material', 'Seleccionar Material:') !!} <i class=" info fa fa-info-circle" title="Dar click en el rectangulo de abajo para que aparesca el listado de Auxiliares"></i>
            <input class="filterinput2 form-control" type="text" data-lista2="lista2" id="campo_producto"  placeholder="Seleccione Auxiliar">
            <ul id="lista2" class="lista invisible">
                @foreach($materiales as $material)
                    <li material="{!! $material->nom_prod !!}"
                        descripcion="{!! $material->descripcion_prod !!}"
                        precio="{!! $material->precio_unitario !!}"
                        value="{!! $material->id !!}">{!! $material->id !!} | {!! $material->nom_prod !!}</li>
                @endforeach
            </ul>
        </div>

        <div class="col-sm-2">
            {!! Form::label('cantidad', 'Cantidad:') !!}
            {!! Form::text('cantidad', null, ['class' => 'form-control', 'value' => '1']) !!}
        </div>

        <div class="col-sm-2">
            {!! Form::button('<i class = "fa fa-plus-circle" style="margin-top: 18px;"></i>', ['class' => 'btn btn-icon-sucess', 'onclick' => "agregarMaterial(this)" , 'title' => 'Agregar Material Seleccionado']) !!}
        </div>

        <div class="col-sm-3" id="switchs" align="center" style="margin-top: 20px;">
            <input class="switch porcentajeIva" id="porcentajeIva" type="checkbox" data-label-text="IVA 16%"
                   data-on-text="SI" {{--data-on-color="success"--}} data-off-text="NO" {{--data-off-color="info"--}}
                   onchange="calcularTotal()"
            >
        </div>

        <div class="col-sm-12 materiales-header table-responsive" style="margin-top: 10px">
            <table class="table table-bordered" id="seccion-materiales">
                <thead>
                <tr>
                    <th>Cantidad</th>
                    <th>Material</th>
                    <th>Descripcion</th>
                    <th>Precio Unitario</th>
                    <th>Importe</th>
                    <th>Quitar</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div class="col-xs-12 seccion-totales hidden">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div id="seccion-totales">
                        <div class="row">
                            <div class="col-sm-4 text-right">Subtotal:</div>
                            <div class="col-sm-4 subtotal">$ 0.00</div>
                            <input id="subtotal" type = "hidden">
                        </div>
                        <div class="row">
                            <div class="col-sm-4 text-right">IVA:</div>
                            <div class="col-sm-4 iva">$ 0.00</div>
                            <input id="iva" type = "hidden">
                        </div>
                        <div class="row" style="font-weight: bold">
                            <div class="col-sm-4 text-right">Total:</div>
                            <div class="col-sm-4 total">$ 0.00</div>
                            <input id="total" type = "hidden">
                        </div>
                        <div class="row">
                            <div class="col-sm-6 pull-right" id="conletra"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('cirugias.script')