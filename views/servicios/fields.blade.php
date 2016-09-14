<div class = "row">
    <div class = "col-xs-6 col-md-3">
        <h1 class = "page-header">Citas</h1>
    </div>
</div>

@if(isset($reagendar))
<div class = "col-xs-12">
    <a class="removerDec cursor alertaReagendar" title="Enviar alerta para reagendar esta Cita" onclick="reagendarAlerta(this)" para="1"> <i class="iconosfuente alertaReagendar"> </i></a>
</div>
@endif

@if(isset($seguimiento))
    <div class = "col-xs-12">
        <a class="removerDec cursor alertaSeguimiento" title="Enviar alerta para dar seguimiento a esta Cita" onclick="reagendarAlerta(this)" para="2"> <i class="iconosfuente seguimientoAlerta"> </i></a>
    </div>
@endif

{{--<div class="form-group col-sm-5 col-lg-3">
    {!! Form::label('id_cliente', 'Paciente:') !!}
    {!! Form::select('id_cliente',$listaClientes, null, ['class' => 'form-control id_cliente']) !!}
</div>--}}

<div class="form-group col-sm-5 col-lg-3">
    {!! Form::label('id_cliente', 'Paciente:') !!}
    <input type="text" name="id_cliente" id="id_cliente" class="form-control filterInput id_cliente" data-lista='{!! json_encode($clientesNlista) !!}' autocomplete="off">
</div>

<div class="col-sm-1 areaClientenuevo">
    <a class="btn btn-icon-sucess" data-toggle="modal" data-target="#modalCliente" title="Agregar Nuevo Paciente"><i class="fa fa-user-plus"></i></a>
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('tipo', 'Tipo:') !!}
    {!! Form::select('tipo', [
    '1'=>'Consulta',
    '2'=>'Cirugía'
    ], null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('id_costo', 'Consulta o Cirugía a Realizar:') !!}
    <select class = "form-control id_costo" name = "id_costo" id = "id_costo"></select>
</div>

<div class="form-group col-sm-3 col-lg-2">
    {!! Form::label('costo', 'Costo:') !!}
    {!! Form::text('costoVer', null, ['class' => 'form-control','disabled', 'id' => 'costoVer']) !!}
    {!! Form::hidden('costo', null, ['class' => 'form-control', 'id' => 'costo']) !!}
</div>

<div class="form-group col-sm-3 col-lg-2">
    {!! Form::label('fecha', 'Día de la Cita:') !!}
    {!! Form::text('fecha', null, ['class' => 'form-control','disabled','disabled']) !!}
</div>

<div class="form-group col-sm-6 col-lg-4 clasePadecimiento">
    {!! Form::label('id_padecimiento', 'Padecimiento:') !!}
    <select class = "form-control id_padecimiento" name = "id_padecimiento" id = "id_padecimiento"></select>
</div>

<div class="fechaReagendar form-group col-sm-5 col-lg-3 hidden">
    <div id="sandbox-containerCitas" align="center">
        <div class="calendario"></div>
    </div>
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('hora', 'Hora:') !!} <i class="fa fa-info-circle" title="Si no muestra hora, favor de verificar horarios disponibles de Consultas y Cirugías en sección Altas -> Horarios."></i>
    {!! Form::text('hora', null, ['class' => 'form-control hora','autocomplete' => 'off', 'size' => '10', 'readonly' => '']) !!}
</div>

<div class="form-group col-sm-5 col-lg-3">
    {!! Form::label('hospital_id', 'Hospital:') !!}
    {!! Form::select('hospital_id',$listaHospitales, null, ['class' => 'form-control hospital_id']) !!}
</div>

{!! Form::hidden('cirugia', null, ['class' => 'form-control','id' => 'cirugia']) !!}

<div class="form-group col-xs-12 hidden">
    {!! Form::label('diagnostico', 'Síntomas:') !!}
    {!! Form::textarea('diagnostico', null, ['class' => 'form-control']) !!}
</div>

<div class="titulo col-xs-12 hidden areaCirugia">
    &iquest;Preparar Cirugía?
    <i id="check-cirugia" class="fa fa-square-o" onclick="checkCirugia(this)" style="cursor: pointer"></i>
</div>

<div class="col-xs-12 invisible" id="seccion-cirugia">
    @include('cirugias.fields')
</div>

<!--- Submit Field --->
<div class="form-group col-sm-12">
    <a onclick="GuardarCita(this)" class="btn btn-success guardarServicio">
        Guardar
        <i class = "glyphicon glyphicon-floppy-save"></i>
    </a>
    <a class = "btn btn-danger cancelarServicio" href = "{!! url('bienvenido') !!}">
        Cancelar
        <i class = "glyphicon glyphicon-floppy-remove"></i>
    </a>
</div>
@include('servicios.script')
<!------------------------------------------- Modal Clientes --------------------------------------------->
<div class="modal fade" id="modalCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class = "glyphicon glyphicon-user"></i>&nbsp;Agregar Cliente</h4>
            </div>
            <div class="modal-body container-fluid">
                @include('clientes.fields')
            </div>
            <div class="modal-footer container-fluid">
                <a type="button" class="btn btn-success col-xs-3 pull-right" onclick="agregarCliente(this)">Guardar Paciente</a>
            </div>
        </div>
    </div>
</div>

<!------------------------------------------- Modal Condiciones --------------------------------------------->
<div class="modal fade" id="modalCondiciones" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     style="position: absolute; overflow: auto!important;">
    <div class="modal-dialog modal-lg" role="document" style="z-index: 2000;">
        <div class="modal-content">
            @include('cxc.fields')
        </div>
    </div>
</div>
</div>

