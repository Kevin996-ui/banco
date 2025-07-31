<?php
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json");
include_once __DIR__ . '/../conexion.php';

// Validar método PUT real
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Obtener datos crudos del cuerpo
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id_cliente'])) {
        $id = $data['id_cliente'];
        $nombre = $data['nombre'] ?? '';
        $direccion = $data['direccion'] ?? '';
        $telefono = $data['telefono'] ?? '';
        $email = $data['email'] ?? '';

        $sql = "UPDATE cliente SET nombre=?, direccion=?, telefono=?, email=? WHERE id_cliente=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $nombre, $direccion, $telefono, $email, $id);

        if ($stmt->execute()) {
            echo json_encode(["mensaje" => "Cliente actualizado correctamente."]);
        } else {
            echo json_encode(["error" => "Error al actualizar: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "ID del cliente no proporcionado."]);
    }
} else {
    echo json_encode(["error" => "Método no permitido. Se esperaba PUT."]);
}

$conn->close();
