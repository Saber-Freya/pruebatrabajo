@include('layout.script')
<nav class="navbar navbar-default navbar-static-top container-fluid" role="navigation">
    <!-- El contenido se agrupan para mostrarlos mejor en los dispositivos móviles -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Desplegar navegación</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class = "navbar-brand" href = "{{ url('/') }}">
            <img class="img-responsive suLogo" height="auto"  src="{{asset('img/uploads/empresa/logo.png')}}" alt="Logo"/>
        </a>
    </div>

    <!-- Agrupar los enlaces que se pueda ocultar al minimizar la barra -->
    <ul class = "nav navbar-top-links navbar-right col-xs-10 col-sm-2">
        @if (Auth::guest())
            <li><a href = "{{ url('/') }}">Iniciar Sesion</a></li>
        @else
            <li class = "dropdown">
                <a href = "#" class = "dropdown-toggle bienvenida" data-toggle = "dropdown" role = "button"
                   aria-expanded = "false">Bienvenido, {{ Auth::user()->name }} <span class = "caret"></span></a>
                <ul class = "dropdown-menu" role = "menu">
                    <li><a href = "{{ url('/cerrarSesion') }}">Cerrar Sesión</a></li>
                </ul>
            <li>

        @endif
    </ul>

    {{--Campanita--}}
    <ul class = "nav navbar-top-links navbar-right col-xs-1">
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
            <li id="menu-empresa">
                <a href = "{{ url('/empresas') }}">
                    <i class = "fa fa-building-o fa-fw"></i>
                    Empresa
                </a>
            </li>
            <li><a href = "{{ url('bienvenido') }}"><i class = "fa fa-calendar"></i> Calendario</a></li>
            @if(Entrust::can('ver_pacientes'))
                <li><a href = "{{ url('/clientes') }}"> <i class = "fa fa-users"></i> Pacientes</a> </li>
            @endif
            {{----------------------------------Medicamentos---------------------------}}
            @if(Entrust::can('ver_medicamentos'))
                <li><a href="{{ url('/medicamentos') }}"><i class="fa fa-plus-square"></i> Medicamentos </a></li>
            @endif
            {{---------------------------------- Citas ---------------------------}}
            @if(Entrust::can('ver_servicios'))
                <li><a href = "{{ url('/servicios') }}"><i class="fa fa-newspaper-o"></i> Citas</a></li>
            @endif
            {{---------------------------------- Cirugias ---------------------------}}
            <li>
                <a href = "{{ url('/cirugias') }}"><i class="fa fa-user-md"></i> Cirugías</a>
            </li>
            {{---------------------------------- Cuentas por cobrar ---------------------------}}
            {{--@if(Auth::user()->can("ver_cuentasporcobrar") || 1)
            <li>
                <a href = "{{url('cuentas_por_cobrar')}}">
                    <i class = "fa fa-list"></i>
                    Cuentas por Cobrar
                </a>
            </li>@endif
            @if(Auth::user()->can("ver_ingresostotales") || 1 )
               <li id="menu-relacion">
                   <a href = "{{ url('/ingresos_totales') }}">
                       <i class = "fa fa-list-alt"></i>
                       Ingresos Totales
                   </a>
               </li>
            @endif--}}
            {{---------------------------------- Reportes ---------------------------}}
            @if(Entrust::can('crear_reportes'))
                <li>
                    <!--<a href="#" data-toggle="modal" data-target="#Reportes"><i class="fa fa-medkit"></i> Reporte</a>-->
                    <a href="{{ url('/reportes') }}"><i class="fa fa-medkit"></i> Reporte</a>
                </li>
            @endif
            {{----------------------------Altas-------------------------------------}}
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-folder-open"></i> Altas <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                    <li><a href = "{{ url('/hospitals') }}"> <i class = "fa fa-hospital-o"></i> Hospitales</a></li>
                    <li><a href = "{{ url('/auxiliars') }}"> <i class = "fa fa-users"></i> Auxiliares</a></li>
                    <li><a href = "{{ url('/productos') }}"><i class = "fa fa-puzzle-piece"></i> Materiales</a></li>
                    <li><a href = "{{ url('/costos') }}"><i class = "fa fa-money"></i> Costos</a></li>
                    @if(Entrust::can('ver_horarios'))<li><a href = "{{ url('/horarios') }}"> <i class = "fa fa-clock-o"></i> Horarios</a> </li>@endif
                    @if(Entrust::can('ver_fechas'))<li> <a onclick="abrirModal('this')"> <i class = "fa fa-calendar"></i> Fechas Inhábiles</a> </li>@endif
                    <li><a href = "{{ url('/padecimientos') }}"><i class = "fa fa-bed"></i> Padecimientos</a> </li>
                    <li><a href = "{{ url('/estudios') }}"><i class = "fa fa-stethoscope"></i> Estudios</a> </li>
                    <li><a href = "{{ url('/proveedores') }}"><i class = "fa fa-thumbs-o-up"></i> Proveedores</a> </li>
                </ul>
            </li>
            {{---------------------------------- Usuarios ---------------------------}}
            @if(Entrust::can('ver_usuarios') || Entrust::can('ver_roles'))
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-user"></i> Usuarios <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        @if(Entrust::can('ver_roles'))
                            <li><a href = "{{ url('/roles') }}"> <i class = "fa fa-lock"></i> Perfiles</a></li>
                        @endif
                        @if(Entrust::can('ver_usuarios'))
                            <li><a href = "{{ url('/usuarios') }}" style="margin-top: -14px;"><i class="iconosfuente usuario"></i> Usuarios </a> </li>
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