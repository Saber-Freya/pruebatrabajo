@extends('app')@section('content')
<div class="container">
    @include('flash::message')
    <div class="row">
        <h2 class="pull-left">Todas las Citas</h2>
        <a class = "btn btn-primary pull-right" style="margin-top: 25px" href = "{!! url('bienvenido') !!}"><i class="fa fa-calendar"></i> Calendario</a>
    </div>

    <div class="col-xs-12">Hay {{ sizeof($servicios) }} Citas</div>

    <div class="row">

        <div class="col-xs-12" align="right">
            <i class="fa fa-info-circle" title="Dejar vacío para mostrar todos los registros"></i>
            <input class="busqueda" id="busquedaAvanzada" type="text" placeholder="Búsqueda">
            <a class="btn-buscar" style="cursor:pointer" title="Buscar" onclick="busquedaAvanzada()"><i class="fa fa-search"></i></a>
        </div>

        @if($servicios->isEmpty())
            <div class="text-center">No hay registros.</div>
        @else
            <div class="table-responsive col-xs-12">
                <table class="table">
                    <thead>
                    {{--<th>Orden</th>--}}
                    <th>Fecha</th>
                    <th>Hora de la Cita</th>
                    <th>Paciente</th>
                    <th>Tipo</th>
                    <th>Total</th>
                    <th>Estatus de pago</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th width="85px">Acción</th>
                    </thead>
                    <tbody>
                    @foreach($servicios as $servicio)
                        <?php
                            if($servicio->tipo == 1){$tipo = "Consulta";}else{$tipo = "Cirugia";}
                            if($servicio->estatus == 1){$estatus = "Pagado";}else{$estatus = "Pendiente de Pago";}
                            if($servicio->inicioHora == '00:00:00'){$inicioHora = "Pendiente";}else{$inicioHora= $servicio->inicioHora;}
                            if($servicio->finHora == '00:00:00'){$finHora = "Pendiente";}else{$finHora= $servicio->finHora;}

                            $fecha_completa = $servicio->fecha .' '.$servicio->hora;
                            $fecha_completa = new DateTime($fecha_completa);
                            $fecha_completa = $fecha_completa->format('Y-m-d H:i:s');
                            $hoy = \Carbon\Carbon::now();
                        ?>
                        <tr>
                            {{--<td>{!! $servicio->orden !!}</td>--}}
                            <td>{!! $servicio->fecha !!}</td>
                            <td>{!! $servicio->hora !!}</td>
                            <td>{!! $servicio->paciente !!}</td>
                            <td>{!! $tipo !!}</td>
                            <td>$ {!! number_format($servicio->costo, 2, '.', ',') !!}</td>
                            <td>{!! $estatus !!}</td>
                            <td>{!! $inicioHora !!}</td>
                            <td>{!! $finHora !!}</td>
                            <td width="80px">
                                {{--@if($servicio->tipo == 2)
                                    <a onclick="verPDF('{!! $servicio->recibo !!}')" href="javascript:void(0)"><i alt="PDF" title="Ver Recibo" class="fa fa-file-pdf-o"></i></a>
                                @endif--}}
                                {{--Si se nececita obtener por estatus fin $servicio->finHora == '00:00:00'--}}
                                @if( $fecha_completa >= $hoy)
                                    <a title="Enviar Recordatorio de Cita" id_servicio="{!! $servicio->id !!}" id_cliente="{!! $servicio->id_cliente !!}" href="#" onclick="crearCorreo(this)"><i class = "fa fa-paper-plane"></i></a>
                                @endif
                                @if($servicio->estatus == 0)
                                    <a onclick="pagar('{!! $servicio->id !!}')" title="Marcar como pagado" class="cursor"><i class="fa fa-check" aria-hidden="true"></i></a>
                                @endif
                                @if(Entrust::can('eliminar_servicios'))
                                <a title="Cancelar" href="#" data-id="{!! $servicio->id !!}"
                                   onclick="return borrarElemento(this)"><i class="glyphicon glyphicon-remove"></i>
                                </a>
                                @endif
                                {{--@if(Entrust::can('eliminar_servicios'))
                                    <a title="Borrar" href="#" data-slug="servicios" data-id="{!! $servicio->id !!}"
                                       onclick="return borrarElemento(this)"><i class="fa fa-trash-o"></i>
                                    </a>
                                @endif--}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    {!!  str_replace('/servicios', '/dr_basico/servicios', $servicios->render()) !!}
</div>

<script>
    $("#document").ready(function(){
        $("#busquedaAvanzada").on("keyup",function(e){
            if(e.key == "Enter"){
                busquedaAvanzada();
            }
        });
    });

    function busquedaAvanzada(){
        $.post('servicios/busquedaAvanzada', {
            _token: $('meta[name=csrf-token]').attr('content'),
            busqueda: $("#busquedaAvanzada").val()
        })
        .done(function (data) {
            if(!data){
                swal("Espere", "Algo esta inpidiendo la busqueda, intente nuevamente", "warning");
            }
            var newdoc = document.open("text/html", "replace");
            newdoc.write(data);
            newdoc.close();
        })
        .fail(function () {
            swal("Upps", "No se encontraron resultados, intenta con otra busqueda", "info");
        });
    }

    function pagar(id){
        $.ajax({
            type: 'POST',
            url: '/dr_basico/servicios/pagar/'+id,
            data:{
                _token: $('meta[name=csrf-token]').attr('content'),
            },success: function(data){
                swal("Recibo pagado","","success");
                window.location.reload();
            },error: function (ajaxContext) {
                swal("Espere","Algo salio mal, reintente de nuevo","warning");
            }
        });
    }

</script>
@endsection

