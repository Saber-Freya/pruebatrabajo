 <script>
    $(document).ready(function(){
        $("#tipo").val(0);
        $('.fecha').datepicker({
                    format        : "yyyy-mm-dd",
                    todayBtn      : "linked",
                    language      : "es",
                    orientation   : "auto",
                    autoclose     : true,
                    todayHighlight: true
       });
       $('#generarReporte').click(function(e){
            //BOTONES
            var tipo = $("#tipo").val();
            console.log(tipo);
            var paciente = $("#paciente").attr("data-value");

            if($("#fecha_ini2").is(":visible") && ($("#fecha_ini2").val()=="" || $("#fecha_fin2").val()=="")){
                swal("Atencion","No ha especificado las fechas de su reporte","warning");
                return;
            }

            if($("#paciente").is(":visible") && paciente==0){
                swal("Atencion","No ha seleccionado un paciente registrado","warning");
                return;
            }

            $("body").css("cursor","wait");
            $('#generarReporte').text("Generando Reporte...").attr("disabled",true);

            $.post('{!! url("/reportes/generar") !!}', {
                _token: $('meta[name=csrf-token]').attr('content'),
                tipo: tipo,
                fechaini: $("#fecha_ini2").val(),
                fechafin: $("#fecha_fin2").val(),
                consulta: $("#consulta").val(),
                paciente:paciente,
                statuscitas:$("#statuscitas").val()
            }).done(function (data) {

                if(tipo==2)
                    verPDF("{!! url('/reportes/descargaPDF/"+ data +".pdf') !!}");
                else if(data!=0) {
                    window.location.assign("{!! url('/reportes/descarga/" + data + ".xls') !!}");
                }else{
                    swal( "Sin resultados", "No hay resultados para su reporte","info");
                }
            }).fail(function (ajaxContext) {
                swal({ html:true, title:'Error', text:ajaxContext.statusText,type: 'error'});
            }).always(function() {

                $("body").css("cursor","default");
                $('#generarReporte').text("Generar Reporte").attr("disabled",false);
            });
        });
        $('#tipo').on('change',function(){
            var valor = $(this).val();
            $(".ocultar").hide();
            $(".campos"+valor).show();
        });
    });

 </script>
<style>
    iframe{
        border:none;
        height: 100px;
    }
</style>