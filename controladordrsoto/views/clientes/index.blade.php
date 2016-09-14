@extends('app')@section('content')@include('clientes.script')@include('clientes.modal')
<div class="container">
	@include('flash::message')
    <div class="row"><h2 class="pull-left">Pacientes</h2>
		@if(Entrust::can('crear_pacientes'))
			<a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('clientes.create') !!}">Agregar</a>
		@endif
    </div>

	<div class="col-xs-12">Hay {{ sizeof($clientes) }} Pacientes en esta página</div>

    <div class="row">

		<div class="col-xs-12" align="right">
			<i class="fa fa-info-circle" title="Dejar vacío para mostrar todos los registros"></i>
			<input class="busqueda" id="busqueda" type="text" placeholder="Búsqueda">
			<a class="btn-buscar" style="cursor:pointer" title="Buscar" onclick="buscarCliente()"><i class="fa fa-search"></i></a>
		</div>

		@if($clientes->isEmpty())
			<div class="well text-center">No hay registros.</div>
		@else
			<div class="table-responsive col-xs-12">
				<table class="table">
					<thead>
						{{--<th>No. de Cliente</th>--}}
						<th>Nombre</th>
						{{--<th>Dirección</th>--}}
						{{--<th>Rfc</th>--}}
						<th>Teléfono</th>
						{{--<th>Descripcion</th>--}}
						<th width="80px">Acción</th>
					</thead>
					<tbody>
						@foreach($clientes as $cliente)
							<tr>
								{{--<td>{!! $cliente->id !!}</td>--}}
								<td>{!! $cliente->nombre !!} {!! $cliente->apellido !!}</td>
								{{--<td>
									{!! $cliente->calle !!}, {!! $cliente->no_exterior !!}, {!! $cliente->no_interiior !!},
									{!! $cliente->colonia !!}, {!! $cliente->ciudad !!}, {!! $cliente->estado !!},
									{!! $cliente->pais !!}, {!! $cliente->cp !!}.
								</td>--}}
								{{--<td>{!! $cliente->rfc !!}</td>--}}
								<td>{!! $cliente->tel !!}</td>

								<td width="80px">
									@if(Entrust::can('editar_pacientes'))<a title="Editar" href="{!! route('clientes.edit', [$cliente->id]) !!}"><i class="glyphicon glyphicon-edit"></i></a>@endif
									<a title="Informacion e Historial del Paciente" href="clientes/historial/{!!$cliente->id!!}"><i class = "fa fa-clipboard"></i></a>
									{{--<a title="Historial" href = "#" data-id = "{!! $cliente->id !!}" onclick="historialCliente(this)"><i class = "fa fa-clipboard"></i></a>--}}
									@if(Entrust::can('eliminar_pacientes'))<a title="Borrar" href="#" data-slug="clientes" data-id="{!! $cliente->id !!}" onclick="return borrarElemento(this)"><i class="glyphicon glyphicon-remove"></i></a>@endif
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
        @endif
    </div>
    <?php echo $clientes->render(); ?>
	{{--<a class="btn btn-warning pull-right"href="#" data-toggle="modal" data-target="#myCliente">Generar Excel</a>--}}
</div>
<script>
	$("#document").ready(function(){
		$("#busqueda").on("keyup",function(e){
			if(e.key == "Enter"){
				buscarCliente();
			}
		});
	});

	function buscarCliente(){

		$.post('clientes/buscarCliente', {
			_token: $('meta[name=csrf-token]').attr('content'),
			busqueda: $("#busqueda").val()
		}).done(function (data) {
			if(!data){
				alert("Error, No se pudo cargar las secciones, intente nuevamente");
			}
			var newdoc = document.open("text/html", "replace");
			newdoc.write(data);
			newdoc.close();
		}).fail(function () {
			swal("Upps", "No se encontraron resultados, intenta con otra busqueda", "info");
		});
	}
</script>
@endsection