@include('clientes.script')
<!-- Modal -->
<!-- Modal -->
<div class = "modal fade" id = "myModal" tabindex = "-1" role = "dialog" aria-labelledby = "myModalLabel"
     aria-hidden = "true">
    <div class = "modal-dialog modal-lg">
        <div class = "modal-content">
            <div class = "modal-header">
                <button type = "button" class = "close" data-dismiss = "modal" aria-label = "Close"><span
                            aria-hidden = "true">&times;</span></button>
                <h4 class = "modal-title">Historial</h4>
            </div>
            <div class = "modal-body">
                <div class="panel">
                    <div class="panel-body">
                        <div class="col-xs-12">
                            <!-- List group -->
                            <ul class = "list-group">
                                <li class = "list-group-item">
                                    <i class = "fa fa-user"></i>
                                    <strong id="paciente">Paciente</strong>
                                </li>
                                <li class = "list-group-item">
                                    <div class="tablaHistorial"></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class = "modal-footer">
                <button type = "button" class = "btn btn-default" data-dismiss = "modal">Cerrar</button>
                {{--<a type = "button" class = "btn btn-primary" id="editarCliente">Editar</a>--}}
            </div>
        </div>
    </div>
</div>
<!-- Modal -->