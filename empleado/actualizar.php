<?php
header("Content-Type: application/json");
include_once __DIR__ . '/../conexion.php';

parse_str(file_get_contents("php://input"), $_PUT);

$id = $_PUT['id_empleado'] ?? '';
$nombre = $_PUT['nombre'] ?? '';
$puesto = $_PUT['puesto'] ?? '';
$telefono = $_PUT['telefono'] ?? '';
$email = $_PUT['email'] ?? '';
$id_sucursal = $_PUT['id_sucursal'] ?? '';

if ($id !== '') {
    $sql = "UPDATE empleado SET nombre=?, puesto=?, telefono=?, email=?, id_sucursal=? WHERE id_empleado=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $nombre, $puesto, $telefono, $email, $id_sucursal, $id);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Empleado actualizado correctamente."]);
    } else {
        echo json_encode(["error" => "Error al actualizar: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "ID del empleado no proporcionado."]);
}
$conn->close();
