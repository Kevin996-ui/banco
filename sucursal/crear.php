<?php
include_once __DIR__ . '/../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';

    $sql = "INSERT INTO sucursal (nombre, direccion, telefono) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nombre, $direccion, $telefono);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Sucursal creada correctamente"]);
    } else {
        echo json_encode(["error" => "Error al crear la sucursal: " . $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
