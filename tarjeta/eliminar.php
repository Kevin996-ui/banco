<?php
include_once __DIR__ . '/../conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_tarjeta'] ?? '';
    if ($id) {
        $sql = "DELETE FROM tarjeta_credito WHERE id_tarjeta=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(["mensaje" => "Tarjeta eliminada"]);
        $stmt->close();
    } else {
        echo json_encode(["error" => "ID no proporcionado"]);
    }
}

$conn->close();
