@include('layout.script')
<!-- Nuevo Menu -->
<nav class="navbar navbar-default navbar-static-top container-fluid" role="navigation">
    <!-- El logotipo y el icono que despliega el menú se agrupan
         para mostrarlos mejor en los dispositivos móviles -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Desplegar navegación</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class = "navbar-brand" href = "{{ url('/') }}">
            <img class="img-responsive" height="auto" width = "150px"  src="{{asset('img/logo/logo.png')}}" alt="Logo"/>
        </a>
    </div>

    <!-- Agrupar los enlaces que se pueda ocultar al minimizar la barra -->
    <ul class = "nav navbar-top-links navbar-right">
        @if (Auth::guest())
            <li><a href = "{{ url('/') }}">Iniciar Sesion</a></li>
        @else
            <li><a href = "{{ url('/cerrarSesion') }}">Cerrar Sesión</a></li>
        @endif
    </ul>
    {{--Campanita--}}
    <ul class = "nav navbar-top-links navbar-right">
        <li>
            <a href="javascript:void(0)" data-toggle="modal" data-target="#modalCampanita" class="btn campanita">
                <i class="fa fa-bell-slash"></i>
            </a>
            <a href="javascript:void(0)" data-toggle="modal" data-target="#modalCampanita" class="btn alert-on campanitaOn ocultar">
                <i class="fa fa-bell"></i>
            </a>
        </li>
    </ul>
    <!-- Se cierra la agrupacion de los enlaces de navegacion -->
    @if(Auth::guest())

    @else

    <!-- Menu Arriba -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav">
            <li><a href = "{{ url('bienvenido') }}"><i class = "fa fa-calendar"></i> Calendario</a></li>
            {{--<li><a href = "{{ url('/') }}"><i class = "fa fa-calendar"></i> Calendario</a></li>--}}
            @if(Entrust::can('ver_pacientes'))<li> <a href = "{{ url('/clientes') }}"> <i class = "fa fa-users"></i> Pacientes</a> </li>@endif
            {{---------------------------------- Citas ---------------------------}}
            <li>
                <a href = "{{ url('/servicios') }}"><i class="fa fa-newspaper-o"></i> Citas</a>
            </li>
            {{---------------------------------- Cirugias ---------------------------}}
            <li>
                <a href = "{{ url('/cirugias') }}"><i class="fa fa-user-md"></i> Cirugías</a>
            </li>
            {{---------------------------------- Historial ---------------------------}}
            {{--<li>
                <a href = "{{ url('/servicios/inicio/todo') }}"><i class="fa fa-heartbeat"></i> Historial</a>
            </li>--}}
            {{---------------------------------- Reportes ---------------------------}}
            @if(Entrust::can('crear_reportes'))
                <li>
                    <a href="#" data-toggle="modal" data-target="#Reportes"><i class="fa fa-medkit"></i> Reporte</a>
                </li>
            @endif
            {{----------------------------Altas-------------------------------------}}
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-folder-open"></i> Altas <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                    <li><a href = "{{ url('/auxiliars') }}"> <i class = "fa fa-users"></i> Auxiliares</a></li>
                    <li><a href = "{{ url('/productos') }}"><i class = "fa fa-stethoscope"></i> Materiales</a></li>
                    <li><a href = "{{ url('/costos') }}"><i class = "fa fa-money"></i> Costos</a></li>
                    @if(Entrust::can('ver_fechas'))<li> <a onclick="abrirModal('this')"> <i class = "fa fa-calendar"></i> Fechas Inhábiles</a> </li>@endif
                    <li><a href = "{{ url('/horarios') }}"> <i class = "fa fa-clock-o"></i> Horarios</a> </li>
                </ul>
            </li>
            {{---------------------------------- Usuarios ---------------------------}}
            @if(Auth::user()->hasRole('admin'))
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-users"></i> Usuarios <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        @if(Entrust::can('ver_roles'))
                            <li><a href = "{{ url('/roles') }}"> <i class = "fa fa-lock"></i> Perfiles</a></li>
                        @endif
                        @if(Entrust::can('ver_usuarios'))
                            <li><a href = "{{ url('/usuarios') }}"><i class="fa fa-user"></i> Usuarios </a></li>
                        @endif
                    </ul>
                </li>
            @endif
        </ul>
    </div>
    @endif
</nav>

@include('layout.modal')

<script>
    function abrirModal(valor){ $('#modalDisponibilidad').modal(); }
    function abrirModal2(valor){ $('#modalHorarios').modal(); }
    function reportes(valor){ $('#Reportes').modal(); }
    function abrirModalHorario(valor){ $('#modalHorarios').modal(); }
</script>