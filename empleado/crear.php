<?php
include_once __DIR__ . '/../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $puesto = $_POST['puesto'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $id_sucursal = $_POST['id_sucursal'];

    $sql = "INSERT INTO empleado (nombre, puesto, telefono, email, id_sucursal) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nombre, $puesto, $telefono, $email, $id_sucursal);

    if ($stmt->execute()) {
        echo "Empleado creado exitosamente.";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
}
$conn->close();
