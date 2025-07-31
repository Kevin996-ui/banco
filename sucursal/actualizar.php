<?php
include_once __DIR__ . '/../conexion.php';

parse_str(file_get_contents("php://input"), $_PUT);

$id = $_PUT['id_sucursal'] ?? '';
$nombre = $_PUT['nombre'] ?? '';
$direccion = $_PUT['direccion'] ?? '';
$telefono = $_PUT['telefono'] ?? '';

if ($id !== '') {
    $sql = "UPDATE sucursal SET nombre=?, direccion=?, telefono=? WHERE id_sucursal=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nombre, $direccion, $telefono, $id);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Sucursal actualizada correctamente"]);
    } else {
        echo json_encode(["error" => "Error al actualizar: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "ID de sucursal no proporcionado"]);
}

$conn->close();
