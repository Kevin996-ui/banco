<?php
session_start();
$rol = $_SESSION['perfil'] ?? 'Invitado';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Cuentas</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light p-4">
    <div class="container">
        <h2 class="mb-4">Gestión de Cuentas Bancarias</h2>

        <!-- Formulario solo para Admin y Dev -->
        <?php if ($rol === "Administrador" || $rol === "Desarrollador"): ?>
            <form id="form-cuenta" class="row g-3 mb-4">
                <input type="hidden" id="id_cuenta" name="id_cuenta">
                <div class="col-md-3">
                    <input type="text" id="numero_cuenta" name="numero_cuenta" class="form-control" placeholder="Número de cuenta" required>
                </div>
                <div class="col-md-2">
                    <select id="tipo" name="tipo" class="form-select" required>
                        <option value="">Tipo</option>
                        <option value="Ahorro">Ahorro</option>
                        <option value="Corriente">Corriente</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" id="saldo" name="saldo" class="form-control" placeholder="Saldo">
                </div>
                <div class="col-md-2">
                    <select id="id_cliente" name="id_cliente" class="form-select" required></select>
                </div>
                <div class="col-md-2">
                    <select id="id_sucursal" name="id_sucursal" class="form-select" required></select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Guardar</button>
                </div>
            </form>
        <?php endif; ?>

        <!-- Tabla -->
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>N° Cuenta</th>
                    <th>Tipo</th>
                    <th>Saldo</th>
                    <th>Cliente</th>
                    <th>Sucursal</th>
                    <?php if ($rol !== "Supervisor"): ?>
                        <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody id="tabla-cuentas"></tbody>
        </table>
    </div>

    <script>
        const form = document.getElementById("form-cuenta");
        const tabla = document.getElementById("tabla-cuentas");
        const rolUsuario = "<?= $rol ?>";

        // Cargar clientes
        fetch("../cliente/listar.php")
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById("id_cliente");
                if (select) {
                    select.innerHTML = '<option value="">Cliente</option>';
                    data.forEach(cli => {
                        select.innerHTML += `<option value="${cli.id_cliente}">${cli.nombre}</option>`;
                    });
                }
            });

        // Cargar sucursales
        fetch("../sucursal/listar.php")
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById("id_sucursal");
                if (select) {
                    select.innerHTML = '<option value="">Sucursal</option>';
                    data.forEach(suc => {
                        select.innerHTML += `<option value="${suc.id_sucursal}">${suc.nombre}</option>`;
                    });
                }
            });

        function cargarCuentas() {
            fetch("listar.php")
                .then(res => res.json())
                .then(data => {
                    tabla.innerHTML = "";
                    data.forEach(c => {
                        let fila = `
                        <tr>
                            <td>${c.id_cuenta}</td>
                            <td>${c.numero_cuenta}</td>
                            <td>${c.tipo}</td>
                            <td>$${parseFloat(c.saldo).toFixed(2)}</td>
                            <td>${c.cliente_nombre}</td>
                            <td>${c.sucursal_nombre}</td>`;

                        if (rolUsuario !== "Supervisor") {
                            fila += `<td>
                            <button class="btn btn-sm btn-warning" onclick="editarCuenta(${c.id_cuenta}, '${c.numero_cuenta}', '${c.tipo}', '${c.saldo}', '${c.id_cliente}', '${c.id_sucursal}')">Editar</button>
                            <button class="btn btn-sm btn-danger" onclick="eliminarCuenta(${c.id_cuenta})">Eliminar</button>
                        </td>`;
                        }

                        fila += `</tr>`;
                        tabla.innerHTML += fila;
                    });
                });
        }

        cargarCuentas();

        // Guardar cuenta solo si es Admin o Dev
        if (rolUsuario === "Administrador" || rolUsuario === "Desarrollador") {
            form?.addEventListener("submit", function(e) {
                e.preventDefault();
                const id = document.getElementById("id_cuenta").value;
                const datos = new FormData(form);

                if (id === "") {
                    fetch("crear.php", {
                        method: "POST",
                        body: datos
                    }).then(() => {
                        form.reset();
                        cargarCuentas();
                    });
                } else {
                    const data = new URLSearchParams();
                    datos.forEach((value, key) => data.append(key, value));
                    fetch("actualizar.php", {
                        method: "PUT",
                        body: data
                    }).then(() => {
                        form.reset();
                        cargarCuentas();
                    });
                }
            });
        }

        function editarCuenta(id, numero, tipo, saldo, id_cliente, id_sucursal) {
            document.getElementById("id_cuenta").value = id;
            document.getElementById("numero_cuenta").value = numero;
            document.getElementById("tipo").value = tipo;
            document.getElementById("saldo").value = saldo;
            document.getElementById("id_cliente").value = id_cliente;
            document.getElementById("id_sucursal").value = id_sucursal;
        }

        function eliminarCuenta(id) {
            if (rolUsuario === "Administrador" || rolUsuario === "Desarrollador") {
                if (confirm("¿Estás seguro de eliminar esta cuenta?")) {
                    const data = new URLSearchParams();
                    data.append("id_cuenta", id);
                    fetch("eliminar.php", {
                        method: "DELETE",
                        body: data
                    }).then(() => cargarCuentas());
                }
            }
        }
    </script>
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>