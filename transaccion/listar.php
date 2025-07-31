<?php
header("Content-Type: application/json");
include_once __DIR__ . '/../conexion.php';

$sql = "SELECT 
    transaccion.*, 
    cuenta.numero_cuenta, 
    cliente.nombre AS nombre_cliente
FROM transaccion
INNER JOIN cuenta ON transaccion.id_cuenta = cuenta.id_cuenta
INNER JOIN cliente ON cuenta.id_cliente = cliente.id_cliente
ORDER BY transaccion.id_transaccion DESC";


$result = $conn->query($sql);
$cuentas = [];

while ($row = $result->fetch_assoc()) {
    $cuentas[] = $row;
}

echo json_encode($cuentas);
$conn->close();
