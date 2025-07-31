<?php
header("Content-Type: application/json");
include_once __DIR__ . '/../conexion.php';

// Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Método no permitido."]);
    exit;
}

// Recibir datos del formulario
$numero_cuenta = $_POST['numero_cuenta'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$saldo = $_POST['saldo'] ?? 0.00;
$fecha_apertura = $_POST['fecha_apertura'] ?? date('Y-m-d');
$id_cliente = $_POST['id_cliente'] ?? '';
$id_sucursal = $_POST['id_sucursal'] ?? '';

// Validación mínima
if ($numero_cuenta && $tipo && $id_cliente && $id_sucursal) {
    $sql = "INSERT INTO cuenta (numero_cuenta, tipo, saldo, fecha_apertura, id_cliente, id_sucursal)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdssi", $numero_cuenta, $tipo, $saldo, $fecha_apertura, $id_cliente, $id_sucursal);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Cuenta creada correctamente."]);
    } else {
        echo json_encode(["error" => "Error al crear la cuenta: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Todos los campos obligatorios deben ser proporcionados."]);
}

$conn->close();
