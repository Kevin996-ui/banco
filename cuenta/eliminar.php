<?php
header("Content-Type: application/json");
include_once __DIR__ . '/../conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    echo json_encode(["error" => "Método no permitido."]);
    exit;
}

// Obtener datos desde cuerpo de la solicitud
parse_str(file_get_contents("php://input"), $_DELETE);

$id_cuenta = $_DELETE['id_cuenta'] ?? '';

if ($id_cuenta) {
    $sql = "DELETE FROM cuenta WHERE id_cuenta=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_cuenta);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Cuenta eliminada correctamente."]);
    } else {
        echo json_encode(["error" => "Error al eliminar la cuenta: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "ID de cuenta no proporcionado."]);
}

$conn->close();
