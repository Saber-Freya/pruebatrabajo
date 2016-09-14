@include('servicios.script')
<div class = "row">
    <div class = "col-xs-6 col-md-3">
        <h1 class = "page-header">Citas</h1>
    </div>
</div>

@if(isset($reagendar))
<div class = "col-xs-12">
    <a class="removerDec cursor alertaReagendar" title="Enviar alerta para reagendar esta Cita" onclick="reagendarAlerta(this)" para="1"> <i class="fa fa-refresh"> </i></a>
</div>
@endif

@if(isset($seguimiento))
    <div class = "col-xs-12">
        <a class="removerDec cursor alertaSeguimiento" title="Enviar alerta para dar seguimiento a esta Cita" onclick="reagendarAlerta(this)" para="2"> <i class="fa fa-share-square-o"> </i></a>
    </div>
@endif

<div class="form-group col-sm-5 col-lg-3">
    {!! Form::label('id_cliente', 'Paciente:') !!}
    {!! Form::select('id_cliente',$listaClientes, null, ['class' => 'form-control id_cliente']) !!}
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
        {{--<input id="fechaReagendar">--}}
    </div>
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('hora', 'Hora:') !!} <i class="fa fa-info-circle" title="Si no muestra hora, favor de verificar horarios disponibles de Consultas y Cirugías en sección Altas -> Horarios."></i>
    {!! Form::text('hora', null, ['class' => 'form-control hora','autocomplete' => 'off', 'size' => '10', 'readonly' => '']) !!}
</div>
<div id="seccion-credito" class="grupo col-xs-12 pad0 hidden">
    <div class="col-xs-12 titulo page-header-sub"><i  class = "fa fa-money"></i>&nbsp;Condiciones de Pago</div>
    <div class="col-xs-12 titulo">
        <div class="col-xs-4">Total: <div class="totalote"></div></div>
        <div class="col-xs-4">Intereses: <div id="interesesVer"></div></div><input class="hidden" id="intereses">
        <div class="col-xs-4">Deuda con Intereses: <div id="deuda-interesesVer"></div></div><input class="hidden" id="deuda-intereses">
    </div>
    <div class="col-sm-4 form-group">
        <label for="interes-mensual">Intereses Mensuales:</label> <i class="info fa fa-info-circle" title="Si no requieres intereses, agrega un 0."></i>
        <input type="number" class="form-control" id="interes-mensual" onkeyup="calcularPagos()">
    </div>
    <div class="col-sm-4 form-group">
        <label for="cantidad-fechas">Cantidad de pagos:</label>
        <select id="cantidad-fechas" class="form-control">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">19</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>
            <option value="24">24</option>
            <option value="25">25</option>
            <option value="26">26</option>
            <option value="27">27</option>
            <option value="28">28</option>
            <option value="29">29</option>
            <option value="30">30</option>
            <option value="31">31</option>
            <option value="32">32</option>
            <option value="33">33</option>
            <option value="34">34</option>
            <option value="35">35</option>
            <option value="36">36</option>
            <option value="37">37</option>
            <option value="38">38</option>
            <option value="39">39</option>
            <option value="40">40</option>
            <option value="41">41</option>
            <option value="42">42</option>
            <option value="43">43</option>
            <option value="44">44</option>
            <option value="45">45</option>
            <option value="46">46</option>
            <option value="47">47</option>
            <option value="48">48</option>
            <option value="49">49</option>
            <option value="50">50</option>
            <option value="51">51</option>
            <option value="52">52</option>
            <option value="53">53</option>
            <option value="54">54</option>
            <option value="55">55</option>
            <option value="56">56</option>
            <option value="57">57</option>
            <option value="58">58</option>
            <option value="59">59</option>
            <option value="60">60</option>
            <option value="61">61</option>
            <option value="62">62</option>
            <option value="63">63</option>
            <option value="64">64</option>
            <option value="65">65</option>
            <option value="66">66</option>
            <option value="67">67</option>
            <option value="68">68</option>
            <option value="69">69</option>
            <option value="70">70</option>
            <option value="71">71</option>
            <option value="72">72</option>
            <option value="73">73</option>
            <option value="74">74</option>
            <option value="75">75</option>
            <option value="76">76</option>
            <option value="77">77</option>
            <option value="78">78</option>
            <option value="79">79</option>
            <option value="80">80</option>
            <option value="81">81</option>
            <option value="82">82</option>
            <option value="83">83</option>
            <option value="84">84</option>
            <option value="85">85</option>
            <option value="86">86</option>
            <option value="87">87</option>
            <option value="88">88</option>
            <option value="89">89</option>
            <option value="90">90</option>
            <option value="91">91</option>
            <option value="92">92</option>
            <option value="93">93</option>
            <option value="94">94</option>
            <option value="95">95</option>
            <option value="96">96</option>
            <option value="97">97</option>
            <option value="98">98</option>
            <option value="99">99</option>
            <option value="100">100</option>
        </select>
    </div>
    <div class="col-sm-4 form-group">
        <label for="periodo-fechas">Periodo de pagos:</label>
        <select id="periodo-fechas" class="form-control">
            <option value="sem">Semanal</option>
            <option value="qui">Quincenal</option>
            <option value="men">Mensual</option>
        </select>
    </div>
    <div class="border-bot-gray col-xs-12"></div>
    <div class=""></div>
    <div class="col-xs-12 pad0">
        <div class="col-xs-12 pad0 form-group">
            <label for="fecha-inicial" class="col-xs-12">Fecha Inicial: </label>
            <div class="col-md-4 col-sm-4 col-xs-6 sandbox-container">
                <input class="form-control fecha" id="fecha-inicial" type="text">
            </div>
            <div class="col-xs-4">
                <div class="btn btn-default" onclick="calcularCredito()">Calcular Fechas</div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 construirFechas" id="fechas"></div>
    <div class="col-xs-12 btn-pasar-cuenta invisible">
        <div class="btn btn-default" onclick="guardarPagos()">Guardar Condiciones de pago</div>
    </div>
</div>

<!--- Cirugia Field --->
{{--<div class="form-group col-sm-6 col-lg-4 cirugiaSet hidden">
    {!! Form::label('cirugia', 'Cirugía a Realizar:') !!}
    {!! Form::text('cirugia', null, ['class' => 'form-control']) !!}
</div>--}}
{!! Form::hidden('cirugia', null, ['class' => 'form-control','id' => 'cirugia']) !!}

<!--- Diagnostico Field --->
<div class="form-group col-xs-12 hidden">
    {!! Form::label('diagnostico', 'Síntomas:') !!}
    {!! Form::textarea('diagnostico', null, ['class' => 'form-control']) !!}
</div>

<!--- Submit Field --->
<div class="form-group col-sm-12">
     {{--{!! Form::button('Guardar <i class = "glyphicon glyphicon-floppy-save"></i>', ['class' => 'btn btn-success',
        'type'=>'submit']) !!}--}}
    <a onclick="GuardarCita(this)" class="btn btn-success guardarServicio">
        Guardar
        <i class = "glyphicon glyphicon-floppy-save"></i>
    </a>
    {{--<a class = "btn btn-danger cancelarServicio" href = "{!! route('servicios.index') !!}">
        Cancelar
        <i class = "glyphicon glyphicon-floppy-remove"></i>
    </a>--}}
    <a class = "btn btn-danger cancelarServicio" href = "{!! url('bienvenido') !!}">
        Cancelar
        <i class = "glyphicon glyphicon-floppy-remove"></i>
    </a>
</div>

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
                <a type="button" class="btn btn-success col-xs-3 pull-right" onclick="agregarCliente()">Guardar Paciente</a>
            </div>
        </div>
    </div>
</div>
