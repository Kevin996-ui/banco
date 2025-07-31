<?php
include_once __DIR__ . '/../conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['numero_tarjeta'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $limite = $_POST['limite_credito'] ?? 0.00;
    $fecha = $_POST['fecha_emision'] ?? date('Y-m-d');
    $id_cuenta = $_POST['id_cuenta'] ?? '';

    if ($numero && $tipo && $limite && $id_cuenta) {
        $sql = "INSERT INTO tarjeta_credito (numero_tarjeta, tipo, limite_credito, fecha_emision, id_cuenta) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsi", $numero, $tipo, $limite, $fecha, $id_cuenta);
        $stmt->execute();
        echo json_encode(["mensaje" => "Tarjeta registrada correctamente"]);
        $stmt->close();
    } else {
        echo json_encode(["error" => "Datos incompletos"]);
    }
}

$conn->close();
