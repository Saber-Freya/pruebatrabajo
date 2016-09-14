<script>
    $(document).on('ready', function(){
        rol_id = null;

        $('#select-permisos').multiSelect({
            selectableHeader: "<div class='custom-header'>Permisos no asignados</div>",
            selectionHeader: "<div class='custom-header'>Permisos asignados</div>",
            afterSelect:function(value){
            },
            afterDeselect:function(value){ //enviamos al servidor el id del permiso seleccionado
            }
        });

        $('.modi').on("click", function(){
            $('.modi').removeClass('active');
            $(this).addClass('active');
            var modulo = $(this).attr("modulo");
            if(modulo=="todos"){
                $(".ms-elem-selectable").removeClass('invisible');
            }else{
                $(".ms-elem-selectable").addClass('invisible');
                $(".ms-elem-selectable[modulo='"+modulo+"']").removeClass('invisible');
            }
        });

        //Para que no muestre espacio vacion cuando desasignas un permiso
        $('.ms-elem-selection').on("click", function(){
            var elemento = $(this).attr("id");
            var elementoSeparado = elemento.split("-");
            var idEle = elementoSeparado[0];

                $("li[id='"+idEle+"-selectable']").delay(1).queue(function(){
                    /*aca lo que quiero hacer después de los 1 milisegundo de retraso ejemplo 2000 para 2 segundos*/
                    $(this).removeAttr("style"); //quito el display: list-item para que no muestre eun espacio vacio;
                    $(this).dequeue(); //continúo con el siguiente ítem en la cola
                });

        });

        $('.get-permisos').on('click', function(){
            rol_id = $(this).attr('rol_id');
            $.ajax({
                url : '{!! URL::to("/permisos") !!}',
                type : 'GET',
                dataType: 'json',
                data : {id: rol_id}
            }).done(function(data){
                if (data.permisosAsignados != ""){
                    $.each(data.todosPermisos ,function(index, value){

                        $('#select-permisos option[value="'+value.id+'"]').attr('selected', false);
                    });

                    $.each(data.permisosAsignados ,function(index, value){
                        $('#select-permisos option[value="'+value.id+'"]').attr('selected', true);
                    });

                    $('#select-permisos').multiSelect('refresh');

                }else{
                    $('#select-permisos').multiSelect('deselect_all');
                }
            });
        });
    });

    function guardarRol(){
        /*var name = $("#name").val().trim();*/
        var display_name = $("#display_name").val().trim();
        var description = $("#description").val().trim();
        var permisos_id = [];
        $(".ms-selection").find(".ms-selected").each(function(){
            permisos_id.push($(this).attr("id_permiso"));
        });

        /*if(name == "")
         return swal("Error", "Necesitas agregarle un nombre al Perfil", "error");*/
        if(display_name == "")
            return swal("Espere", "Necesitas agregarle un nombre para mostrar al Perfil", "warning");
        if(permisos_id.length == 0)
            return swal("Espere", "Necesitas agregar al menos un permiso", "warning");

        $.post('{!! url('/roles/guardarRol') !!}', {
            _token: $('meta[name=csrf-token]').attr('content'),
            name: name,
            display_name: display_name,
            permisos_id: permisos_id,
            description:description
        }).done(function (data) {
            swal("Guardado","","success");
            setTimeout("location.href = '/dr_basico/roles'",0);
        }).fail(function () {
            swal("Error", "No se pudo conectar con el servidor", "error");
        });
    }

    function actualizarRol(){
        /*var name = $("#name").val().trim();*/
        var display_name = $("#display_name").val().trim();
        var description = $("#description").val().trim();
        var permisos_id = [];
        $(".ms-selection").find(".ms-selected").each(function(){
            permisos_id.push($(this).attr("id_permiso"));
        });
        /*if(name == "")
         return swal("Error", "Necesitas agregarle un nombre al Perfil", "error");*/
        if(display_name == "")
            return swal("Espere", "Necesitas agregarle un nombre para mostrar al Perfil", "warning");
        if(permisos_id.length == 0)
            return swal("Espere", "Necesitas agregar al menos un permiso", "warning");
                @if(isset($role))
                    var id = "{!! $role->id !!}";
        @endif
        if(id=="")
            return swal("Error", "Esto no deberia pasar", "error");

        $.post('{!! url('/roles/actualizarRol') !!}', {
            _token: $('meta[name=csrf-token]').attr('content'),
            id: id,
            name: name,
            display_name: display_name,
            permisos_id: permisos_id,
            description:description
        }).done(function (data) {
            swal("Actualizado","","success");
            setTimeout("location.href = '/dr_basico/roles'",0);
        }).fail(function () {
            swal("Error", "No se pudo conectar con el servidor", "error");
        });
    }
</script>