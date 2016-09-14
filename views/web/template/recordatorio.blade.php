<?php $dominio = $_SERVER['SERVER_NAME']; ?>

<table style="width: 500px">
    <tr><td colspan="2" style="font-weight: bold ">LE RECORDAMOS</td></tr>
    <tr><td></td></tr>
    <tr><td><b>Sr(a).</b> {!! $servicio->paciente !!}.</td>         <td> </td></tr>
    <tr><td> </td>     <td><b></b></td></tr>
    <tr><td colspan="2">Que el día {!! $servicio->fecha !!} a las {!! $servicio->hora !!}, tiene una cita con nosotros, sin más por el momento me despido y que tenga buen día.</td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr>
        <td colspan="2" style="text-align: justify">Mensaje enviado desde {!! $dominio !!}.</td>
    </tr>
</table>