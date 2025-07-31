<?php
include_once __DIR__ . '/../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $monto = $_POST['monto'];
    $tipo = $_POST['tipo'];
    $descripcion = $_POST['descripcion'] ?? '';
    $id_cuenta = $_POST['id_cuenta'];

    $sql = "INSERT INTO transaccion (monto, tipo, descripcion, id_cuenta) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dssi", $monto, $tipo, $descripcion, $id_cuenta);

    if ($stmt->execute()) {
        echo "Transacción registrada exitosamente.";
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
}
$conn->close();
