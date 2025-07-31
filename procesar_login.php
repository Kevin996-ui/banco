<?php
session_start();
include('conexion.php');

if (!isset($_POST['username']) || !isset($_POST['password'])) {
    die("Faltan datos del formulario.");
}

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT u.id AS id_usuario, u.username, p.nombre_perfil 
        FROM usuarios u
        INNER JOIN perfiles p ON u.perfil_id = p.id
        WHERE u.username = ? AND u.password = SHA2(?, 256)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error al preparar la consulta: " . $conn->error);
}

$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();

    $_SESSION['usuario'] = $usuario['username'];
    $_SESSION['perfil'] = $usuario['nombre_perfil'];

    header("Location: menu.php");
    exit;
} else {
    echo "<p style='color:red'>Credenciales inválidas. <a href='login.php'>Intentar de nuevo</a></p>";
}
