<?php
include_once __DIR__ . '/../conexion.php';

$sql = "SELECT * FROM sucursal";
$result = $conn->query($sql);

$sucursales = [];

while ($row = $result->fetch_assoc()) {
    $sucursales[] = $row;
}

header('Content-Type: application/json');
echo json_encode($sucursales);

$conn->close();
