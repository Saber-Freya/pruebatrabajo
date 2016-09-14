<!-- Auxiliares -->
<div class = "modal fade" id = "modalAuxiliares{!! $cirugia->id !!}" tabindex = "-1" role = "dialog" aria-labelledby = "myModalLabel"
     aria-hidden = "true">
    <div class = "modal-dialog modal-lg">
        <div class = "modal-content">
            <div class = "modal-header">
                <button type = "button" class = "close" data-dismiss = "modal" aria-label = "Close"><span
                            aria-hidden = "true">&times;</span></button>
                <h4 class = "modal-title"><i class="fa fa-users"> Auxiliar(es) de esta Cirugía</i></h4>
            </div>
            <div class = "modal-body">
                <div class = "panel">
                    <div class = "panel-body">
                        <div class="table-responsive col-xs-12">
                            <table class="table">
                                <thead>
                                {{--<th>ID</th>--}}
                                <th>Auxiliar</th>
                                {{--<th>Domicilio</th>--}}
                                {{--<th>Teléfono</th>--}}
                                <th>Pago</th>
                                <th>Comentarios</th>
                                <th>Fecha de Pago</th>
                                <th>Estatus</th>
                                <th width="50px">Acción</th>
                                </thead>
                                <tbody>
                                <?php
                                $auxiliares = $cirugia->auxiliares;
                                ?>
                                @foreach($auxiliares as $auxiliar)
                                    <?php
                                    $estatus = $auxiliar->estatus;
                                    $fecha_pago = $auxiliar->fecha_pago;
                                    ?>
                                    <tr>
                                        {{--<td>{!! $cirugia->id !!}</td>--}}
                                        <td>{!! $auxiliar->nombre !!} {!! $auxiliar->apellido !!}</td>
                                        {{--<td>{!! $auxiliar->domicilio !!}</td>--}}
                                        {{--<td>{!! $auxiliar->telefono !!}</td>--}}
                                        <td><input  id="p{!! $cirugia->id !!}{!! $auxiliar->id !!}" class="form-control moneda" placeholder="{!! number_format($auxiliar->pago, 2, '.', ',') !!}"></td>
                                        <td><textarea id="c{!! $cirugia->id !!}{!! $auxiliar->id !!}" class="form-control">{!! $auxiliar->comentarios !!}</textarea></td>

                                        <td>@if ($fecha_pago != '0000-00-00'){!! $fecha_pago !!}@else @endif</td>
                                        @if ($estatus == 0)
                                            <td> Pendiente de Pago </td>
                                            <td>
                                                <a title="Guardar modificaciones" id_cirugia="{!! $cirugia->id !!}" id_auxiliar="{!! $auxiliar->id !!}" onclick="guardarModificaciones(this)"><i class="cursor glyphicon glyphicon-floppy-save"></i></a>
                                                <a title="Marcar como pagado" href="auxiliar/pagar/{!! $cirugia->id !!}/{!! $auxiliar->id !!}"><i class="fa fa-check"></i></a>
                                                {{--<a title="Borrar" href="{!! route('cirugias.delete', [$auxiliar->id]) !!}" onclick="return confirm('Seguro de borrar?')"><i class="glyphicon glyphicon-remove"></i></a>--}}
                                            </td>
                                        @else
                                            <td> Pagado</td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class = "modal-footer">
                <button type = "button" class = "btn btn-default" data-dismiss = "modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Materiales -->
<div class = "modal fade" id = "modalMateriales{!! $cirugia->id !!}" tabindex = "-1" role = "dialog" aria-labelledby = "myModalLabel"
     aria-hidden = "true">
    <div class = "modal-dialog modal-lg">
        <div class = "modal-content">
            <div class = "modal-header">
                <button type = "button" class = "close" data-dismiss = "modal" aria-label = "Close"><span
                            aria-hidden = "true">&times;</span></button>
                <h4 class = "modal-title"><i class="fa fa-stethoscope"> Material(es) de esta Cirugía</i></h4>
            </div>
            <div class = "modal-body">
                <div class = "panel">
                    <div class = "panel-body">
                        <div class="table-responsive col-xs-12">
                            <table class="table">
                                <thead>
                                <th>Cantidad</th>
                                <th>Material</th>
                                <th>Descripción</th>
                                <th>Precio Unitario</th>
                                <th>Importe</th>
                                {{--<th width="50px">Action</th>--}}
                                </thead>
                                <tbody>
                                <?php
                                $materiales = $cirugia->materiales;
                                ?>
                                @foreach($materiales as $material)
                                    <?php
                                        $importe = $material->cantidad*$material->precio_unitario;
                                    ?>
                                    <tr>
                                        <td>{!! $material->cantidad !!}</td>
                                        <td>{!! $material->nom_prod !!}</td>
                                        <td>{!! $material->descripcion_prod !!}</td>
                                        <td>$ {!! number_format($material->precio_unitario, 2, '.', ',') !!}</td>
                                        <td>$ {!! number_format($importe, 2, '.', ',') !!}</td>
                                        {{--<td>
                                            <a title="Guardar modificaciones" id_cirugia="{!! $cirugia->id !!}" id_auxiliar="{!! $material->id !!}" onclick="guardarModificaciones(this)"><i class="cursor glyphicon glyphicon-floppy-save"></i></a>
                                            <a title="Marcar como pagado" href="material/pagar/{!! $cirugia->id !!}/{!! $material->id !!}"><i class="fa fa-check"></i></a>
                                            --}}{{--<a title="Borrar" href="{!! route('cirugias.delete', [$auxiliar->id]) !!}" onclick="return confirm('Seguro de borrar?')"><i class="glyphicon glyphicon-remove"></i></a>--}}{{--
                                        </td>--}}
                                    </tr>
                                @endforeach
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>IVA</td>
                                        <td>$ {!! number_format($cirugia->iva, 2, '.', ',') !!}</td>
                                    </tr>
                                    <tr style="font-weight: bold">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>Total</td>
                                        <td>$ {!! number_format($cirugia->total_material, 2, '.', ',') !!}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class = "modal-footer">
                <button type = "button" class = "btn btn-default" data-dismiss = "modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function guardarModificaciones(este){
        var id_cirugia = $(este).attr('id_cirugia');
        var id_auxiliar = $(este).attr('id_auxiliar');
        var super_id = id_cirugia+id_auxiliar;
        var pago = $('#p'+super_id).val();
        var comentarios = $('#c'+super_id).val();

        waitingDialog.show('Guardando...', {dialogSize: 'sm', progressType: 'warning'});
        $.ajax({
            type: 'POST',
            url: 'auxiliar/guardarModificaciones',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                pago: pago,
                comentarios: comentarios,
                id_cirugia: id_cirugia,
                id_auxiliar: id_auxiliar,

            },success: function (data) {
                waitingDialog.hide();
                swal("Guardado","","success");
                $('#modalAuxiliares'+id_cirugia).modal('toggle');
                /*setTimeout("location.href = '/cirugias'",0);*/
            },error: function (ajaxContext) {
                waitingDialog.hide();
                swal("Espere","Algo salio mal, reintente de nuevo","warning");
            }
        });
    }
</script>