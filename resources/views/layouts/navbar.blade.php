<!-- Fixed navbar -->
<nav id="barra" class="navbar navbar-expand-md navbar-dark">
    <div id="logo">
        <img src="/armada.png" width="35px" height="auto">
    </div>
    <a id="titulo" class="navbar-brand" href="/">Holistic</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav bd-navbar-nav flex-row">
            <li class="nav-item">
                <a class="nav-link" href="/">Inicio</a>
            </li>
            @if(Auth::check())
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Proyectos
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/proyectos/">Activos</a>
                        {{-- <div class="dropdown-divider"></div> --}}
                        @if(Auth::user()->hasRole('Administrador')||Auth::user()->hasRole('OCR'))
                            <a class="dropdown-item" href="/proyectosArchivados">Terminados</a>
                        @endif
                    </div>
                </li>
                @if(Auth::user()->hasRole('Administrador'))
                    <li class="nav-item">
                        <a class="nav-link" href="/users/">Usuarios</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Configuraciones
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/areas/">Gestionar áreas</a>
                            <a class="dropdown-item" href="/tipotareas/">Gestionar tipos de tarea</a>
                            <a class="dropdown-item" href="/propiedadesGrafico/">Gestionar colores gráfico</a>
                        </div>                        
                    </li>
                @endif                
                <li class="nav-item">
                    <a class="nav-link" href="/about/">Acerca de</a>
                </li>
            @endif
        </ul>
        <ul class="navbar-nav flex-row ml-md-auto d-none d-md-flex">
        @if(Auth::check())
            <li class="nav-item">
                <span class="navbar-text" href="">
                    @if(Auth::user()->hasRole('Administrador'))
                        <b>Administrador: </b>
                    @elseif(Auth::user()->hasRole('OCR'))
                        <b>OCR: </b>
                    @elseif(Auth::user()->hasRole('Usuario'))
                        <b>Usuario: </b>
                    @endif
                    @if(Auth::user()->cargo != '')
                    {{Auth::user()->cargo}} - 
                    @endif
                    {{Auth::user()->nombre}}
                </span>
            </li>
            <li class="nav-item">
                <a class="btn btn-warning ml-3" onclick="return confirm('¿Desea finalizar su sesión?')" href="{{action('SessionsController@destroy')}}">Cerrar sesión
                    <i class="fas fa-sign-in-alt"></i>
                </a>
            </li>
        @endif
        </ul>
    </div>
</nav>

