<?php
include '../conexion.php';

$sql = "SELECT * FROM CLIENTE";
$result = $conn->query($sql);

$clientes = array();
while ($row = $result->fetch_assoc()) {
    $clientes[] = $row;
}

echo json_encode($clientes);
$conn->close();
