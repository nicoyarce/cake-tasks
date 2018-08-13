<!-- Fixed navbar -->
<nav id="barra" class="navbar navbar-expand-md navbar-dark">
    <div style="width:40px;height:40px;">
        <img id="logo" src="/armada.png" width="40px" height="auto">
    </div>
    &nbsp;
    &nbsp;
    <a class="navbar-brand" style="color: white">Holistic</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="/">Home</a>
            </li>            
            @if(Auth::check())
            <li class="nav-item">
                <a class="nav-link" href="/proyectos/">Proyectos</a>
            </li>
            @if(Auth::user()->hasRole('Administrador'))
                <li class="nav-item">
                    <a class="nav-link" href="/users/">Usuarios</a>
                </li>                
            @endif
            @endif
        </ul>
        @if(Auth::check())
            <span class="navbar-text" href="">
                @if(Auth::user()->hasRole('Administrador'))
                    <b>Administrador: </b>{{Auth::user()->nombre}}
                @elseif(Auth::user()->hasRole('OCR'))
                    <b>OCR: </b>{{Auth::user()->nombre}}
                @elseif(Auth::user()->hasRole('Usuario'))
                    <b>Usuario: </b>{{Auth::user()->nombre}}
                @endif
            </span>
            <a class="nav-link" onclick="return confirm('¿Desea finalizar su sesión?')" href="{{action('SessionsController@destroy')}}">Cerrar sesion</a>
        @endif
    </div>
</nav>
