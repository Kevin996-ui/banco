<?php
session_start();
$rol = $_SESSION['perfil'] ?? 'Invitado';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Empleados</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4 bg-light">
    <div class="container">
        <h2 class="mb-4">Gestión de Empleados</h2>

        <!-- Formulario solo para Admin y Dev -->
        <?php if ($rol === "Administrador" || $rol === "Desarrollador"): ?>
            <form id="form-empleado" class="row g-3 mb-4">
                <input type="hidden" id="id_empleado" name="id_empleado">
                <div class="col-md-3">
                    <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre" required>
                </div>
                <div class="col-md-3">
                    <select id="puesto" name="puesto" class="form-select" required>
                        <option value="">Seleccione Puesto</option>
                        <option value="Cajero">Cajero</option>
                        <option value="Asesor">Asesor</option>
                        <option value="Gerente">Gerente</option>
                        <option value="Administrador">Administrador</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" id="telefono" name="telefono" class="form-control" placeholder="Teléfono">
                </div>
                <div class="col-md-3">
                    <input type="email" id="email" name="email" class="form-control" placeholder="Correo">
                </div>
                <div class="col-md-3">
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
                    <th>Nombre</th>
                    <th>Puesto</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Sucursal</th>
                    <?php if ($rol !== "Supervisor"): ?>
                        <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody id="tabla-empleados"></tbody>
        </table>
    </div>

    <script>
        const form = document.getElementById("form-empleado");
        const tabla = document.getElementById("tabla-empleados");
        const rolUsuario = "<?= $rol ?>";

        // Cargar sucursales
        function cargarSucursales() {
            fetch("../sucursal/listar.php")
                .then(res => res.json())
                .then(data => {
                    const select = document.getElementById("id_sucursal");
                    data.forEach(s => {
                        const option = document.createElement("option");
                        option.value = s.id_sucursal;
                        option.text = s.nombre;
                        select.appendChild(option);
                    });
                });
        }

        // Cargar empleados
        function cargarEmpleados() {
            fetch("listar.php")
                .then(res => res.json())
                .then(data => {
                    tabla.innerHTML = "";
                    data.forEach(e => {
                        let fila = `
                        <tr>
                            <td>${e.id_empleado}</td>
                            <td>${e.nombre}</td>
                            <td>${e.puesto}</td>
                            <td>${e.telefono || ''}</td>
                            <td>${e.email || ''}</td>
                            <td>${e.nombre_sucursal}</td>`;

                        if (rolUsuario !== "Supervisor") {
                            fila += `<td>
                                <button class="btn btn-warning btn-sm" onclick="editarEmpleado(${e.id_empleado}, '${e.nombre}', '${e.puesto}', '${e.telefono}', '${e.email}', ${e.id_sucursal})">Editar</button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarEmpleado(${e.id_empleado})">Eliminar</button>
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
                const id = document.getElementById("id_empleado").value;
                const datos = new FormData(form);

                if (id === "") {
                    // Crear
                    fetch("crear.php", {
                        method: "POST",
                        body: datos
                    }).then(() => {
                        form.reset();
                        cargarEmpleados();
                    });
                } else {
                    // Actualizar
                    const data = new URLSearchParams();
                    datos.forEach((value, key) => data.append(key, value));
                    fetch("actualizar.php", {
                        method: "POST",
                        body: data
                    }).then(() => {
                        form.reset();
                        cargarEmpleados();
                    });
                }
            });
        }

        // Editar
        function editarEmpleado(id, nombre, puesto, telefono, email, id_sucursal) {
            document.getElementById("id_empleado").value = id;
            document.getElementById("nombre").value = nombre;
            document.getElementById("puesto").value = puesto;
            document.getElementById("telefono").value = telefono;
            document.getElementById("email").value = email;
            document.getElementById("id_sucursal").value = id_sucursal;
        }

        // Eliminar solo para Admin o Dev
        function eliminarEmpleado(id) {
            if (rolUsuario === "Administrador" || rolUsuario === "Desarrollador") {
                if (confirm("¿Deseas eliminar este empleado?")) {
                    fetch("eliminar.php", {
                            method: "DELETE",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: `id_empleado=${encodeURIComponent(id)}`
                        })
                        .then(res => res.text())
                        .then(() => cargarEmpleados());
                }
            }
        }

        // Inicializar
        cargarSucursales();
        cargarEmpleados();
    </script>
</body>

</html>