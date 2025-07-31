<?php
header("Content-Type: application/json");
include_once __DIR__ . '/../conexion.php';

// Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    echo json_encode(["error" => "Método no permitido."]);
    exit;
}

// Capturar datos del cuerpo PUT
parse_str(file_get_contents("php://input"), $_PUT);

// Asignar variables
$id_cuenta = $_PUT['id_cuenta'] ?? '';
$numero_cuenta = $_PUT['numero_cuenta'] ?? '';
$tipo = $_PUT['tipo'] ?? '';
$saldo = $_PUT['saldo'] ?? 0.00;
$fecha_apertura = $_PUT['fecha_apertura'] ?? date('Y-m-d');
$id_cliente = $_PUT['id_cliente'] ?? '';
$id_sucursal = $_PUT['id_sucursal'] ?? '';

// Validación básica
if ($id_cuenta && $numero_cuenta && $tipo && $id_cliente && $id_sucursal) {
    $sql = "UPDATE cuenta 
            SET numero_cuenta=?, tipo=?, saldo=?, fecha_apertura=?, id_cliente=?, id_sucursal=? 
            WHERE id_cuenta=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdssii", $numero_cuenta, $tipo, $saldo, $fecha_apertura, $id_cliente, $id_sucursal, $id_cuenta);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Cuenta actualizada correctamente."]);
    } else {
        echo json_encode(["error" => "Error al actualizar: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Todos los campos requeridos deben ser enviados."]);
}

$conn->close();
