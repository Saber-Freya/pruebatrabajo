@extends('app')@section('content')
<div class="container">
    @include('flash::message')
    <div class="row">
        <h2 class="pull-left">Todas las Citas</h2>
        {{--@if(Entrust::can('crear_servicios'))<a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('servicios.create') !!}">Agregar</a>@endif--}}
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
            <div class="well text-center">No hay registros.</div>
        @else
            <div class="table-responsive col-xs-12">
                <table class="table">
                    <thead>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Paciente</th>
                    <th>Tipo</th>
                    <th>Total</th>
                    <th>Estatus</th>
                    <th width="85px">Acción</th>
                    </thead>
                    <tbody>
                    @foreach($servicios as $servicio)
                        <?php
                            if($servicio->tipo == 1){$tipo = "Consulta";}
                            else{$tipo = "Cirugia";}
                            if($servicio->estatus == 1){$estatus = "Pagado";}
                            else{$estatus = "Pendiente de Pago";}
                        ?>
                        <tr>
                            <td>{!! $servicio->fecha !!}</td>
                            <td>{!! $servicio->hora !!}</td>
                            <td>{!! $servicio->paciente !!}</td>
                            <td>{!! $tipo !!}</td>
                            <td>$ {!! number_format($servicio->costo, 2, '.', ',') !!}</td>
                            <td>{!! $estatus !!}</td>
                            <td width="80px">
                                {{--@if($servicio->tipo == 2)
                                    <a onclick="verPDF('{!! $servicio->recibo !!}')" href="javascript:void(0)"><i alt="PDF" title="Ver Recibo" class="fa fa-file-pdf-o"></i></a>
                                @endif--}}
                                <a title="Enviar Recordatorio de Cita" id_servicio="{!! $servicio->id !!}" id_cliente="{!! $servicio->id_cliente !!}" href="#" onclick="crearCorreo(this)"><i class = "fa fa-paper-plane"></i></a>
                                {{--<a title="Editar" href="{!! route('servicios.edit', [$servicio->id]) !!}"><i class="glyphicon glyphicon-edit"></i></a>--}}
                                @if($servicio->estatus == 0)
                                    <a onclick="pagar('{!! $servicio->id !!}')" title="Marcar como pagado" class="cursor"><i class="fa fa-check" aria-hidden="true"></i></a>
                                @endif
                                @if(Entrust::can('eliminar_servicios'))
                                    <a title="Borrar" href="#" data-slug="servicios" data-id="{!! $servicio->id !!}"
                                       onclick="return borrarElemento(this)"><i class="glyphicon glyphicon-remove"></i>
                                    </a>
                                @endif
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

    (function(a){a.createModal=function(b){defaults={title:"",message:"Your Message Goes Here!",closeButton:true,scrollable:false};var b=a.extend({},defaults,b);var c=(b.scrollable===true)?'style="max-height: 600px;overflow-y: auto;"':"";html='<div class="modal fade" id="myModal">';html+='<div class="modal-dialog">';html+='<div class="modal-content">';html+='<div class="modal-header">';html+='<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>';if(b.title.length>0){html+='<h4 class="modal-title">'+b.title+"</h4>"}html+="</div>";html+='<div class="modal-body" '+c+">";html+=b.message;html+="</div>";html+='<div class="modal-footer">';if(b.closeButton===true){html+='<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>'}html+="</div>";html+="</div>";html+="</div>";html+="</div>";a("body").prepend(html);a("#myModal").modal().on("hidden.bs.modal",function(){a(this).remove()})}})(jQuery);
    function verPDF(pdf_link){
        var iframe = '<object type="application/pdf" data="'+pdf_link+'" width="100%" height="500">No Support</object>'
        var title = "RECIBO";
        partes = title.split('/');
        title = partes[partes.length-1];
        title = title.replace('.pdf','');
        $.createModal({
            title:title,
            message: iframe,
            closeButton:true,
            scrollable:false
        });
        return false;
    }

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
            url: '/servicios/pagar/'+id,
            data:{
                _token: $('meta[name=csrf-token]').attr('content'),
            },
            success: function(data){
                swal("Recibo pagado","","success");
                window.location.reload();
            },error: function (ajaxContext) {
                swal("Espere","Algo salio mal, reintente de nuevo","warning");
            }
        });
    }
</script>
@endsection

