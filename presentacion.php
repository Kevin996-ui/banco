<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema Bancario</title>
    <link href="bootstrap/css/pbootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom right, #e3f2fd, #ffffff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 30px;
        }
        .presentacion {
            background-color: #ffffff;
            border-radius: 20px;
            padding: 40px;
            max-width: 800px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .btn-login {
            margin-top: 30px;
        }
    </style>
</head>
<body>

<div class="presentacion text-center">
    <h1 class="mb-4 text-primary">Bienvenido al Sistema Bancario</h1>
    <p class="fs-5">
        Este sistema fue desarrollado para gestionar de forma eficiente las operaciones bancarias internas, incluyendo el registro y control de clientes, cuentas, transacciones, préstamos, tarjetas de crédito, empleados y sucursales.
    </p>
    <hr>
    <div class="text-start">
        <h4>Módulos principales:</h4>
        <ul>
            <li><strong>Clientes:</strong> Registro y gestión de datos personales.</li>
            <li><strong>Cuentas:</strong> Control de cuentas de ahorro o corriente.</li>
            <li><strong>Transacciones:</strong> Depósitos, retiros y transferencias.</li>
            <li><strong>Préstamos:</strong> Administración de préstamos y su estado.</li>
            <li><strong>Tarjetas de crédito:</strong> Emisión, tipo y límite de crédito.</li>
            <li><strong>Sucursales:</strong> Ubicación y gestión de oficinas bancarias.</li>
            <li><strong>Empleados:</strong> Gestión del personal bancario.</li>
        </ul>
    </div>

    <a href="login.php" class="btn btn-primary btn-lg btn-login">Ingresar al Sistema</a>
</div>

</body>
</html>
