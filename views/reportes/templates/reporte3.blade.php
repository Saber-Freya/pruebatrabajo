<!DOCTYPE html>
<html>
    <head>
        <meta charset = "utf-8">
    </head>
    <body>
        <table style="width: 800px;">
            <tr><td colspan="9" style="width: 25px; font-size: 34px" class="in-line">Sistema Medico</td></tr>
            <tr>
                <td colspan="6" style="font-size: 20px; font-weight: bold;" class="in-line">{!! $titulo !!}</td>
                <td colspan="2" style="text-align: right">{!! $fecha !!}</td>
            </tr>
            <tr style="height: 30px">
                <td colspan="3"></td>
                <td >Periodo:</td>
                <td >{!! $input["fechaini"] !!}</td>
                <td >{!! $input["fechafin"] !!}</td>
            </tr>
            <tr style="background-color: #d7d7d7">
                <td style="width: 10%;"><b>ORDEN</b></td>
                <td style="width: 30%"><b>NOMBRE DEL PACIENTE</b></td>
                <td><b>ASEGURADORA</b></td>
                <td><b>CONCEPTO</b></td>
                <td><b>NUMERACION</b></td>
                <td><b>TOTAL</b></td>
                <td><b>ABONOS</b></td>
                <td><b>SALDO</b></td>
                <td><b>VENCIMIENTO</b></td>
                <td><b>RECIBO</b></td>
                <td><b>STATUS</b></td>
            </tr>

            @foreach($datos as $servicio)
                <tr style="border-bottom: 1px solid grey;">
                    <td>{!! $servicio['orden'] !!}</td>
                    <td>{!! $servicio['cliente'] !!}</td>
                    <td>{!! $servicio['aseguradora'] !!}</td>
                    <td>{!! $servicio['nombre'] !!}</td>
                    <td>{!! $servicio['numeracion'] !!}</td>
                    <td>{!! $servicio['pago'] !!}</td>
                    <td>{!! $servicio['abonos'] !!}</td>
                    <td>{!! $servicio['saldo'] !!}</td>
                    <td>{!! $servicio['fecha'] !!}</td>
                    <td>{!! $servicio['documento'] !!}</td>
                    <td>{!! $servicio['status'] !!}</td>
                </tr>
            @endforeach
        </table>
    </body>
</html>