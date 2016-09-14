<script>
    function agregarEmail(){

        var email = $("#email").val().trim();
        var emailAgregado = "";
        var existe = false;

        if(email !="") {
            $('td.email').each(function(){
                emailAgregado = $(this).html().trim();
                if(email == emailAgregado) {existe = true; return existe}
            });

            if (existe == true)return swal("Espere", "Este Correo ya esta agregado", "warning");
            if(!validarEmail(email)) return swal("Espere", "Ingrese un campo de Correo Electrónico Valido", "warning");
            var ids = [];

            $('#seccion-emails tr').each(function(){
                ids.push($(this).attr('id'));
            });

            var res = "";
            res += "<tr id='"+email+"'><td class='email' >" + email + "</td>";
            res += '<input id="emails" name="e[]" type="hidden" value="'+ email +'">';
            res += "<td><div class='minus col-xs-1' onclick='quitarEmail(this)'><i class='fa fa-times' title='Quitar solo este E-mail'></i></div></td></tr>";
            $("#seccion-emails").append(res);
            /*$('#select-email').append('<option value="'+email+'" selected="selected">'+email+'</option>');*/
            $("#email").val("");

        }else{ return swal("Falta E-mail", "", "warning"); }
    }

    function validarEmail(x) {
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        if(pattern.test(x))
            return true;
        else
            return false;
    }

    function quitarEmail(x){
        $(x).parents('tr').remove();
    }

    var auxiliars = null;
    @if(isset($auxiliar))
         auxiliars = {!!$auxiliar!!};
    @endif
    $(document).on('ready',function(){
        if(auxiliars != null){

            $(".guardar").attr('dato-id', auxiliars.id);
            var res = "";
            @for($i=0;$i<count($emails);$i++)

                var email = '{!!$emails[$i]->email!!}';
                res += "<tr id='"+email+"'><td class='email'>" + email + "</td>";
                res += '<input id="emails" name="e[]" type="hidden" value="'+ email +'">';
                res += "<td><div class='minus col-xs-1' onclick='quitarEmail(this)'><i class='fa fa-times' title='Quitar solo este Correo'></i></div></td></tr>";
            @endfor
            $("#seccion-emails").append(res);
        }
    });
</script>