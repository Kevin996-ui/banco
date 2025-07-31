<?php
include_once __DIR__ . '/../conexion.php';

parse_str(file_get_contents("php://input"), $_DELETE);

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_DELETE['id_transaccion'])) {
    $id = $_DELETE['id_transaccion'];

    $sql = "DELETE FROM transaccion WHERE id_transaccion=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Transacción eliminada correctamente.";
    } else {
        echo "Error al eliminar: " . $conn->error;
    }
    $stmt->close();
}
$conn->close();
