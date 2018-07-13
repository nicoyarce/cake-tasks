<!doctype html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Proyecto Armada</title>
		<!-- Bootstrap core CSS -->
		<link rel="stylesheet" href="/css/bootstrap.css">
		<!-- Custom styles for this template -->
		<link href="/css/sticky-footer-navbar.css" rel="stylesheet">
		<link href="/css/all.css" rel="stylesheet">
		
	</head>
	<body>
		<header>
			<!-- Fixed navbar -->
			<nav class="navbar navbar-expand-md navbar-dark bg-dark">
				<a class="navbar-brand" href="/">Proyecto Armada</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarCollapse">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item active">
							<a class="nav-link" href="/">Home</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/proyectos/">Proyectos</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/tareas/">Tareas</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/grafico/">Gráfico</a>
						</li>
					</ul>
				</div>
			</nav>
		</header>
		<!-- Begin page content -->
		<main role="main" class="container">
			
			@yield('content')
			
		</main>
		
		<footer class="footer">
			<div class="container">				
				<span class="text-muted small">Desarrollado por Nicolas Oyarce</span>
			</div>
		</footer>
		
	</body>
</html>
