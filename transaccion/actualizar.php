<?php
include_once __DIR__ . '/../conexion.php';
header("Content-Type: application/json");

parse_str(file_get_contents("php://input"), $_PUT);

$id = $_PUT['id_transaccion'] ?? '';
$monto = $_PUT['monto'] ?? '';
$tipo = $_PUT['tipo'] ?? '';
$descripcion = $_PUT['descripcion'] ?? '';
$id_cuenta = $_PUT['id_cuenta'] ?? '';

if ($id !== '') {
    $sql = "UPDATE transaccion SET monto=?, tipo=?, descripcion=?, id_cuenta=? WHERE id_transaccion=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dssii", $monto, $tipo, $descripcion, $id_cuenta, $id);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Transacción actualizada correctamente."]);
    } else {
        echo json_encode(["error" => "Error al actualizar: " . $conn->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["error" => "ID no proporcionado."]);
}
$conn->close();
