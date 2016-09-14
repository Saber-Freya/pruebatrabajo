@extends('app')
@section('content')
    <div class="container-fluid">
        <div class="container">
            <div class="col-xs-12">

                <div id = "forma">
                    <div class="col-xs-12">
                        <label>Nombre</label>
                        <input class="" type="text" id="nombre" placeholder="">
                        <label>Telefono</label>
                        <input class="" type="text" name="telefono" id="telefono" placeholder="">
                        <label>Email</label>
                        <input class = "" type = "text" name = "email" id = "email" placeholder = "">
                        <label>Asunto</label>
                        <input class = "" type = "text" name = "asunto" id = "asunto" placeholder = "">
                        <label>Mensaje</label>
                        <textarea class="" rows="4" name="mensaje" id="mensaje" placeholder="Escribe tu mensaje aqui">
                        </textarea>
                        <img src = "{{asset('/captcha/captcha.php')}}" alt = "" class = "img-responsive captchaImg">
                        <label>Codigo</label>
                        <input type = "text" class = "codigocapcha" name = "code" id = "code" placeholder = "Codigo">
                        <button type="button" class="button botonvermas sendBtn button" onclick = "enviar('es')">ENVIAR</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        $("#mensaje").val("");
    </script>
@endsection







