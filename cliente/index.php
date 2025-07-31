<?php
session_start();
$rol = $_SESSION['perfil'] ?? 'Invitado';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4 bg-light">
    <div class="container">

        <h2 class="mb-4">Gestión de Clientes</h2>

        <!-- Formulario solo para Administrador y Desarrollador -->
        <?php if ($rol === "Administrador" || $rol === "Desarrollador"): ?>
            <form id="form-cliente" class="row g-3 mb-4">
                <input type="hidden" id="id_cliente" name="id_cliente">

                <div class="col-md-3">
                    <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre" required>
                </div>
                <div class="col-md-3">
                    <input type="text" id="direccion" name="direccion" class="form-control" placeholder="Dirección">
                </div>
                <div class="col-md-2">
                    <input type="text" id="telefono" name="telefono" class="form-control" placeholder="Teléfono">
                </div>
                <div class="col-md-3">
                    <input type="email" id="email" name="email" class="form-control" placeholder="Correo">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Guardar</button>
                </div>
            </form>
        <?php endif; ?>

        <!-- Tabla de clientes -->
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <?php if ($rol === "Administrador" || $rol === "Desarrollador"): ?>
                        <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody id="tabla-clientes">
                <!-- Datos dinámicos -->
            </tbody>
        </table>
    </div>

    <script>
        const form = document.getElementById("form-cliente");
        const tabla = document.getElementById("tabla-clientes");
        const rolUsuario = "<?= $rol ?>";

        function cargarClientes() {
            fetch("listar.php")
                .then(res => res.json())
                .then(data => {
                    tabla.innerHTML = "";
                    data.forEach(c => {
                        let fila = `
                            <tr>
                                <td>${c.id_cliente}</td>
                                <td>${c.nombre}</td>
                                <td>${c.direccion || ''}</td>
                                <td>${c.telefono || ''}</td>
                                <td>${c.email || ''}</td>
                        `;

                        if (rolUsuario === "Administrador" || rolUsuario === "Desarrollador") {
                            fila += `
                                <td>
                                    <button class="btn btn-sm btn-warning" onclick="editarCliente(${c.id_cliente}, '${c.nombre}', '${c.direccion}', '${c.telefono}', '${c.email}')">Editar</button>
                                    <button class="btn btn-sm btn-danger" onclick="eliminarCliente(${c.id_cliente})">Eliminar</button>
                                </td>`;
                        }

                        fila += `</tr>`;
                        tabla.innerHTML += fila;
                    });
                });
        }

        cargarClientes();

        // Solo Admin y Dev pueden guardar
        if (rolUsuario === "Administrador" || rolUsuario === "Desarrollador") {
            form.addEventListener("submit", function(e) {
                e.preventDefault();
                const id = document.getElementById("id_cliente").value;
                const nombre = document.getElementById("nombre").value;
                const direccion = document.getElementById("direccion").value;
                const telefono = document.getElementById("telefono").value;
                const email = document.getElementById("email").value;

                if (id === "") {
                    fetch("crear.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            nombre,
                            direccion,
                            telefono,
                            email
                        })
                    }).then(() => {
                        form.reset();
                        cargarClientes();
                    });
                } else {
                    fetch("actualizar.php", {
                        method: "PUT",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            id_cliente: id,
                            nombre,
                            direccion,
                            telefono,
                            email
                        })
                    }).then(() => {
                        form.reset();
                        cargarClientes();
                    });
                }
            });
        }

        function editarCliente(id, nombre, direccion, telefono, email) {
            document.getElementById("id_cliente").value = id;
            document.getElementById("nombre").value = nombre;
            document.getElementById("direccion").value = direccion;
            document.getElementById("telefono").value = telefono;
            document.getElementById("email").value = email;
        }

        function eliminarCliente(id) {
            if (rolUsuario === "Administrador" || rolUsuario === "Desarrollador") {
                if (confirm("¿Estás seguro de eliminar este cliente?")) {
                    fetch("eliminar.php", {
                        method: "DELETE",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            id_cliente: id
                        })
                    }).then(() => {
                        cargarClientes();
                    });
                }
            }
        }
    </script>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>