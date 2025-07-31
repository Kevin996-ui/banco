<?php
include __DIR__ . '/../conexion.php';

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input = json_decode(file_get_contents("php://input"), true);

    $nombre = $input['nombre'] ?? '';
    $direccion = $input['direccion'] ?? '';
    $telefono = $input['telefono'] ?? '';
    $email = $input['email'] ?? '';

    $sql = "INSERT INTO CLIENTE (nombre, direccion, telefono, email) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nombre, $direccion, $telefono, $email);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Cliente creado exitosamente."]);
    } else {
        echo json_encode(["error" => "Error: " . $conn->error]);
    }

    $stmt->close();
}
$conn->close();
