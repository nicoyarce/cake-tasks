<!doctype html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Holistic</title>
		<!-- Bootstrap core CSS -->
		<link href="/css/bootstrap.css" rel="stylesheet" >
		<!-- Custom styles for this template -->
		<link href="/css/personal.css" rel="stylesheet">
		<!--Font Awesome -->
		<link href="/css/all.css" rel="stylesheet">
	</head>
	<body>
		<header>
			<!-- Fixed navbar -->
			<nav id="barra" class="navbar navbar-expand-md navbar-dark">
				<div style="width:40px;height:40px;">
					<img id="logo" src="/armada.png" width="40px" height="auto">
				</div>
				&nbsp;
				&nbsp;
				<a class="navbar-brand" href="/">Holistic</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarCollapse">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item">
							<a class="nav-link" href="/">Home</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/proyectos/">Proyectos</a>
						</li>											
					</ul>
					@if(Auth::check())
					<a class="nav-link" href="#">{{Auth::user()->name}}</a>
					<a class="nav-link" onclick="return confirm('Desea finalizar su sesion')" href="{{action('SessionsController@destroy')}}">Cerrar sesion</a>
					@else
					<a class="nav-link" href="{{action('RegistrationController@create')}}">Registrarse</a>
					<a class="nav-link" href="{{action('SessionsController@create')}}">Iniciar sesion</a>
					@endif
				</div>
			</nav>			
		</header>
		<!-- Begin page content -->
		<br>
		@include('flash::message')

		<main role="main" class="container">			
			@yield('content')
		</main>

		<!-- Begin footer -->
		<footer class="footer">
			<div class="container">
				<span class="text-muted small">Desarrollado por Nicolas Oyarce</span>
			</div>
		</footer>
	</body>
</html>
