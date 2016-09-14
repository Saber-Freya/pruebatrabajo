@extends('app')@section('content')
<div class="container-fluid">
    @include('flash::message')
    <div class="row">
        <h2>Historial</h2>
        {{--@if(Entrust::can('crear_servicios'))<a class="btn btn-primary pull-right" href="{!! route('servicios.create') !!}">Agregar</a>@endif--}}
    </div>

    <div class="col-xs-12">Hay {{ sizeof($servicios) }} Servicios en ésta página</div>
    <div class="row">
        <div class="col-xs-12" align="right">
            <i class="fa fa-info-circle" title="Dejar vacío para mostrar todos los registros"></i>
            <input class="busqueda" id="busquedaAvanzada" type="text" placeholder="Búsqueda">
            <a class="btn-buscar" style="cursor:pointer" title="Buscar" onclick="busquedaAvanzada()"><i class="fa fa-search"></i></a>
        </div>

        @if($servicios->isEmpty())
            <div class="well text-center">No hay registros.</div>
        @else
            <div class="table-responsive col-xs-12">
                <table class="table">
                    <thead>
                        <th>FECHA</th>
                        <th>NOMBRE DEL PACIENTE</th>
                        <th>CONVENIO</th>
                        <th>CIRUGÍA REALIZADA</th>
                        <th>RENTA CON IVA</th>
                        <th>RECIBO DE MATERIAL</th>
                        <th>RECIBO</th>
                        <th>FECHA DE PAGO</th>
                        <th>PAGO DE RENTA</th>
                        <th>DR. HDZ G</th>
                        <th>EXTERNO</th>
                        <th>DR. MICHEL</th>
                        <th>CRYOABLACION</th>
                        <th>FECHA DE PAGO DE CRYO.</th>
                        <th>CANTIDAD DE PAGO</th>
                        <th width="80px">Acción</th>
                    </thead>
                    <tbody>
                    @foreach($servicios as $servicio)
                        <?php
                            if($servicio->FECHA_DE_PAGO == '0000-00-00'){$FECHA_PAGO = '';}ELSE{$FECHA_PAGO = $servicio->FECHA_DE_PAGO;}
                            if($servicio->FECHA_DE_PAGO_DE_CRYO == '0000-00-00'){$FECHA_CRYO = '';}ELSE{$FECHA_CRYO = $servicio->FECHA_DE_PAGO_DE_CRYO;}
                        ?>
                        <tr>
                            <td>{!! $servicio->FECHA !!}</td>
                            <td>{!! $servicio->paciente !!}</td>
                            <td>{!! $servicio->CONVENIO !!}</td>
                            <td>{!! $servicio->CIRUGIA_REALIZADA !!}</td>
                            <td>{!! $servicio->RENTA_CON_IVA !!}</td>
                            <td>{!! $servicio->RECIBO_DE_MATERIAL!!}</td>
                            <td>{!! $servicio->RECIBO!!}</td>
                            <td>{!! $FECHA_PAGO!!}</td>
                            <td>{!! $servicio->PAGO_DE_RENTA!!}</td>
                            <td>{!! $servicio->DR_HDZ_G!!}</td>
                            <td>{!! $servicio->EXTERNO!!}</td>
                            <td>{!! $servicio->DR_MICHEL!!}</td>
                            <td>{!! $servicio->CRYOABLACION!!}</td>
                            <td>{!! $FECHA_CRYO !!}</td>
                            <td>{!! $servicio->CANTIDAD_DE_PAGO!!}</td>
                            <td width="80px">
                                <a title="Informacion e Historial del Paciente" href="/clientes/historial/{!!$servicio->id_cliente!!}"><i class = "fa fa-clipboard"></i></a>
                                {{--@if($servicio->RECIBO_PAGO == '')
                                    <a onclick="pagar('{!! $servicio->id !!}')" title="Marcar recibo como pagado" class="cursor"><i class="fa fa-check" aria-hidden="true"></i></a>
                                @endif--}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <?php echo $servicios->render(); ?>
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
        $.post('busquedaAvanzadaAnterior', {
            _token: $('meta[name=csrf-token]').attr('content'),
            busqueda: $("#busquedaAvanzada").val()
        }).done(function (data) {
            if(!data){
                swal("Espere", "Algo esta impidiendo la búsqueda, intente nuevamente", "warning");
            }
            var newdoc = document.open("text/html", "replace");
            newdoc.write(data);
            newdoc.close();
        }).fail(function () {
            swal("Upps", "No se encontraron resultados, intenta con otra busqueda", "info");
        });
    }
    function pagar(id){
        $.ajax({
            type: 'POST',
            url: '/servicios/pagarAnterior/'+id,
            data:{
                _token: $('meta[name=csrf-token]').attr('content'),
            },
            success: function(data){
                swal("Recibo pagado","","success");
                /*setTimeout("location.href = '/servicios/inicio/todo'",3000);*/
            },error: function (ajaxContext) {
                swal("Espere","Algo salio mal, reintente de nuevo","warning");
            }
        });
    }
</script>
@endsection

