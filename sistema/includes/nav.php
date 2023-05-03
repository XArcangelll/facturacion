<nav>
			<ul>
				<li><a href="../"><i class="fa-solid fa-house" ></i> Inicio</a></li>

				<?php
				
				if($_SESSION["rol"] == 1){

				?>
				<li class="principal">
					<a href=""><i class="fa-solid fa-users"></i> Usuarios</a>
					<ul>
						<li><a href="registro_usuario.php"><i class="fa-solid fa-user-plus"></i> Nuevo Usuario</a></li>
						<li><a href="lista_usuario.php"><i class="fa-solid fa-users"></i> Lista de Usuarios</a></li>
					</ul>
				</li>

				<?php } ?>
				<li class="principal">
					<a href="#"><i class="fa-solid fa-user"></i> Clientes</a>
					<ul>
						<li><a href="registro_cliente.php"><i class="fa-solid fa-user-plus"></i> Nuevo Cliente</a></li>
						<li><a href="lista_cliente.php"><i class="fa-solid fa-users"></i> Lista de Clientes</a></li>
					</ul>
				</li>
				<?php if($_SESSION["rol"] == 1 || $_SESSION["rol"] == 2){ ?>
				<li class="principal">
					<a href="#"><i class="fa-solid fa-boxes-packing"></i> Proveedores</a>
					<ul>
						<li><a href="registro_proveedor.php"><i class="fa-solid fa-boxes-packing"></i> Nuevo Proveedor</a></li>
						<li><a href="lista_proveedor.php"><i class="fa-solid fa-boxes-packing"></i> Lista de Proveedores</a></li>
					</ul>
				</li>
				<?php } ?>
				<?php if($_SESSION["rol"] == 1 || $_SESSION["rol"] == 2){ ?>
				<li class="principal">
					<a href="#"><i class="fa-solid fa-store"></i> Productos</a>
					<ul>
						<li><a href="registro_producto.php"><i class="fa-solid fa-store"></i> Nuevo Producto</a></li>
						<li><a href="lista_producto.php"><i class="fa-solid fa-store"></i> Lista de Productos</a></li>
					</ul>
				</li>
				<?php } ?>
				<li class="principal">
					<a href="#"><i class="fa-solid fa-money-bill"></i> Ventas</a>
					<ul>
						<li><a href="nueva_venta.php"><i class="fa-solid fa-money-bill"></i> Nueva Venta</a></li>
						<li><a href="#"><i class="fa-solid fa-money-bill"></i> Ventas</a></li>
					</ul>
				</li>
			</ul>
		</nav>