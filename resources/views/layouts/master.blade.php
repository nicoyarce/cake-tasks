<!doctype html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>CakeTasks - @yield('tituloPagina')</title> 
		<link rel="icon" href="/favicon.ico" type="image/x-icon">
		<!-- Bootstrap core CSS -->
		<link href="/css/bootstrap.min.css" rel="stylesheet" >
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
		<script src="/js/jquery.rut.min.js"></script>
		{{-- MultiSelect --}}
		<script src="/js/jquery.multiselect.js" type="text/javascript"></script>
		<link rel="stylesheet" href="/css/jquery.multiselect.css" type="text/css"/>
		<script src="https://www.w3counter.com/tracker.js?id=148147"></script>
	</head>
	<body>
		<div id="carga">
			<img src="{{url('/img/ajax-loader.gif')}}">
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
		<!--Bootstrap JS -->
		<script src="/js/popper.min.js" type="text/javascript"></script>
		<script src="/js/bootstrap.min.js" type="text/javascript"></script>
		<script>			
			$('form').submit(function() {
				$('#carga').show();
			});
			$(function () {
				$('[data-toggle="tooltip"]').tooltip();
				$('[data-toggle="popover"]').popover();				
			})
			$(document).ajaxStart(function() {
				$("#carga").show();
			});
			$(document).ajaxStop(function() {
				$("#carga").hide();
			});	
			function iniciarMultiSelect() {
				$('select[multiple]').multiselect({
					columns: 1,
					search: true,
					selectAll: true,
					texts    : {
						placeholder: "Elija opciones",
						search: "Buscar", // search input placeholder text
						selectedOptions: "Seleccionado", // selected suffix text
						selectAll: "Seleccionar todos", // select all text
						unselectAll: "Deselecionar todos", // unselect all text
						noneSelected: "Ninguno seleccionado", // None selected text
					}
				});
			}		
		</script>			
	</body>
</html>
