<?php
session_start();

if (!isset($_SESSION['usuario']) || !isset($_SESSION['perfil'])) {
    header("Location: login.php");
    exit;
}

$usuario = $_SESSION['usuario'];
$rol = $_SESSION['perfil'];
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Sistema Bancario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 265px;
            background-color: #343a40;
            padding-top: 20px;
            min-height: 100vh;
            position: fixed;
        }

        .sidebar .nav-link {
            color: #ffffff;
            font-weight: 500;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #495057;
            color: #fff;
        }

        .main-content {
            margin-left: 265px;
            padding: 30px;
            flex-grow: 1;
        }

        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        iframe {
            width: 100%;
            height: 80vh;
            border: none;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>

    <!-- Barra lateral -->
    <div class="sidebar">
        <div class="text-center text-white mb-4">
            <h4>Banco XYZ</h4>
            <p class="small">Bienvenido, <?= htmlspecialchars($usuario) ?></p>
        </div>

        <nav class="nav flex-column px-3">
            <a class="nav-link" href="#" onclick="cargarPagina('cliente/index.php')">Clientes</a>
            <a class="nav-link" href="#" onclick="cargarPagina('empleado/index.php')">Empleados</a>
            <a class="nav-link" href="#" onclick="cargarPagina('cuenta/index.php')">Cuentas</a>
            <a class="nav-link" href="#" onclick="cargarPagina('transaccion/index.php')">Transacciones</a>
            <a class="nav-link" href="#" onclick="cargarPagina('prestamo/index.php')">Préstamos</a>
            <a class="nav-link" href="#" onclick="cargarPagina('tarjeta/index.php')">Tarjetas de Crédito</a>
            <a class="nav-link" href="#" onclick="cargarPagina('sucursal/index.php')">Sucursales</a>
            <hr class="text-light">
            <a class="nav-link text-danger" href="logout.php">Cerrar sesión</a>
        </nav>
    </div>

    <!-- Contenido principal -->
    <div class="main-content">
        <div class="header-bar mb-4">
            <h2>Panel de Control</h2>
        </div>
        <iframe id="contenido" src="" title="Contenido principal"></iframe>
    </div>

    <script>
        function cargarPagina(url) {
            document.getElementById("contenido").src = url;
        }

        // Cargar vista inicial (cliente)
        window.onload = function() {
            cargarPagina('cliente/index.php');
        };
    </script>

</body>

</html>