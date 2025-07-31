<?php
include_once __DIR__ . '/../conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_tarjeta'] ?? '';
    $numero = $_POST['numero_tarjeta'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $limite = $_POST['limite_credito'] ?? 0.00;
    $fecha = $_POST['fecha_emision'] ?? date('Y-m-d');
    $id_cuenta = $_POST['id_cuenta'] ?? '';

    if ($id && $numero && $tipo && $limite && $id_cuenta) {
        $sql = "UPDATE tarjeta_credito SET numero_tarjeta=?, tipo=?, limite_credito=?, fecha_emision=?, id_cuenta=? WHERE id_tarjeta=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsii", $numero, $tipo, $limite, $fecha, $id_cuenta, $id);
        $stmt->execute();
        echo json_encode(["mensaje" => "Tarjeta actualizada correctamente"]);
        $stmt->close();
    } else {
        echo json_encode(["error" => "Datos incompletos"]);
    }
}

$conn->close();
