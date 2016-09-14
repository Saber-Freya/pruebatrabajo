@extends('app')@section('content')
<div class = "row" align="center" id="sandbox-container">
	<div class="input-daterange " id="datepicker">
		Periodo de: <input type="text" class="input-small start" name="start" />
		<span class="add-on">a: </span>
		<input type="text" class="input-small end" name="end" />
	</div>
</div>

<div class = "row margentop30" id="cargaexterna"></div>
<div class = "row margentop30 calendario"></div>
<script>
$(document).on('ready',function() {

	$(".guardarServicio,.cancelarServicio").addClass('invisible');
	$('#sandbox-container .input-daterange').datepicker({
		format	: "yyyy-mm-dd",
		language: "es",
		todayBtn: "linked",
		startDate: '0d',
		todayHighlight: true,
		autoclose: true,
	});

	$('.start').datepicker('setDate', new Date());
	var ini = $('.start').datepicker('getDate');

	var d = ini.getDate();
	var m = ini.getMonth();
	var y = ini.getFullYear();
	var fin = new Date(y, m, d+5);
	$('.end').datepicker('setDate',fin);

	$('#datepicker').on('change', function () {
		crearTablaHorario();
	});
	crearTablaHorario();
});

function crearTablaHorario() {
	var inicio = $('.start').val();
	var fin = $('.end').val();
	var cantidadFechas = Math.floor(( Date.parse(fin) - Date.parse(inicio) ) / 86400000);

	if (cantidadFechas < 0) {
		cantidadFechas = cantidadFechas * (-1);
	}
	crearHorario(cantidadFechas, inicio);
}

function crearHorario(cantidadFechas,inicio){

	var fecha_inhabil = "";
	var arrayFecha_inhabil = [];

	$.ajax({
		type   : "GET",
		url    : "/disponibles",
		success: function (data) {

			for (var i = 0; i < data.length; i++) {
				fecha_inhabil += data[i].fecha+',';
			}

			arrayFecha_inhabil = fecha_inhabil.split(',');
			filtroInhabiles(cantidadFechas,inicio,arrayFecha_inhabil);
		}
	});

}

function filtroInhabiles(cantidadFechas,inicio,arrayFecha_inhabil) {

	var ini = $('.start').datepicker('getDate');

	var d = ini.getDate();
	var m = ini.getMonth();
	var y = ini.getFullYear();

	var result = "";
	var inicio2 = inicio;
	var s2 = 1;
	var inha = '';
	var imprimir = '';
	var ocultarPanel = '';

	for (var f = 0; f <= cantidadFechas; f++) {

		//fechas inhabiles
		for (var hi = 0; hi < arrayFecha_inhabil.length-1 ; hi++) {
			inha = $.trim(arrayFecha_inhabil[hi]);
			if (inha != '') {
				/*console.log('entro');*/
				if (inha == inicio ){
					/*console.log('inhabil');*/
					imprimir = 'inhabil';
					break;
				}else{
					/*console.log('Sin ninguna fecha');*/
					imprimir = inicio;
				}
			}else{
				/*console.log('vacio');*/
				imprimir = inicio;
			}
		}

		if (imprimir != ''){
			if (imprimir == 'inhabil'){ocultarPanel = 'hidden';}else{ocultarPanel = 'show';}
		}

		var nombreDia = sacarDia(inicio);
		result += '<div class = "col-md-2 '+ocultarPanel+'">';
		result += '<h4 class = "page-header">' + nombreDia + ' ' + inicio + '</h4>';
		var horaEstatica = '08:00:00';

		for (var h = 0; h < 27; h++) {
			var fechaLimpia = inicio.replace(/\-/g, "");
			var horaLimpia = horaEstatica.replace(/\:/g, "");
			result += '<div class="panel id' + fechaLimpia + horaLimpia + '" style="margin-top: -19px;"><a onclick="asignarCita(this)" dato="' + fechaLimpia + horaLimpia + '" fecha="' + inicio + '" hora="' + horaEstatica + '" class="cursor" style="color: #868686;text-decoration: none" title="Asignar Cita a esta Hora" data-toggle="modal" data-target="#modalCita">' + horaEstatica + '</a></div>';
			result += '<a onclick="desplegar(this)" dato="' + fechaLimpia + horaLimpia + '" class="cursor" style="color:white;text-decoration: none" title="Desplegar Información"><div class="panel id' + fechaLimpia + horaLimpia + 'Fantasma hidden azulPanel" style="margin-top: -19px;">' + horaEstatica + '</div></a>';

			var separar = horaEstatica.split(':');
			var horas = separar[0];
			var minutos = separar[1];

			var sumaMinutos = parseFloat(minutos) + 30;
			if (sumaMinutos == 60) {
				horas = parseFloat(horas) + 1;
				if (horas <= 9) {
					horas = '0' + horas;
				}
				horaEstatica = horas + ':00:00';
			} else {
				horaEstatica = horas + ':' + sumaMinutos + ':00';
			}
		}
		result += '</div>';
		result += '<div class="divider"></div>';
		result += '</div>';
		$('.calendario').html(result);

		inicio = new Date(y, m, d + s2);
		inicio = $.datepicker.formatDate('yy-mm-dd', new Date(inicio));
		s2++;
	}
	buscarFecha(cantidadFechas,inicio2);
}

function buscarFecha(cantidadFechas,inicio2){
	var ini = $('.start').datepicker('getDate');
	var d = ini.getDate();
	var m = ini.getMonth();
	var y = ini.getFullYear();
	var s = 1;
	for (var f = 0; f <= cantidadFechas; f++) {
		$.ajax({
			async: false,
			type: 'GET',
			url: 'servicios/fecha/' + inicio2,
			success: function (data) {

				if (data != '') {

					var result = "";

					for (var i = 0; i < data.length; i++) {

						var tipo = data[i].tipo;
						if (tipo == 1) {tipo = 'Consulta';}else{tipo = 'Cirugia';}
						var fecha = data[i].fecha;
						var estatus = data[i].estatus;
						var paciente = data[i].paciente;
						var hora = data[i].hora;
						var id = data[i].id;
						var id_cliente = data[i].id_cliente;
						var id_padecimiento = data[i].id_padecimiento;
						var padecimiento = data[i].padecimiento;
						var sintomas = data[i].sintomas;
						var diagnostico = data[i].diagnostico;

						/*var hora = data[i].hora;*/
						var horaLimpia = hora.replace(/\:/g, "");

						/*var fecha = data[i].fecha;*/
						var fechaLimpia = fecha.replace(/\-/g, "");

						var dato = fechaLimpia+horaLimpia;
						result = contenido(
								id,tipo,paciente,estatus,dato,hora,id_cliente,fecha,padecimiento,id_padecimiento,sintomas,
								diagnostico
						);

						$('.id'+dato+'Fantasma').removeClass('hidden');
						$('.id'+dato+'Fantasma').html(
								'<strong>'+hora+' </strong>' +
								'<strong class="pull-right">'+paciente +'</strong>'
						);
						$('.id'+dato).addClass('hidden');
						$('.id'+dato).attr('style','margin-top: -19px;');
						$('.id'+dato).addClass('azulPanel');
						$('.id'+dato).html(result);
					}
				}
			}, error: function (ajaxContext) {
				swal("Espere", "Algo salio mal, reintente de nuevo o comuníquese con su administrador", "warning");
			}
		});
		inicio2 = new Date(y, m, d + s);
		inicio2 = $.datepicker.formatDate('yy-mm-dd', new Date(inicio2));
		s++;
	}
}

function sacarDia(fecha){
	fecha = new Date(fecha);
	var diaSemana = fecha.getUTCDay();

	switch (diaSemana){
		case 0: var diadelaSemana = "Domingo";
			return diadelaSemana;
			break;
		case 1: var diadelaSemana = "Lunes";
			return diadelaSemana;
			break;
		case 2: var diadelaSemana = "Martes";
			return diadelaSemana;
			break;
		case 3: var diadelaSemana = "Miércoles";
			return diadelaSemana;
			break;
		case 4: var diadelaSemana = "Jueves";
			return diadelaSemana;
			break;
		case 5: var diadelaSemana = "Viernes";
			return diadelaSemana;
			break;
		case 6: var diadelaSemana = "Sábado";
			return diadelaSemana;
			break;
		default: var diadelaSemana = "";
			break;
	}
}

function contenido (id,tipo,paciente,estatus,dato,hora,id_cliente,fecha,padecimiento,id_padecimiento,sintomas,diagnostico){
	var result = '';
	result += '<a onclick="retractar(this)" dato="'+dato+'" style="color:white;text-decoration: none" class="cursor azulPanel" title="Ocultar Información">' +
			'<strong>'+hora+' </strong><strong class="pull-right">'+paciente +'</strong><div class = "panel-heading">';
	result += '<div class = "row">';
	result += '<div class = "text-right">';
	result += '<div class = "huge">' + tipo + '</div><div>';
	result += '</div></a>';
	result += '</div>';
	result += '</div>';
	result += '</div>';
	result += '<div class = "panel-footer">';

	//Botones del footer
	if (estatus == 1) {
		result += '<i class="verde fa fa-circle fa-fw" title="Cita pagada"> </i>';
	} else {
		result += '<a href="#" class="removerDec"> <i class="rojo fa fa-circle fa-fw" title="Paciente con pago pendiente"> </i> </a>';
	}

	result += '<a title="Reagendar" href="/servicios/'+id+'/edit" class="removerDec"> <i class="fa fa-refresh fa-fw"> </i></a>';
	result += '<a onclick="informacionConsulta(this)" id_padecimiento="'+id_padecimiento+'" sintomas="'+sintomas+'" padecimiento="'+padecimiento+'" diagnostico="'+diagnostico+'" title="Ver y/o Agregar informacion de Consulta" href="#" class="removerDec" data-toggle="modal" data-target="#modalConsulta"> <i class="fa fa-plus fa-fw"> </i></a>';

	if (padecimiento != null) {
		result += '<a onclick="archivos(this)" id_padecimiento="'+id_padecimiento+'" title="Agregar Archivos" href="#" class="removerDec" data-toggle="modal" data-target="#modalArchivos"> <i class="fa fa-file-pdf-o fa-fw"> </i></a>';
	}

	if (tipo != 'Consulta') {
		//si no es consulta se supone que es cirugia y puede preparar cirugia
		result += '<a href="/cirugias/'+id+'/preparar" title="Preparar Cirugía" class="removerDec"> <i class="fa fa-user-md fa-fw"> </i></a>';
	}else{
		//si es consulta se puede crear la receta
		result += '<a onclick="receta(this)" id_servicio="'+id+'" paciente="'+paciente+'" fecha="'+fecha+'" title="Crear Receta" href="#" class="removerDec" data-toggle="modal" data-target="#modalReceta"> <i class="fa fa-pencil-square-o fa-fw"> </i></a>';
	}

	result += '<a title="Seguimiento" href="/servicios/'+id+'/seguimiento" class="removerDec"> <i class="fa fa-share-square-o fa-fw"> </i></a>';
	result += '<a title="Enviar Recordatorio" id_servicio="'+id+'" id_cliente="'+id_cliente+'" href="#" onclick="crearCorreo(this)"><i class = "fa fa-paper-plane fa-fw"></i></a>';
	result += '<a href="#" data-slug="servicios" data-id="'+id+'" onclick="return borrarElemento(this)"><i class = "pull-right fa fa-times" title="Cancelar Cita"> </i></a>';
	result += '<a title="Enviar alerta para reagendar este servicio" id_servicio="'+id+'" onclick="reagendarAlerta(this)" class="removerDec cursor"> <i class="pull-right fa fa-refresh fa-fw" style="color: rgb(180, 6, 6) !important;"> </i></a>';
	result += '<div class = "clearfix"></div>';
	result += '</div>';
	return result;
}

function seguimiento(elemento){
	var dato = $(elemento).attr('dato');
	$('.id'+dato+'Fantasma').addClass('hidden');
	$('.id'+dato).removeClass('hidden');
}

function desplegar(elemento){
	var dato = $(elemento).attr('dato');
	$('.id'+dato+'Fantasma').addClass('hidden');
	$('.id'+dato).removeClass('hidden');
}

function retractar(elemento){
	var dato = $(elemento).attr('dato');
	$('.id'+dato+'Fantasma').removeClass('hidden');
	$('.id'+dato).addClass('hidden');
}

function asignarCita(elemento){
	var hora = $(elemento).attr('hora');
	var fecha = $(elemento).attr('fecha');
	var cliente = $(elemento).attr('cliente');
	var accion = $(elemento).attr('accion');
	$("#fecha").val(fecha);
	$('.input-fecha').datepicker('setDate', fecha);
	$('#hora').val(hora);
}

function informacionConsulta(elemento){
	var id_padecimiento = $(elemento).attr('id_padecimiento');
	var sintomas = $(elemento).attr('sintomas');
	var padecimiento = $(elemento).attr('padecimiento');
	var diagnostico = $(elemento).attr('diagnostico');
	$('.guardarConsulta').attr('id', id_padecimiento);
	$('#sintomas').val(sintomas);
	$('.valor_nombre').val(padecimiento);
	$('#descripcion').val(diagnostico);
}

function archivos(elemento){
	var id_padecimiento = $(elemento).attr('id_padecimiento');
	$('#archivo').attr('id', id_padecimiento).addClass('guardarArchivo');
}

function GuardarConsulta(elemento){
	var id_padecimiento = $(elemento).attr('id');
	var nombre = $('.valor_nombre').val();
	var sintomas = $('#sintomas').val();
	var descripcion = $('#descripcion').val();

	$.ajax({
		type: 'POST',
		url: '/guardarInfoConsulta/'+id_padecimiento,
		data:{
		_token: $('meta[name=csrf-token]').attr('content'),
		nombre: nombre,
		sintomas: sintomas,
		descripcion: descripcion,
		},
		success: function(data){
			swal("Guardado","","success");
			$('#modalConsulta').modal('toggle');
			crearTablaHorario();
		},error: function (ajaxContext) {
			swal("Espere","Algo salio mal, reintente de nuevo","warning");
		}
	});
}

function reagendarAlerta(elemento){
	var id_servicio = $(elemento).attr('id_servicio');
	$.ajax({
		type: 'GET',
		url: '/reagendarAlerta/'+id_servicio,
		success: function(data){
			swal("Enviada","Se envió alerta para reagendar el servicio","success");
			window.setTimeout(function(){location.reload()},500)
		},error: function (ajaxContext) {
			swal("Espere","Algo salio mal, reintente de nuevo","warning");
		}
	});
}

function receta(este){
	var paciente = $(este).attr('paciente');
	var fecha = $(este).attr('fecha');
	var id_servicio = $(este).attr('id_servicio');
	$('.paciente').html(paciente);
	$('.fecha_receta').html(fecha);
	$('.guardarReceta').attr('id_servicio',id_servicio);
	$('.guardarReceta').attr('paciente',paciente);
	$('.guardarReceta').attr('fecha',fecha);
}

function GuardarReceta(este){
	var id_servicio = $(este).attr('id_servicio');
	var paciente = $(este).attr('paciente');
	var fecha = $(este).attr('fecha');
	var receta = $('#receta').val();

	$.ajax({
		type: 'POST',
		url: '/servicios/receta',
		data:{
			_token: $('meta[name=csrf-token]').attr('content'),
			id_servicio: id_servicio,
			receta: receta,
		},success: function(data){
			var result = '';
			result += '<div class = "row" style="margin-top: 2.5cm;"></div>';
			result += '<div style="margin-left: 1cm;margin-top: 4px;">'+paciente+'</div>';
			result += '<div style="margin-left: 13cm;margin-top: -16px;">'+fecha+'</div>';
			result += '<div class = "row" style="margin-top: 0.5cm;">'+receta+'</div>';
			w=window.open();
			w.document.write(result);
			w.print();
			w.close();
			$('#modalReceta').modal('toggle');
			swal("Mandado a imprimir","","success");
			$('#receta').val('');
		},error: function (ajaxContext) {
			swal("Espere","Algo salio mal, reintente de nuevo","warning");
		}
	});
}

</script>
<!------------------------------------------- Modal Citas --------------------------------------------->
<div class="modal fade" id="modalCita" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg tamanoModal" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Agregar Cita</h4>
			</div>
			<div class="modal-body container-fluid">
				@include('servicios.fields')
			</div>
			<div class="modal-footer container-fluid">
				<a type="button" class="btn btn-success pull-right guardarCita" onclick="GuardarCita(this)">Guardar</a>
			</div>
		</div>
	</div>
</div>

<!------------------------------------------- Modal Consulta --------------------------------------------->
<div class="modal fade" id="modalConsulta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg tamanoModal" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Agregar Informacion</h4>
			</div>
			<div class="modal-body container-fluid">
				<div class="col-sm-2 col-md-3">
					{!! Form::label('nombre', 'Titulo del Diagnostico o Padecimiento:') !!}
					{!! Form::text('nombre', null, ['class' => 'form-control valor_nombre']) !!}
				</div>
				<fieldset class="informacion">
					<!--- Sintomas Field --->
					<div class="form-group col-xs-6">
						{!! Form::label('sintomas', 'Sintomas:') !!}
						{!! Form::textarea('sintomas', null, ['class' => 'form-control']) !!}
					</div>
					<!--- Descripcion Field --->
					<div class="form-group col-xs-6">
						{!! Form::label('descripcion', 'Diagnostico:') !!}
						{!! Form::textarea('descripcion', null, ['class' => 'form-control']) !!}
					</div>
				</fieldset>
			</div>
			<div class="modal-footer container-fluid">
				<a type="button" class="btn btn-success pull-right guardarConsulta" onclick="GuardarConsulta(this)">Guardar</a>
			</div>
		</div>
	</div>
</div>

<!------------------------------------------- Modal Archivos --------------------------------------------->
<div class="modal fade" id="modalArchivos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg tamanoModal" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Agregar Archivos a esta Consulta</h4>
			</div>
			<div class="modal-body container-fluid">
				@include('documentos.documentos')
			</div>
			<div class="modal-footer container-fluid">
				<a type="button" class="btn btn-success pull-right guardarArchivos hidden" onclick="GuardarArchivos(this)">Guardar</a>
			</div>
		</div>
	</div>
</div>

<!------------------------------------------- Modal Receta --------------------------------------------->
<div class="modal fade" id="modalReceta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Receta</h4>
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
				<div class="col-xs-12">
					{!! Form::label('receta', 'Receta:') !!}
					{!! Form::textarea('receta', null, ['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="modal-footer container-fluid">
				<a type="button" class="btn btn-success pull-right guardarReceta" onclick="GuardarReceta(this)">Crear Receta</a>
			</div>
		</div>
	</div>
</div>
@endsection