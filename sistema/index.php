<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<title>Sisteme Ventas</title>
</head>
<body>
	<header>
		<div class="header">
			
			<h1>Sistema Facturación</h1>
			<div class="optionsBar">
				<p><?php 
						date_default_timezone_set("America/Lima");
						$mes = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");
						$dia = array("domingo","lunes","martes","miercoles","jueves","sabado");
						 "Perú, " . $dia[date("w")] . " " . date("d") . " de " . $mes[date("m")-1] . " de " . date("Y") . "<br>";
				?></p>
				<span>|</span>
				<span class="user">Diego Príncipe</span>
				<img class="photouser" src="img/user.png" alt="Usuario">
				<a href="#"><img class="close" src="img/salir.png" alt="Salir del sistema" title="Salir"></a>
			</div>
		</div>
		<nav>
			<ul>
				<li><a href="#">Inicio</a></li>
				<li class="principal">
					<a href="#">Usuarios</a>
					<ul>
						<li><a href="#">Nuevo Usuario</a></li>
						<li><a href="#">Lista de Usuarios</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#">Clientes</a>
					<ul>
						<li><a href="#">Nuevo Cliente</a></li>
						<li><a href="#">Lista de Clientes</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#">Proveedores</a>
					<ul>
						<li><a href="#">Nuevo Proveedor</a></li>
						<li><a href="#">Lista de Proveedores</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#">Productos</a>
					<ul>
						<li><a href="#">Nuevo Producto</a></li>
						<li><a href="#">Lista de Productos</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#">Facturas</a>
					<ul>
						<li><a href="#">Nuevo Factura</a></li>
						<li><a href="#">Facturas</a></li>
					</ul>
				</li>
			</ul>
		</nav>
	</header>
	<section id="container">
		<h1>Bienvenido al sistema</h1>
	</section>
</body>
</html>