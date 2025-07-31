<?php
include_once __DIR__ . '/../conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método no permitido.']);
    exit;
}

$id_prestamo = $_POST['id_prestamo'] ?? '';

if ($id_prestamo) {
    $stmt = $conn->prepare("DELETE FROM prestamo WHERE id_prestamo = ?");
    $stmt->bind_param("i", $id_prestamo);

    if ($stmt->execute()) {
        echo json_encode(['mensaje' => 'Préstamo eliminado correctamente.']);
    } else {
        echo json_encode(['error' => 'Error al eliminar el préstamo.']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'ID no proporcionado.']);
}
$conn->close();
