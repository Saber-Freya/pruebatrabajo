<?php
// Palabras clave para toda la página
$keywords = "";
?>

<head xmlns="http://www.w3.org/1999/html">

    {{-------------------------------------------- Metas Generales -------------------------------------------------}}
    <meta charset="utf-8">
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8">﻿
    <meta name="ROBOTS" content="INDEX,FOLLOW">
    <meta name="keywords" content="<?php $keywords ?>">
    <meta name="author" content="http:\/\/e-consulting.com.mx">
    <meta name="rating" content="General">

    {{----------------------------------- Metas para caracteres y menu xs ------------------------------------------}}
    <meta http-equiv = "X-UA-Compatible" content = "IE=edge">
    <meta name = "viewport" content = "width=device-width, initial-scale=1">

    {{----------------------------------- Meta para el toquen de Laravel ------------------------------------------}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ env('PROYECTO') }}</title>

    {{--------------------------------------------- Favicon --------------------------------------------------------}}
    <link href = "{{ asset('/img/favicon/favicon.ico?v=2') }}" rel = "icon">


    {{----------------------------------------------- CSS ----------------------------------------------------------}}
    <link href = "{{ asset('/css/estilos.css') }}" rel = "stylesheet">
    {{-- datepicker --}}
    <link rel="stylesheet" href="{{ asset('/js/datepicker-1.5.1/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href = "{{ asset('/js/kartik-v/css/fileinput.min.css')}}" media="all"  type="text/css">
    {{-- Hora --}}
    <link rel="Stylesheet" href = "{{ asset('/js/timePicker/timePicker.css')}}" type="text/css">

    {{--------------------------------------------- JQuery ---------------------------------------------------------}}
    <script src="{{url('//code.jquery.com/jquery-1.11.3.min.js')}}"></script>
    <script src="{{url('//code.jquery.com/jquery-migrate-1.2.1.min.js')}}"></script>


    {{------------------------------------------- BOOTSTRAP -------------------------------------------------------}}
    <!-- Último compilado y CSS minified -->
    <link rel="stylesheet" href="{{url('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css')}}"
        integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ=="
        crossorigin="anonymous">

    <!-- Tema Opcional -->
    <link rel="stylesheet" href="{{url('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css')}}"
        integrity="sha384-aUGj/X2zp5rLCbBxumKTCw2Z50WgIr1vs/PFN4praOTvYXWlVyh2UtNUU0KAUhAX" crossorigin="anonymous">

    <!-- Último compilado y minificado JavaScript -->
    <script src="{{url('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js')}}"
        integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ=="
        crossorigin="anonymous"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">


    {{-------------------------------------------- Scripts ----------------------------------------------------------}}
    <script src = "{{ url('js/scripts.js') }}"></script>

    {{--Contacto--}}
    {{--<script src = "{{ asset('js/contacto.js') }}"></script>--}}

    {{--piccut--}}
    <script src = "{{ asset('/js/jqueryui/jquery-ui.min.js') }}"></script>
    <script src = "{{ asset('/js/piccut/src/jquery.picture.cut.js') }}"></script>

    {{--Archivo--}}
    <script src = "{{asset('/js/kartik-v/js/plugins/canvas-to-blob.min.js')}}" type="text/javascript"></script>

    <script src="{{url('http://multidatespickr.sourceforge.net/jquery-ui.multidatespicker.js')}}"></script>

    {{--Switch--}}
    <script src = "{{asset('/js/switch/js/bootstrap-switch.js')}}"></script>
    <link href = "{{ asset('/js/switch/css/bootstrap3/bootstrap-switch.css') }}" rel = "stylesheet">

    {{--para que funcione promise en navegadores diferente a mozilla--}}
    <script src = "{{asset('js/promise-7.0.4.min.js')}}"></script>

    {{--lollipop--}}
    <script src = "{{ asset('/js/lollipop/lollipopG.js') }}"></script>
    <link href = "{{ asset('/js/lollipop/lollipopG.css') }}" rel = "stylesheet">


{{--Subir multiples archivos--}}
<script src = "{{ asset('js/kartik-v/js/fileinput.min.js')}}" type="text/javascript"></script>

{{--Alertas Swal--}}
{{--<link rel="stylesheet" href="{{ asset('js/sweetalert/sweetalert.css') }}">
<script type="text/javascript" src = "{{ asset('js/sweetalert/sweetalert.min.js') }}"></script>--}}
<script type="text/javascript" src = "{{ asset('js/sweetalert2/sweetalert2.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('js/sweetalert2/sweetalert2.min.css') }}">


{{--Multiselect--}}
<link rel="stylesheet" href="{{ asset('js/multiselect/css/multi-select.css') }}">
<script type="text/javascript" src = "{{ asset('js/multiselect/js/jquery.multi-select.js') }}"></script>

{{--Input Fecha--}}
<script src = "{{ asset('/js/datepicker-1.5.1/js/bootstrap-datepicker.min.js') }}"></script>
<script src = "{{ asset('/js/datepicker-1.5.1/locales/bootstrap-datepicker.es.min.js') }}"></script>
<script src = "{{ asset('/js/moment.js') }}"></script>

{{--Subir multiples archivos--}}
<script src = "{{ asset('js/kartik-v/js/fileinput_locale_es.js')}}"></script>

{{--Formato moneda--}}
<script src = "{{asset('js/jquery.price_format.2.0.js')}}"></script>
<script src = "{{asset('js/jquery.formatCurrency-1.4.0.js')}}"></script>

<script src = "{{asset('js/waiting.js')}}"></script>

{{--Input Hora--}}
<script src = "{{ asset('/js/timePicker/jquery.timePicker.js') }}"></script>
</head>