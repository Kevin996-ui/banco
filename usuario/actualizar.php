<?php
include '../conexion.php';

$id = $_POST['id_cliente'];
$nombre = $_POST['nombre'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];

$sql = "UPDATE CLIENTE SET nombre=?, direccion=?, telefono=?, email=? WHERE id_cliente=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $nombre, $direccion, $telefono, $email, $id);

if ($stmt->execute()) {
    echo "Cliente actualizado correctamente.";
} else {
    echo "Error al actualizar cliente: " . $stmt->error;
}
$conn->close();
