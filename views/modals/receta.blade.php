{{--Modal CrearReceta --}}
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
                <a type="button" class="btn btn-success pull-right guardarReceta" onclick="guardarReceta(this)">Crear Receta</a>
                <a type="button" class="btn btn-warning pull-right reimprimirReceta" onclick="reimprimirReceta(this)">Reimprimir Receta</a>
            </div>
        </div>
    </div>
</div>
{{--Modal HistorialReceta --}}
<div class="modal fade" id="historialRecetas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-pencil-square-o fa-fw"></i> Historial de Recetas</h4>
            </div>
            <div class="modal-body container-fluid">
                <div class="row">
                    <div class = "table-responsive col-xs-12">
                        <table class="table">
                            <thead>
                                <th>Fecha</th>
                                <th>Receta</th>
                            </thead>
                            <tbody>
                            @foreach($recetas as $receta)
                                <tr>
                                    <td>{!! $receta->fecha !!}</td>
                                    <td>{!! $receta->receta !!}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    function receta(este){

        var hoy = new Date();
        var fecha = $.datepicker.formatDate('yy-mm-dd',new Date(hoy));

        var paciente = $(este).attr('paciente');
        var id_cliente = $(este).attr('id_cliente');
        $('.paciente').html(paciente);
        $('.fecha_receta').html(fecha);
        $('#receta').val('').removeAttr('readonly');
        $('.reimprimirReceta').addClass('hidden');
        $('.guardarReceta').attr('fecha',fecha).attr('id_cliente',id_cliente).attr('paciente',paciente).removeClass('hidden');
    }

    function recetaReimpresion(este){

        var fecha = $(este).attr('dato-fecha');
        var paciente = $(este).attr('paciente');
        var receta = $(este).attr('dato-receta');
        console.log(receta);
        $('.paciente').html(paciente);
        $('.fecha_receta').html(fecha);
        $('#receta').val(receta).attr('readonly','');
        $('.guardarReceta').addClass('hidden');
        $('.reimprimirReceta').attr('fecha',fecha).attr('paciente',paciente).removeClass('hidden');
    }

    function guardarReceta(este){
        var fecha = $(este).attr('fecha');
        var id_cliente = $(este).attr('id_cliente');
        var paciente = $(este).attr('paciente');
        var receta = $('#receta').val();

        if (receta == ""){
            return swal("Receta Vacia","Favor de ingresar la informacion de la Receta","warning");
        }

        $.ajax({
            type: 'POST',
            url: '/servicios/receta',
            data:{
                _token: $('meta[name=csrf-token]').attr('content'),
                fecha: fecha,
                id_cliente: id_cliente,
                paciente:   paciente,
                receta:     receta,
            },success: function(data){
                receta = receta.replace(/\r?\n/g, "<br>");
                var result = '';
                result += '<div class = "row" style="margin-top: 2.5cm;"></div>';
                result += '<div style="margin-left: 1cm;margin-top: 4px;">'+paciente+'</div>';
                result += '<div style="margin-left: 13cm;margin-top: -16px;">'+fecha+'</div>';
                result += '<div class = "row" style="margin-top: 0.5cm;">'+receta+'</div>';
                newWin= window.open("");
                newWin.document.write(result);

                if (!! navigator.userAgent.match(/Trident/gi)) {
                    newWin.document.execCommand('print', false, null);
                } else {  newWin.print(); }
                newWin.close();

                $('#modalReceta').modal('toggle');
                swal("Mandado a imprimir","","success");
                $('#receta').val('');
            },error: function (ajaxContext) {
                swal("Espere","Algo salio mal, reintente de nuevo","warning");
            }
        });
    }

    function reimprimirReceta(este){
        var fecha = $(este).attr('fecha');
        var paciente = $(este).attr('paciente');
        var receta = $('#receta').val();
        receta = receta.replace(/\r?\n/g, "<br>");

        var result = '';
        result += '<div class = "row" style="margin-top: 2.5cm;"></div>';
        result += '<div style="margin-left: 1cm;margin-top: 4px;">'+paciente+'</div>';
        result += '<div style="margin-left: 13cm;margin-top: -16px;">'+fecha+'</div>';
        result += '<div class = "row" style="margin-top: 0.5cm;">'+receta+'</div>';
        newWin= window.open("");
        newWin.document.write(result);

        if (!! navigator.userAgent.match(/Trident/gi)) {
            newWin.document.execCommand('print', false, null);
        } else { newWin.print(); }
        newWin.close();

        $('#modalReceta').modal('toggle');
        swal("Mandado a imprimir","","success");
        $('#receta').val('');
    }
</script>