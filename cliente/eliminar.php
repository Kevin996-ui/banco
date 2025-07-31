<?php
header("Access-Control-Allow-Methods: DELETE");
header("Content-Type: application/json");
include_once __DIR__ . '/../conexion.php';

// Validar método DELETE real
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id_cliente'])) {
        $id = $data['id_cliente'];

        $sql = "DELETE FROM cliente WHERE id_cliente=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["mensaje" => "Cliente eliminado correctamente."]);
        } else {
            echo json_encode(["error" => "Error al eliminar: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "ID del cliente no proporcionado."]);
    }
} else {
    echo json_encode(["error" => "Método no permitido. Se esperaba DELETE."]);
}

$conn->close();
