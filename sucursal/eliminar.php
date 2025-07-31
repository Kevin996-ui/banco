<?php
include_once __DIR__ . '/../conexion.php';

parse_str(file_get_contents("php://input"), $_DELETE);

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_DELETE['id_sucursal'])) {
    $id = $_DELETE['id_sucursal'];

    $sql = "DELETE FROM sucursal WHERE id_sucursal=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Sucursal eliminada correctamente"]);
    } else {
        echo json_encode(["error" => "Error al eliminar: " . $conn->error]);
    }

    $stmt->close();
}

$conn->close();
