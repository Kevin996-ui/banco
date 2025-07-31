<?php
include_once __DIR__ . '/../conexion.php';

header('Content-Type: application/json');

$sql = "SELECT e.*, s.nombre AS nombre_sucursal 
        FROM empleado e
        JOIN sucursal s ON e.id_sucursal = s.id_sucursal";

$result = $conn->query($sql);
$empleados = [];

while ($row = $result->fetch_assoc()) {
    $empleados[] = $row;
}

echo json_encode($empleados);
$conn->close();
