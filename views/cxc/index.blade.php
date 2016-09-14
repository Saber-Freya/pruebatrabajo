@extends('app')
@section('content')
    @include('cxc.script')
    @include('flash::message')
    <div class="container-fluid">
    <div class="row">
        <h1 class="pull-left">{!! $q["seccion"] !!}</h1>
        <h5 class="pull-right">Modulo de Facturacion: @if($fact == 1) ACTIVO @else INACTIVO @endif</h5>
    </div>

    @if(sizeof($pagos) == 0)
        <div class="col-xs-12 grupo">No se encontraron pagos -
        <a href="javascript:history.back(1)">Volver Atrás</a></div>
    @else

    <div class="row" style="min-width: 100%">
        <table class="table table-hover table-striped table-condensed sortCC" id="tb_pagos">
            <thead>
                <tr class="rowTotales">
                    <th>TOTALES</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th class="dinero">{!! $totales["pago"] !!}</th>
                    <th class="dinero">{!! $totales["abonos"] !!}</th>
                    <th class="dinero">{!! $totales["saldo"] !!}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th data-sort="idproducto" style="min-width: 110px">Orden</th>
                    <th data-sort="cliente" style="min-width: 110px">Cliente</th>
                    <th data-sort="aseguradora" style="min-width: 150px">Aseguradora</th>
                    <th data-sort="nombre" style="min-width: 130px">Concepto</th>
                    <th data-sort="numeracion" style="min-width: 145px">Numeración</th>
                    <th data-sort="pago" style="min-width: 115px">Total</th>
                    <th data-sort="abonos" style="min-width: 115px">Abonos</th>
                    <th data-sort="saldo" style="min-width: 115px">Saldo</th>
                    <th data-sort="dia" style="min-width: 85px">Día</th>
                    <th data-sort="mes" style="min-width: 90px">Mes</th>
                    <th data-sort="anio" style="min-width: 90px">A&ntilde;o</th>
                    <th data-sort="fecha" style="min-width: 150px">Vencimiento</th>
                    <th data-sort="status" style="min-width: 105px">Status</th>
                    <th data-sort="documento" style="min-width: 110px">Recibo</th>
                    <th data-sort="numfact" style="min-width: 125px">Num Factura</th>
                    <th width="180px">Acciones</th>
                </tr>
            </thead>
            <tbody class="tablabody">
            @foreach($pagos as $prod)
                <tr class='{!! $prod->id !!}
                @if($prod->status=="CANCELADA")
                    letras-rojas
                @elseif((new DateTime() > new DateTime($prod->fecha)) && $prod->status!="PAGADO")
                    linea-roja
                @endif
                ' data-datos='{!! json_encode($prod) !!}'>
                    <td title="{!! $prod->id !!}">{!! $prod->orden !!}</td>
                    <td>{!! $prod->cliente !!}</td>
                    <td>{!! $prod->aseguradora !!}</td>
                    <td>{!! $prod->nombre !!}</td>
                    <td>{!!  $prod->numeracion !!}</td>
                    <td class="dinero">{!! $prod->pago !!}</td>
                    <td class="dinero">{!! $prod->abonos !!}</td> <!-- $prod->abonos -->
                    <td class="dinero">{!! $prod->saldo !!}</td>
                    <td>{!! $prod->dia !!}</td>
                    <td>{!! $prod->mes !!}</td>
                    <td>{!! $prod->anio !!}</td>
                    <td>{!! $prod->fecha !!}</td>

                    <td>{!! $prod->status !!}</td>

                    <td><?php if($prod->documento != null) echo $prod->documento; ?></td>
                     <td>{!! $prod->numfact !!}</td>
                    <td>
                        @if($prod->status != "CANCELADA")
                            @if($prod->status == "RECIBO")
                                @if(Entrust::can("editar_cuentasporcobrar") || 1)
                                <input type="checkbox" class="chek-list" idpago="{!! $prod->id !!}"  cliente="{!! $prod->id_cliente !!}">
                                <button title="Generar recibo" onclick="generar(this)" type="button" class="btn btn-default btn-xs">
                                    <i class="fa fa-file-text-o"></i>
                                </button>@endif
                            @else
                                @if(Entrust::can("editar_cuentasporcobrar") || 1)
                                <button title="Generar pago" onclick="pago(this)" type="button" class="btn btn-default btn-xs">
                                    <i class="fa fa-credit-card"></i>
                                </button>@endif
                                @if($prod->documento == "factura" && $prod->id_factura != "na")
                                    <a onclick="verPDF('cuentas/verRecibo/f/{!! $prod->id_factura !!}')" title="Ver recibo" class="btn btn-default btn-xs">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </a>
                                    <a href="{!! url('cuentas/verXML/'.$prod->id_factura) !!}" title="Ver XML" target="_blank" class="btn btn-default btn-xs">
                                        <i class="fa fa-file-code-o"></i>
                                    </a>
                                @elseif($prod->id_factura != "na")
                                    <a onclick="verPDF('cuentas/verRecibo/{!! $prod->id_factura !!}')" title="Ver recibo" class="btn btn-default btn-xs">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </a>
                                @endif
                                @if($prod->id_factura != "na")
                                    @if(Entrust::can("enviar_documentos_cuentasporcobrar") || 1)
                                        <button title="Enviar Recibo por Email" factura="{!! $prod->id_factura !!}" type="button" class="btn btn-default btn-xs" onclick="abrirEnviar(this)">
                                            <i class="fa fa-paper-plane-o"></i>
                                        </button>
                                    @endif
                                @endif
                                @if(Entrust::can("editar_cuentasporcobrar") || 1)
                                <button title="Abonar" type="button" class="btn btn-default btn-xs" onclick="abono(this)">
                                    <i class="glyphicon fa fa-credit-card-alt"></i>
                                </button>@endif
                                @if(Entrust::can("eliminar_cuentasporcobrar") || 1)
                                <button title="Cancelar" onclick="cancelar(this)" type="button" class="btn btn-default btn-xs">
                                    <i class="glyphicon fa fa-ban"></i>
                                </button>@endif
                            @endif
                        @else
                            @if($prod->documento == "factura" && $prod->id_factura != "na")
                                <a onclick="verPDF('cuentas/verRecibo/f/{!! $prod->id_factura !!}')" title="Ver recibo" class="btn btn-default btn-xs">
                                    <i class="fa fa-file-pdf-o"></i>
                                </a>
                                <a href="{!! url('cuentas/verXML/'.$prod->id_factura) !!}" title="Ver XML" target="_blank" class="btn btn-default btn-xs">
                                    <i class="fa fa-file-code-o"></i>
                                </a>
                            @elseif($prod->id_factura != "na")
                                <a onclick="verPDF('cuentas/verRecibo/{!! $prod->id_factura !!}')" title="Ver recibo" class="btn btn-default btn-xs">
                                    <i class="fa fa-file-pdf-o"></i>
                                </a>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>
    </div>

    @if ($q["seccion"] == 'Cuentas por Cobrar')
        {!! str_replace('/cuentas_por_cobrar', '/dr_basico/cuentas_por_cobrar', $pagos->appends($q)->render()) !!}
    @else
        {!! str_replace('/ingresos_totales', '/dr_basico/ingresos_totales', $pagos->appends($q)->render()) !!}
    @endif

    </div>
    <div class="modal fade" id="modalEnviar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-xs" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><i class = "fa fa-paper-plane-o"></i>&nbsp;Enviar recibo</h4>
                </div>
                <div class="modal-body container-fluid">
                    <div class="form-group col-sm-12">
                        {!! Form::label('enviar-correo', 'Correo Electrónico:') !!}
                        {!! Form::text('enviar-correo', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-12" id="contact-list">

                    </div>

                </div>
                <div class="modal-footer container-fluid">
                    <a type="button" class="btn btn-success" data-style="expand-right" id="enviarCorreo">Enviar</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalPagar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><i class = "fa fa-money"></i>&nbsp;Ingresar Pago | Orden <span class="orden"></span></h4>
                </div>
                <div class="modal-body container-fluid">
                    <div class="form-group col-sm-12 oculto">
                        {!! Form::text('idpago', null, ['class' => 'form-control idpago', 'disabled'=>'disabled']) !!}
                    </div>
                    <div class="form-group col-sm-12">
                        {!! Form::label('vencimiento', 'Fecha de vencimiento:') !!}
                        {!! Form::text('vencimiento', null, ['class' => 'form-control fecha', 'disabled'=>'disabled']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('envio', 'Fecha de envío:') !!}
                        {!! Form::text('envio', null, ['class' => 'form-control fecha', 'readonly'=>'true']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('contrarecibo', 'Fecha de contrarecibo:') !!}
                        {!! Form::text('contrarecibo', null, ['class' => 'form-control fecha']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('pago', 'Fecha pago:') !!}
                        {!! Form::text('pago', null, ['class' => 'form-control fecha']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('forma', 'Forma de pago:') !!}
                        {!! Form::select('forma', $metodos, null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('refpago', 'Referencia de pago:') !!}
                        {!! Form::text('refpago', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('refdepo', 'Referencia de depósito:') !!}
                        {!! Form::text('refdepo', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="modal-footer container-fluid">
                    <a type="button" class="btn btn-success col-xs-5 ladda-button" data-style="expand-right" id="guardarPago">Guardar</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAbonos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><i class = "fa fa-money"></i>&nbsp;Ingresar Abono | Orden <span class="orden"></span></h4>
                </div>
                <div class="modal-body container-fluid">
                    <div class="col-xs-12 grupo totales" id="totalesModalAbonos">
                        <div class="col-xs-4"><b>Pago: </b><span id="tPago"></span></div>
                        <div class="col-xs-4"><b>Abonado: </b><span id="tAbonado"></span></div>
                        <div class="col-xs-4"><b>Saldo: </b><span id="tSaldo"></span></div>
                    </div>
                    <div id="pageAbonar" class="toggle">
                        <div class="form-group col-sm-12 oculto">
                            {!! Form::text('idpago', null, ['class' => 'form-control idpago', 'disabled'=>'disabled']) !!}
                        </div>
                        <div class="form-group col-sm-12">
                            {!! Form::label('fechaabono', 'Fecha de abono:') !!}
                            {!! Form::text('fechaabono', null, ['class' => 'form-control fecha']) !!}
                        </div>
                        <div class="form-group col-sm-12">
                            {!! Form::label('cantidadabono', 'Cantidad abono:') !!}
                            {!! Form::text('cantidadabono', null, ['class' => 'form-control moneda']) !!}
                        </div>
                    </div>
                    <div id="pageHistorial" class="toggle oculto">
                        <table class="table" id="tbHistorialAbonos">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-center">Saldo</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer container-fluid ">
                 <a type="button" class="btn btn-success col-xs-5 ladda-button toggle" data-style="expand-right" id="guardarAbono">Guardar</a>
                    {{--<a type="button" data-parent="modalAbonos" class="btn btn-info col-xs-5 ladda-button toggle" data-style="expand-right" onclick="mostrarAbonos()">Ver abonos</a>--}}
                    <a type="button" data-parent="modalAbonos" class="btn btn-info col-xs-5 ladda-button toggle oculto" data-style="expand-right">Ingresar Abono</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalGenerar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><i class = "fa fa-file-text-o"></i>&nbsp;Generar Recibo</h4>
                </div>
                <div class="modal-body container-fluid">
                    <div class="form-group col-sm-12">
                        Cliente: <span class="cliente"></span>
                    </div>
                    <div class="form-group col-sm-12 oculto">
                        {!! Form::text('conceptos', null, ['class' => 'form-control conceptos', 'disabled'=>'disabled']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('recibo', 'Recibo:') !!}
                        {!! Form::select('recibo', array("nota"=>"Nota","invoice"=>"Invoice","factura"=>"Factura"), null, ['class' => 'form-control','onchange'=>'checarRecibo(this.value)']) !!}
                    </div>
                    <div class="form-group col-sm-6" id="numfactdiv">
                        {!! Form::label('idFact', 'Serie y Folio:') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <input type="checkbox" id="chknumfact" name="chknumfact" title="Si solo requiere capturar el numero de factura sin timbrar, marque esta casilla e ingrese el numero de factura.">
                            </span>
                            {!! Form::text('idFact', null, ['class' => 'form-control', 'placeholder'=> 'Ejemplo: NO51']) !!}
                        </div>
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('clienterecibo', 'Cliente de recibo:') !!}
                        <select id="clienterecibo" name="clienterecibo" class="form-control">
                            @foreach($clientes as $cliente)
                                <option data-factura="{!! $cliente->factura !!}"
                                value="{!! $cliente->id !!}">{!! $cliente->nombre !!}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-12">
                        {!! Form::label('aseguradorarecibo', 'Aseguradora:') !!}
                        {!! Form::text('aseguradorarecibo', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="modal-footer container-fluid">
                    <a type="button" class="btn btn-success col-xs-5 ladda-button" data-style="expand-right" id="generarRecibo">Generar recibo</a>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
