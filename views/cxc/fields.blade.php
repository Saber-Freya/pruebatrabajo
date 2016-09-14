<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Condiciones de pago</h4>
</div>
<div class="modal-body container-fluid" style="padding: 40px;">
    <div id="seccion-credito" class="col-xs-12 pad0">
        <div class="col-xs-12 grupo">
            <div class="col-xs-4"><b>Total: </b><span id="totalote"></span></div>
            <div class="col-xs-4"><b>Intereses: </b><span id="intereses"></span></div>
            <div class="col-xs-4"><b>Deuda con Intereses: </b><span id="deuda-intereses"></span></div>
        </div>
        <div class="col-sm-3 form-group">
            <label for="interes-mensual">% Interes Mensual:</label>
            <input type="text" class="form-control numero" id="interes-mensual" value="0" onkeyup="calcularCredito()">
        </div>
        <div class="col-sm-3 form-group">
            <label for="cantidad-fechas">Cantidad de pagos:</label>
            <select id="cantidad-fechas" class="form-control" onchange="calcularCredito()">
                @for($i=1;$i<=100;$i++)
                    <option value="{!! $i !!}">{!! $i !!}</option>
                @endfor
            </select>
        </div>
        <div class="col-sm-3 form-group">
            <label for="periodo-fechas">Periodo de pagos:</label>
            <select id="periodo-fechas" class="form-control" onchange="calcularCredito()">
                <option value="sem">Semanal</option>
                <option value="qui">Quincenal</option>
                <option value="men">Mensual</option>
            </select>
        </div>
        <div class="col-sm-3 form-group fechaCondicion">
            <label for="fecha-inicial" class="col-xs-12">Fecha Inicial: </label>
            <input class="form-control" id="fecha-inicial" type="text" onchange="calcularCredito()">
        </div>
        <div class="border-bot-gray col-xs-12"></div>
        <div class=""></div>
        <div class="col-xs-12" id="calendarios">
        </div>

    </div>
</div>
<div class="modal-footer container-fluid">
    <div class="instrucciones invisible">

    </div>
    <div class="col-xs-12 btn-pasar-cuenta invisible">
        <div class="btn btn-success" onclick="guardarPagos()">Guardar <i class="fa fa-archive"></i> </div>
    </div>
</div>
@include('cxc.condicionesScript')