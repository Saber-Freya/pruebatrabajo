@extends('app')@section('content')
<div class="container-fluid">
    @include('flash::message')
    <div class="row">
        <h2 class="pull-left">Cirugías</h2>
    </div>

    <div class="col-xs-12">Hay {{ sizeof($cirugias) }} Cirugia(s)</div>

    <div class="row">
        <div class="col-xs-12" align="right">
            <i class="fa fa-info-circle" title="Dejar vacío para mostrar todos los registros"></i>
            <input class="busqueda" id="busquedaAvanzada" type="text" placeholder="Búsqueda">
            <a class="btn-buscar" style="cursor:pointer" title="Buscar" onclick="busquedaAvanzada()"><i class="fa fa-search"></i></a>
        </div>

        @if($cirugias->isEmpty())
            <div class="text-center">No hay registros.</div>
        @else
            <div class="table-responsive col-xs-12">
                <table class="table">
                    <thead>
                        <th>Fecha</th>
                        <th>Nombre del Paciente</th>
                        <th>Convenio</th>
                        <th>Cirugia Realizada</th>
                        <th>Renta con IVA</th>
                        <th><i class="info fa fa-info-circle" title="Dar clic en el total de los Materiales para ver los detalles."></i> Recibo Material</th>
                        <th>Recibo</th>
                        <th>Plaza</th>
                        <th><i class="info fa fa-info-circle" title="Dar clic en la palabra Auxiliares para ver los detalles y/o agregar datos."></i> Auxiliares</th>
                        <th>Pagado</th>
                        <th>Comentarios</th>
                        <th width="50px">Acción</th>
                    </thead>
                    <tbody>
                    @foreach($cirugias as $cirugia)
                        <?php
                            $color = '';
                            if(!empty($cirugia['auxiliares'])){
                                foreach($cirugia['auxiliares'] as $auxiliaresCir)
                                if($auxiliaresCir['estatus'] == 0){
                                    $color = 'red';
                                    break;
                                }else{
                                    $color = '';
                                }
                            }
                            if ( $cirugia->status == 1){$status = "SI";}else{$status = "NO";}
                        ?>
                        <tr>
                            <td>{!! $cirugia->fecha !!}</td>
                            <td>{!! $cirugia->paciente !!}</td>
                            <td>{!! $cirugia->convenio !!}</td>
                            <td>{!! $cirugia->cirugia !!}</td>
                            <td>$ {!! number_format($cirugia->renta, 2, '.', ',') !!}</td>
                            <td><a onclick="materiales(this)" id_cirugia="{!! $cirugia->id !!}" class="cursor removerDec" title="Ver Materiales de ésta Cirugía">$ {!! number_format($cirugia->total_material, 2, '.', ',') !!}</a></td>
                            <td>{!! $cirugia->recibo !!}</td>
                            <td>{!! $cirugia->plaza !!}</td>
                            <td><a onclick="auxiliares(this)" id_cirugia="{!! $cirugia->id !!}" class="cursor removerDec" title="Ver Auxilires de ésta Cirugía" style="color: {!! $color !!}">Auxiliares</a></td>
                            @if ($status == "NO")
                                <td title="Sin Fecha de Pago">{!! $status !!}</td>
                            @else
                                <td title="{!! $cirugia->fecha_pago !!}">{!! $status !!}</td>
                            @endif
                            <td>{!! $cirugia->comentarios !!}</td>
                            <td>
                                <a title="Editar" href="/dr_basico/cirugias/{!! $cirugia->id_servicio !!}/preparar"><i class="glyphicon glyphicon-edit"></i></a>
                                <a title="Borrar" href="#" data-slug="cirugias" data-id="{!! $cirugia->id !!}" onclick="return borrarElemento(this)"><i class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    {!!  str_replace('/cirugias', '/dr_basico/cirugias', $cirugias->render()) !!}
    <script>
        $("#document").ready(function(){
            $("#busquedaAvanzada").on("keyup",function(e){
                if(e.key == "Enter"){
                    busquedaAvanzada();
                }
            });
        });

        function busquedaAvanzada(){
            $.post('/cirugias/busquedaAvanzadaNueva', {
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
                swal("", "No se encontraron resultados, intenta con otra busqueda", "info");
            });
        }

        function auxiliares(este){
            var id_cirugia = $(este).attr('id_cirugia');
            $('#modalAuxiliares'+id_cirugia).modal();
        }

        function materiales(este){
            var id_cirugia = $(este).attr('id_cirugia');
            $('#modalMateriales'+id_cirugia).modal();
        }
    </script>

    @foreach($cirugias as $cirugia)
        @include('cirugias.modal')
    @endforeach

</div>
@endsection