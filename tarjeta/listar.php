<?php
include_once __DIR__ . '/../conexion.php';

header('Content-Type: application/json');

$sql = "
    SELECT 
        tarjeta_credito.*, 
        cuenta.numero_cuenta, 
        cliente.nombre AS nombre_cliente
    FROM tarjeta_credito
    INNER JOIN cuenta ON tarjeta_credito.id_cuenta = cuenta.id_cuenta
    INNER JOIN cliente ON cuenta.id_cliente = cliente.id_cliente
    ORDER BY tarjeta_credito.id_tarjeta DESC
";

$result = $conn->query($sql);

$tarjetas = [];
while ($row = $result->fetch_assoc()) {
    $tarjetas[] = $row;
}

echo json_encode($tarjetas, JSON_UNESCAPED_UNICODE);
$conn->close();
