
<div class="col-xs-12 col-sm-4">
    <div class="form-groupclasePadecimiento">
        {!! Form::label('id_padecimientoA', 'Padecimiento:') !!}
        <select class = "form-control id_padecimientoA" name = "id_padecimientoA" id = "id_padecimientoA"></select>
    </div>
    <div class="form-group">
        {!! Form::label('titulo', 'Titulo del archivo o conjunto de archivos a guardar:') !!}
        {!! Form::text('titulo', null, ['class' => 'form-control arch','name' => 'titulo']) !!}
    </div>
</div>

<script>
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': '{{csrf_token()}}'
            }
        });
    });
    $(document).ready( function() {

        $("#archivo").fileinput({
            uploadAsync: false,
            minFileCount: 1,
            maxFileCount: 5,
            removeLabel: "Limpiar",
            uploadLabel: "Guardar",
            browseLabel: "Agregar Archivo",
            browseIcon: '<i class="fa fa-file-pdf-o"></i>',
            uploadUrl: '{{ url("/guardarArchivos") }}',

            uploadExtraData: function () {
                var id = $('.guardarArchivo').attr('id');
                var id_padecimiento = $('#id_padecimientoA').val();
                console.log(id_padecimiento);
                if ($("#titulo").val() != "") {
                    if(id_padecimiento != 0){
                        if(id_padecimiento != null){
                            return {
                                id: id,
                                titulo: $("#titulo").val(),
                                id_padecimiento: id_padecimiento,
                            };
                        }else{
                            swal('Falta Datos', 'No puede agregar archivo si no hay padecimiento', 'warning');
                        }
                    }else{
                        swal('Falta Datos', 'Es necesario el titulo y el padecimiento', 'warning');
                    }
                }else{
                    swal('Falta Datos', 'Es necesario el titulo y el padecimiento', 'warning');
                }
            }

        });
    });
</script>

<div class="col-xs-12 col-sm-8">
    <div class="form-group">
        {!! Form::label('archivo', 'Archivos: (Puede guardar varios al mismo tiempo)') !!}
        <input id="archivo" name="archivo[]" type="file" multiple class="file-loading">
    </div>
</div>
