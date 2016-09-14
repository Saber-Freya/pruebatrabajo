<script>
    var ciudades = {!! $ciudades !!};
    var empresa = "";
    var accion = "agregar";
    @if(isset($empresa))
    empresa={!! $empresa !!};
    accion = "editar";
    @endif
    function validar(este){
        var valor = $("#aceptar").hasClass("active");

        if(!valor && accion=="agregar"){
            swal("Error","Es necesario aceptar los terminos y condiciones","error");
            return false;
        }
        var regexEMAIL = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        var regexRFC = /^[a-z]{3,4}[0-9]{6}[a-z0-9]{3}$/i;
        var regexCP = /^[0-9]{5}$/i;
        var regexCURP = /^[a-zA-Z]{4}\d{6}[a-zA-Z]{6}\d{2}$/;
        var rfc = $('#rfc').val();
        if(!regexRFC.test(rfc))
        {
            swal("Error","El RFC es incorrecto. Debe contener solo de 12 a 13 caracteres, inicia con 3 o 4 letras seguido de 6 números y termina con tres alfanuméricos.","error");
            return false;
        }
        if(!regexCP.test($('#codigo_postal').val()))
        {
            swal("Error","El Código Postal es incorrecto. Debe contener 5 numeros.","error");
            return false;
        }
        if(!regexEMAIL.test($('#email').val()))
        {
            swal("Error","El Email es incorrecto.","error");
            return false;
        }
        if(($("#regimen").val() == "Regimen de las personas fisicas con actividades empresariales y profesionales" ||
                $("#regimen").val() == "Regimen intermedio de las personas fisicas con actividades empresariales y profesionales" ||
                $("#regimen").val() == "Regimen intermedio de las personas fisicas con actividades empresariales") &&
                $("#curp").val() == "")
        {
            swal("Error","La CURP es necesaria en los Régimen de personas fisicas.","error");
            return false;
        }
        if($("#archivoCer").val() == "" || $("#archivoKey").val() == "" || $("#claveprivada").val() == "")
        {
            swal("Error","Los archivos .cer y .key son obligatorios asi como la contraseña de la llave privada.","error");
            return false;
        }
        if($("#logo").val() == "")
        {
            swal("Error","El logo es obligatorio.","error");
            return false;
        }

        swal({
            title               : "¿Está seguro de Actualizar?",
            text                : "La información de la empresa será afectada a partir del momento en que la actualize (documentos, recetas, etc.) ¿Desea continuar?",
            type                : "info",
            showCancelButton    : true,
            closeOnConfirm      : false,
            cancelButtonText    : "Cancelar",
            cancelButtonClass   : 'textoNegro',
            cancelButtonColor   : "#E0E0E0",
            confirmButtonText   : "Continuar",
            confirmButtonColor  : "#449D44"
        }).then(function () {
            $(este.form).submit();
        });

    }
    function cargarCiudades(estado){
        var result = '';
        for(var i=0;i<ciudades.length;i++){
            if(ciudades[i].estado_id==estado)
            {
                result += '<option value="'+ciudades[i].id+'">'+ciudades[i].title+'</option>';
            }
        }
        $('#ciudad').html(result).val(empresa.ciudad);
    }

    /** This is the plugin para abrir en modal un Iframe*/
    (function(a){
        a.createModal=function(b){
            defaults={
                title:"",
                message:"Your Message Goes Here!",
                closeButton:true,scrollable:false
            };
            var b=a.extend({},defaults,b);
            var c=(b.scrollable===true)?'style="max-height: 420px;overflow-y: auto;"':"";
            html='<div class="modal fade" id="myModal">';
            html+='<div class="modal-dialog modal-lg">';
            html+='<div class="modal-content">';
            html+='<div class="modal-header">';
            html+='<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>';
            if(b.title.length>0){
                html+='<h4 class="modal-title">'+b.title+"</h4>"
            }
            html+="</div>";
            html+='<div class="modal-body" '+c+">";
            html+=b.message;html+="</div>";
            html+='<div class="modal-footer">';
            if(b.closeButton===true){
                html+='<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>'
            }html+="</div>";
            html+="</div>";
            html+="</div>";
            html+="</div>";
            a("body").prepend(html);
            a("#myModal").modal().on("hidden.bs.modal",function(){
                a(this).remove();
            })
        }}
    )(jQuery);
    function verPagina(){
        var iframe = "<iframe src='https://developers.facturacionmoderna.com/activar_cancelacion.html' width='100%' height='400'>No Support</iframe>";
        var title = "Validador de archivos";

        $.createModal({
            title:title,
            message: iframe,
            closeButton:true,
            scrollable:false
        });

        return false;
    }
    function verPDF(pdf_link){
        var iframe = "<object type='application/pdf' data='"+window.location.protocol+"//"+window.location.host+"/"+pdf_link+"' width='100%' height='500'>No Support</object>";
        var title = "Terminos y condiciones";
        $.createModal({
            title:title,
            message: iframe,
            closeButton:true,
            scrollable:false
        });
        return false;
    }

    $(document).ready(function(){

        $("#estados").trigger("onchange");
        cargarCiudades(empresa.estado);
        if(accion=="editar")
            $(".esconder").remove();

        if(empresa!=null) {
            $('.imag').html("El logo no será modificado al menos que se seleccione otra imagen.");
        }
    })
</script>