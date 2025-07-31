<?php
session_start();
$rol = $_SESSION['perfil'] ?? 'Invitado';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Transacciones</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4 bg-light">
    <div class="container">
        <h2 class="mb-4">Gestión de Transacciones</h2>

        <!-- Formulario solo para Admin y Dev -->
        <?php if ($rol === "Administrador" || $rol === "Desarrollador"): ?>
            <form id="form-transaccion" class="row g-3 mb-4">
                <input type="hidden" id="id_transaccion" name="id_transaccion">

                <div class="col-md-2">
                    <input type="number" step="0.01" id="monto" name="monto" class="form-control" placeholder="Monto" required>
                </div>

                <div class="col-md-2">
                    <select id="tipo" name="tipo" class="form-select" required>
                        <option value="">Tipo</option>
                        <option value="Depósito">Depósito</option>
                        <option value="Retiro">Retiro</option>
                        <option value="Transferencia">Transferencia</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <input type="text" id="descripcion" name="descripcion" class="form-control" placeholder="Descripción">
                </div>

                <div class="col-md-3">
                    <select id="id_cuenta" name="id_cuenta" class="form-select" required>
                        <option value="">Seleccione Cuenta</option>
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
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th># Cuenta</th>
                    <th>Cliente</th>
                    <?php if ($rol !== "Supervisor"): ?>
                        <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody id="tabla-transacciones">
                <!-- Aquí van las transacciones -->
            </tbody>
        </table>
    </div>

    <script>
        const form = document.getElementById("form-transaccion");
        const tabla = document.getElementById("tabla-transacciones");
        const selectCuenta = document.getElementById("id_cuenta");
        const rolUsuario = "<?= $rol ?>";

        // Cargar cuentas
        function cargarCuentas() {
            fetch("../cuenta/listar.php")
                .then(res => res.json())
                .then(data => {
                    selectCuenta.innerHTML = '<option value="">Seleccione Cuenta</option>';
                    data.forEach(c => {
                        selectCuenta.innerHTML += `<option value="${c.id_cuenta}">${c.numero_cuenta} - ${c.cliente_nombre}</option>`;
                    });
                });
        }

        // Cargar transacciones
        function cargarTransacciones() {
            fetch("listar.php")
                .then(res => res.json())
                .then(data => {
                    tabla.innerHTML = "";
                    data.forEach(t => {
                        let fila = `
                            <tr>
                                <td>${t.id_transaccion}</td>
                                <td>${t.fecha}</td>
                                <td>${t.monto}</td>
                                <td>${t.tipo}</td>
                                <td>${t.descripcion || ''}</td>
                                <td>${t.numero_cuenta}</td>
                                <td>${t.nombre_cliente}</td>`;

                        if (rolUsuario !== "Supervisor") {
                            fila += `<td>
                                <button class="btn btn-warning btn-sm" onclick="editarTransaccion(${t.id_transaccion}, '${t.monto}', '${t.tipo}', '${t.descripcion}', ${t.id_cuenta})">Editar</button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarTransaccion(${t.id_transaccion})">Eliminar</button>
                            </td>`;
                        }

                        fila += `</tr>`;
                        tabla.innerHTML += fila;
                    });
                });
        }

        // Enviar formulario solo para Admin o Dev
        if (rolUsuario === "Administrador" || rolUsuario === "Desarrollador") {
            form?.addEventListener("submit", function(e) {
                e.preventDefault();
                const id = document.getElementById("id_transaccion").value;
                const datos = new FormData(form);

                if (id === "") {
                    fetch("crear.php", {
                        method: "POST",
                        body: datos
                    }).then(() => {
                        form.reset();
                        cargarTransacciones();
                    });
                } else {
                    const data = new URLSearchParams();
                    datos.forEach((value, key) => data.append(key, value));
                    fetch("actualizar.php", {
                        method: "POST",
                        body: data
                    }).then(() => {
                        form.reset();
                        cargarTransacciones();
                    });
                }
            });
        }

        // Editar
        function editarTransaccion(id, monto, tipo, descripcion, cuenta) {
            document.getElementById("id_transaccion").value = id;
            document.getElementById("monto").value = monto;
            document.getElementById("tipo").value = tipo;
            document.getElementById("descripcion").value = descripcion;
            document.getElementById("id_cuenta").value = cuenta;
        }

        // Eliminar solo para Admin o Dev
        function eliminarTransaccion(id) {
            if (rolUsuario === "Administrador" || rolUsuario === "Desarrollador") {
                if (confirm("¿Deseas eliminar esta transacción?")) {
                    const data = new URLSearchParams();
                    data.append("id_transaccion", id);

                    fetch("eliminar.php", {
                        method: "POST",
                        body: data
                    }).then(() => {
                        cargarTransacciones();
                    });
                }
            }
        }

        // Inicializar
        cargarCuentas();
        cargarTransacciones();
    </script>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>