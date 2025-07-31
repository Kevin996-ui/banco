<?php
include '../conexion.php';

$id = $_POST['id_cliente'];
$sql = "DELETE FROM CLIENTE WHERE id_cliente=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Cliente eliminado correctamente.";
} else {
    echo "Error al eliminar cliente: " . $stmt->error;
}
$conn->close();
