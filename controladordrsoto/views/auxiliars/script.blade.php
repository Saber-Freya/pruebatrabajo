<script>

    /*function pulsar(este){


        var tipo = $.trim($(este).text());
        if (tipo == 'Renovar Poliza'){
            $(este.form).submit();
        }else{
            $(este.form).submit();
        }
    }*/

    function agregarEmail(){

        var email = $("#email").val();

        if(email !="") {
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

        }else {
            return swal("Falta E-mail", "", "warning");
        }
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
                res += "<td><div class='minus col-xs-1' onclick='quitarEmail(this)'><i class='fa fa-times' title='Quitar solo este Correo'></i></div></td></tr>";
            @endfor
            $("#seccion-emails").append(res);
        }
    });
</script>