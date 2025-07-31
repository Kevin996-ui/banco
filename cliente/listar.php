<?php
include_once __DIR__ . '/../conexion.php';

header('Content-Type: application/json');

$sql = "SELECT * FROM CLIENTE";
$result = $conn->query($sql);

$clientes = [];
while ($row = $result->fetch_assoc()) {
    $clientes[] = $row;
}

echo json_encode($clientes);

$conn->close();
