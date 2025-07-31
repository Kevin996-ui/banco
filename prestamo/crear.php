<?php
include_once __DIR__ . '/../conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método no permitido.']);
    exit;
}

$monto = $_POST['monto'] ?? 0;
$interes = $_POST['interes'] ?? 0;
$plazo = $_POST['plazo'] ?? 0;
$fecha_inicio = $_POST['fecha_inicio'] ?? date('Y-m-d');
$estado = $_POST['estado'] ?? 'Activo';
$id_cliente = $_POST['id_cliente'] ?? '';
$id_sucursal = $_POST['id_sucursal'] ?? '';

if ($monto && $interes && $plazo && $id_cliente && $id_sucursal) {
    $stmt = $conn->prepare("INSERT INTO prestamo (monto, interes, plazo, fecha_inicio, estado, id_cliente, id_sucursal) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ddissii", $monto, $interes, $plazo, $fecha_inicio, $estado, $id_cliente, $id_sucursal);

    if ($stmt->execute()) {
        echo json_encode(['mensaje' => 'Préstamo creado correctamente.']);
    } else {
        echo json_encode(['error' => 'Error al crear el préstamo.']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Todos los campos son obligatorios.']);
}
$conn->close();
