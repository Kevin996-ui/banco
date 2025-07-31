<?php
session_start();
$rol = $_SESSION['perfil'] ?? 'Invitado';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Préstamos</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4 bg-light">
    <div class="container">
        <h2 class="mb-4">Gestión de Préstamos</h2>

        <!-- Formulario solo para Admin y Dev -->
        <?php if ($rol === "Administrador" || $rol === "Desarrollador"): ?>
            <form id="form-prestamo" class="row g-3 mb-4">
                <input type="hidden" id="id_prestamo" name="id_prestamo">

                <div class="col-md-2">
                    <input type="number" step="0.01" id="monto" name="monto" class="form-control" placeholder="Monto" required>
                </div>

                <div class="col-md-2">
                    <input type="number" step="0.01" id="interes" name="interes" class="form-control" placeholder="Interés (%)" required>
                </div>

                <div class="col-md-2">
                    <input type="number" id="plazo" name="plazo" class="form-control" placeholder="Plazo (meses)" required>
                </div>

                <div class="col-md-2">
                    <select id="estado" name="estado" class="form-select" required>
                        <option value="">Estado</option>
                        <option value="Activo">Activo</option>
                        <option value="Cancelado">Cancelado</option>
                        <option value="En mora">En mora</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select id="id_cliente" name="id_cliente" class="form-select" required>
                        <option value="">Seleccione Cliente</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select id="id_sucursal" name="id_sucursal" class="form-select" required>
                        <option value="">Seleccione Sucursal</option>
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
                    <th>Fecha Inicio</th>
                    <th>Monto</th>
                    <th>Interés</th>
                    <th>Plazo</th>
                    <th>Estado</th>
                    <th>Cliente</th>
                    <th>Sucursal</th>
                    <?php if ($rol !== "Supervisor"): ?>
                        <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody id="tabla-prestamos"></tbody>
        </table>
    </div>

    <script>
        const form = document.getElementById("form-prestamo");
        const tabla = document.getElementById("tabla-prestamos");
        const selectCliente = document.getElementById("id_cliente");
        const selectSucursal = document.getElementById("id_sucursal");
        const rolUsuario = "<?= $rol ?>";

        function cargarClientes() {
            fetch("../cliente/listar.php")
                .then(res => res.json())
                .then(data => {
                    selectCliente.innerHTML = '<option value="">Seleccione Cliente</option>';
                    data.forEach(c => {
                        selectCliente.innerHTML += `<option value="${c.id_cliente}">${c.nombre}</option>`;
                    });
                });
        }

        function cargarSucursales() {
            fetch("../sucursal/listar.php")
                .then(res => res.json())
                .then(data => {
                    selectSucursal.innerHTML = '<option value="">Seleccione Sucursal</option>';
                    data.forEach(s => {
                        selectSucursal.innerHTML += `<option value="${s.id_sucursal}">${s.nombre}</option>`;
                    });
                });
        }

        function cargarPrestamos() {
            fetch("listar.php")
                .then(res => res.json())
                .then(data => {
                    tabla.innerHTML = "";
                    data.forEach(p => {
                        let fila = `
                            <tr>
                                <td>${p.id_prestamo}</td>
                                <td>${p.fecha_inicio}</td>
                                <td>${p.monto}</td>
                                <td>${p.interes}%</td>
                                <td>${p.plazo} meses</td>
                                <td>${p.estado}</td>
                                <td>${p.nombre_cliente}</td>
                                <td>${p.nombre_sucursal}</td>`;

                        if (rolUsuario !== "Supervisor") {
                            fila += `<td>
                                <button class="btn btn-warning btn-sm" onclick="editarPrestamo(${p.id_prestamo}, ${p.monto}, ${p.interes}, ${p.plazo}, '${p.estado}', ${p.id_cliente}, ${p.id_sucursal})">Editar</button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarPrestamo(${p.id_prestamo})">Eliminar</button>
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
                const id = document.getElementById("id_prestamo").value;
                const datos = new FormData(form);

                if (id === "") {
                    fetch("crear.php", {
                        method: "POST",
                        body: datos
                    }).then(() => {
                        form.reset();
                        cargarPrestamos();
                    });
                } else {
                    const data = new URLSearchParams();
                    datos.forEach((value, key) => data.append(key, value));
                    fetch("actualizar.php", {
                        method: "POST",
                        body: data
                    }).then(() => {
                        form.reset();
                        cargarPrestamos();
                    });
                }
            });
        }

        function editarPrestamo(id, monto, interes, plazo, estado, cliente, sucursal) {
            document.getElementById("id_prestamo").value = id;
            document.getElementById("monto").value = monto;
            document.getElementById("interes").value = interes;
            document.getElementById("plazo").value = plazo;
            document.getElementById("estado").value = estado;
            document.getElementById("id_cliente").value = cliente;
            document.getElementById("id_sucursal").value = sucursal;
        }

        function eliminarPrestamo(id) {
            if (rolUsuario === "Administrador" || rolUsuario === "Desarrollador") {
                if (confirm("¿Deseas eliminar este préstamo?")) {
                    const data = new URLSearchParams();
                    data.append("id_prestamo", id);

                    fetch("eliminar.php", {
                        method: "POST",
                        body: data
                    }).then(() => {
                        cargarPrestamos();
                    });
                }
            }
        }

        cargarClientes();
        cargarSucursales();
        cargarPrestamos();
    </script>
    
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>