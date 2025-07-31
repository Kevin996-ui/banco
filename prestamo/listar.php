<?php
include_once __DIR__ . '/../conexion.php';
header('Content-Type: application/json');

$sql = "
    SELECT 
        prestamo.*, 
        cliente.nombre AS nombre_cliente, 
        sucursal.nombre AS nombre_sucursal
    FROM prestamo
    INNER JOIN cliente ON prestamo.id_cliente = cliente.id_cliente
    INNER JOIN sucursal ON prestamo.id_sucursal = sucursal.id_sucursal
    ORDER BY prestamo.id_prestamo DESC
";

$result = $conn->query($sql);
$prestamos = [];
while ($row = $result->fetch_assoc()) {
    $prestamos[] = $row;
}

echo json_encode($prestamos, JSON_UNESCAPED_UNICODE);
$conn->close();
