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
		<!-- JQuery -->
		<link href="/css/jquery-ui.min.css" rel="stylesheet">
		<script src="/js/jquery-3.3.1.min.js" type="text/javascript"></script>
		<script src="/js/jquery-ui.min.js" type="text/javascript"></script>
		<!-- Datatables -->
		<link rel="stylesheet" type="text/css" href="/css/dataTables.bootstrap4.min.css">
		<script src="/js/jquery.dataTables.min.js" type="text/javascript"></script>
		<script src="/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
		{{-- Splashscreen --}}
		<link rel="stylesheet" href="/css/login.css" type="text/css"/>
    	<script type="text/javascript" src="/js/login.js"></script>
	</head>
	<body>
		<div id="carga">
			<img src="/ajax-loader.gif">
		</div>
		<header>
			@include('layouts.navbar')
		</header>
		<!-- Begin page content -->
		@include('flash::message')
		<main id="main" role="main" class="container">
			@yield('content')	
			@include('layouts.modal')
		</main>
		<!-- Begin footer -->
		{{-- <footer id="footer" class="footer" style="">
			<div class="container">
				<span class="text-muted small">Desarrollado por Nicolás Oyarce</span>
			</div>
		</footer> --}}		
		<!--Bootstrap JS -->
		<script src="/js/popper.min.js" type="text/javascript"></script>
		<script src="/js/bootstrap.min.js" type="text/javascript"></script>
		<script>			
			$('form').submit(function() {				
				$('#carga').show();
			});
			$(function () {
				$('[data-toggle="tooltip"]').tooltip()
			})
			$(document).ajaxStart(function() {
				$("#carga").show();
			});
			$(document).ajaxStop(function() {
				$("#carga").hide();
			});			
		</script>			
	</body>
</html>
