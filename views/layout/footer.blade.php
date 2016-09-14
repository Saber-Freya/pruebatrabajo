<footer>
    <div class="container-fluid">
        <div class="container">
            <div class="col-xs-12 margentop100" align="center">
                <div class="col-xs-12">
                    {{--<a href="{{url('http://www.e-consulting.com.mx')}}">--}}
                        <img src="{{asset('/img/footer/img_econs.png')}}" width="328px" class="img-responsive">
                    {{--</a>--}}
                </div>
            </div>
        </div>
    </div>
</footer>

{{--Subir multiples archivos--}}
<script src = "{{ asset('js/kartik-v/js/fileinput.min.js')}}" type="text/javascript"></script>

{{--Alertas Swal--}}
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

{{--Cargando...--}}
<script src = "{{asset('js/waiting.js')}}"></script>

{{--Input Hora--}}
<script src = "{{ asset('/js/timePicker/jquery.timePicker.js') }}"></script>