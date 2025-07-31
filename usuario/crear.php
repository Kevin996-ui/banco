<?php
include '../conexion.php';

$nombre = $_POST['nombre'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];

$sql = "INSERT INTO CLIENTE (nombre, direccion, telefono, email) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $nombre, $direccion, $telefono, $email);

if ($stmt->execute()) {
    echo "Cliente agregado correctamente.";
} else {
    echo "Error al agregar cliente: " . $stmt->error;
}
$conn->close();
