<?php
include_once __DIR__ . '/../conexion.php';
parse_str(file_get_contents("php://input"), $_DELETE);

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_DELETE['id_empleado'])) {
    $id = $_DELETE['id_empleado'];

    $sql = "DELETE FROM empleado WHERE id_empleado=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Empleado eliminado correctamente.";
    } else {
        echo "Error al eliminar: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Método no permitido o ID no recibido.";
}
$conn->close();
