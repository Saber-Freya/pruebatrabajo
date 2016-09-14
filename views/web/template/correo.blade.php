<?php $dominio = $_SERVER['SERVER_NAME']; ?>

<table style="width: 500px">
    <tr><td colspan="2" style="font-weight: bold ">{!! $dominio !!}</td></tr>
    <tr><td></td></tr>
    <tr><td><b>Nombre:</b> {!! $nombre !!}</td>         <td><b>Correo:</b> {!! $correo !!}</td></tr>
    <tr><td><b>Telefono:</b> {!! $telefono !!}</td>     <td><b></b></td></tr>
    <tr><td colspan="2"><b>Mensaje:</b> {!! $mensaje !!}</td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr>
        <td colspan="2" style="text-align: justify">Mensaje enviado desde la pagina de contacto en {!! $dominio !!}</td>
    </tr>
</table>