<?php
session_start();
$rol = $_SESSION['perfil'] ?? 'Invitado';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Sucursales</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4 bg-light">
    <div class="container">
        <h2 class="mb-4">Gestión de Sucursales</h2>

        <!-- Formulario solo para Admin y Dev -->
        <?php if ($rol === "Administrador" || $rol === "Desarrollador"): ?>
            <form id="form-sucursal" class="row g-3 mb-4">
                <input type="hidden" id="id_sucursal" name="id_sucursal">

                <div class="col-md-4">
                    <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre" required>
                </div>
                <div class="col-md-4">
                    <input type="text" id="direccion" name="direccion" class="form-control" placeholder="Dirección">
                </div>
                <div class="col-md-3">
                    <input type="text" id="telefono" name="telefono" class="form-control" placeholder="Teléfono">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Guardar</button>
                </div>
            </form>
        <?php endif; ?>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <?php if ($rol !== "Supervisor"): ?>
                        <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody id="tabla-sucursales"></tbody>
        </table>
    </div>

    <script>
        const tabla = document.getElementById("tabla-sucursales");
        const rolUsuario = "<?= $rol ?>";
        const form = document.getElementById("form-sucursal");

        function cargarSucursales() {
            fetch("listar.php")
                .then(res => res.json())
                .then(data => {
                    tabla.innerHTML = "";
                    data.forEach(s => {
                        let fila = `
                            <tr>
                                <td>${s.id_sucursal}</td>
                                <td>${s.nombre}</td>
                                <td>${s.direccion || ''}</td>
                                <td>${s.telefono || ''}</td>`;

                        if (rolUsuario !== "Supervisor") {
                            fila += `
                                <td>
                                    <button class="btn btn-sm btn-warning" onclick="editarSucursal(${s.id_sucursal}, '${s.nombre}', '${s.direccion}', '${s.telefono}')">Editar</button>
                                    <button class="btn btn-sm btn-danger" onclick="eliminarSucursal(${s.id_sucursal})">Eliminar</button>
                                </td>`;
                        }

                        fila += `</tr>`;
                        tabla.innerHTML += fila;
                    });
                });
        }

        cargarSucursales();

        if (rolUsuario === "Administrador" || rolUsuario === "Desarrollador") {
            form?.addEventListener("submit", function(e) {
                e.preventDefault();
                const id = document.getElementById("id_sucursal").value;
                const datos = new FormData(form);

                if (id === "") {
                    // Crear
                    fetch("crear.php", {
                        method: "POST",
                        body: datos
                    }).then(() => {
                        form.reset();
                        cargarSucursales();
                    });
                } else {
                    // Actualizar
                    const data = new URLSearchParams();
                    datos.forEach((value, key) => data.append(key, value));
                    fetch("actualizar.php", {
                        method: "PUT",
                        body: data
                    }).then(() => {
                        form.reset();
                        cargarSucursales();
                    });
                }
            });
        }

        function editarSucursal(id, nombre, direccion, telefono) {
            document.getElementById("id_sucursal").value = id;
            document.getElementById("nombre").value = nombre;
            document.getElementById("direccion").value = direccion;
            document.getElementById("telefono").value = telefono;
        }

        function eliminarSucursal(id) {
            if (rolUsuario === "Administrador" || rolUsuario === "Desarrollador") {
                if (confirm("¿Estás seguro de eliminar esta sucursal?")) {
                    const data = new URLSearchParams();
                    data.append("id_sucursal", id);

                    fetch("eliminar.php", {
                        method: "DELETE",
                        body: data
                    }).then(() => {
                        cargarSucursales();
                    });
                }
            }
        }
    </script>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>