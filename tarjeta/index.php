<?php
session_start();
$rol = $_SESSION['perfil'] ?? 'Invitado';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Tarjetas de Crédito</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4 bg-light">
    <div class="container">
        <h2 class="mb-4">Gestión de Tarjetas de Crédito</h2>

        <!-- Formulario solo para Admin y Dev -->
        <?php if ($rol === "Administrador" || $rol === "Desarrollador"): ?>
            <form id="form-tarjeta" class="row g-3 mb-4">
                <input type="hidden" id="id_tarjeta" name="id_tarjeta">

                <div class="col-md-3">
                    <input type="text" id="numero_tarjeta" name="numero_tarjeta" class="form-control" placeholder="Número de tarjeta" required>
                </div>
                <div class="col-md-2">
                    <select id="tipo" name="tipo" class="form-select" required>
                        <option value="">Tipo</option>
                        <option value="Visa">Visa</option>
                        <option value="MasterCard">MasterCard</option>
                        <option value="AmericanExpress">American Express</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" id="limite_credito" name="limite_credito" class="form-control" placeholder="Límite" required>
                </div>
                <div class="col-md-3">
                    <select id="id_cuenta" name="id_cuenta" class="form-select" required>
                        <option value="">Seleccione cuenta</option>
                    </select>
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
                    <th>Número</th>
                    <th>Tipo</th>
                    <th>Límite</th>
                    <th>Fecha Emisión</th>
                    <th># Cuenta</th>
                    <th>Cliente</th>
                    <?php if ($rol !== "Supervisor"): ?>
                        <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody id="tabla-tarjetas"></tbody>
        </table>
    </div>

    <script>
        const form = document.getElementById("form-tarjeta");
        const tabla = document.getElementById("tabla-tarjetas");
        const selectCuenta = document.getElementById("id_cuenta");
        const rolUsuario = "<?= $rol ?>";

        function cargarCuentas() {
            fetch("../cuenta/listar.php")
                .then(res => res.json())
                .then(data => {
                    selectCuenta.innerHTML = '<option value="">Seleccione cuenta</option>';
                    data.forEach(c => {
                        selectCuenta.innerHTML += `<option value="${c.id_cuenta}">${c.numero_cuenta}</option>`;
                    });
                });
        }

        function cargarTarjetas() {
            fetch("listar.php")
                .then(res => res.json())
                .then(data => {
                    tabla.innerHTML = "";
                    data.forEach(t => {
                        let fila = `
                            <tr>
                                <td>${t.id_tarjeta}</td>
                                <td>${t.numero_tarjeta}</td>
                                <td>${t.tipo}</td>
                                <td>${t.limite_credito}</td>
                                <td>${t.fecha_emision}</td>
                                <td>${t.numero_cuenta}</td>
                                <td>${t.nombre_cliente}</td>`;

                        if (rolUsuario !== "Supervisor") {
                            fila += `
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editarTarjeta(${t.id_tarjeta}, '${t.numero_tarjeta}', '${t.tipo}', ${t.limite_credito}, '${t.fecha_emision}', ${t.id_cuenta})">Editar</button>
                                    <button class="btn btn-danger btn-sm" onclick="eliminarTarjeta(${t.id_tarjeta})">Eliminar</button>
                                </td>`;
                        }

                        fila += `</tr>`;
                        tabla.innerHTML += fila;
                    });
                });
        }

        if (rolUsuario === "Administrador" || rolUsuario === "Desarrollador") {
            form?.addEventListener("submit", function(e) {
                e.preventDefault();
                const id = document.getElementById("id_tarjeta").value;
                const datos = new FormData(form);

                if (id === "") {
                    fetch("crear.php", {
                        method: "POST",
                        body: datos
                    }).then(() => {
                        form.reset();
                        cargarTarjetas();
                    });
                } else {
                    const data = new URLSearchParams();
                    datos.forEach((value, key) => data.append(key, value));
                    fetch("actualizar.php", {
                        method: "POST",
                        body: data
                    }).then(() => {
                        form.reset();
                        cargarTarjetas();
                    });
                }
            });
        }

        function editarTarjeta(id, numero, tipo, limite, fecha, cuenta) {
            document.getElementById("id_tarjeta").value = id;
            document.getElementById("numero_tarjeta").value = numero;
            document.getElementById("tipo").value = tipo;
            document.getElementById("limite_credito").value = limite;
            document.getElementById("id_cuenta").value = cuenta;
        }

        function eliminarTarjeta(id) {
            if (rolUsuario === "Administrador" || rolUsuario === "Desarrollador") {
                if (confirm("¿Deseas eliminar esta tarjeta de crédito?")) {
                    const data = new URLSearchParams();
                    data.append("id_tarjeta", id);

                    fetch("eliminar.php", {
                        method: "POST",
                        body: data
                    }).then(() => {
                        cargarTarjetas();
                    });
                }
            }
        }

        cargarCuentas();
        cargarTarjetas();
    </script>
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>