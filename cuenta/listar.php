<?php
include_once __DIR__ . '/../conexion.php';

header('Content-Type: application/json');

$sql = "
    SELECT 
        cuenta.*, 
        cliente.nombre AS cliente_nombre, 
        sucursal.nombre AS sucursal_nombre
    FROM cuenta
    INNER JOIN cliente ON cuenta.id_cliente = cliente.id_cliente
    INNER JOIN sucursal ON cuenta.id_sucursal = sucursal.id_sucursal
    ORDER BY cuenta.id_cuenta DESC
";

$result = $conn->query($sql);

$cuentas = [];
while ($row = $result->fetch_assoc()) {
    $cuentas[] = $row;
}

echo json_encode($cuentas, JSON_UNESCAPED_UNICODE);
$conn->close();
