<?php
$host = 'localhost';
$db = 'banco';
$user = 'root';
$pass = ''; // Contraseña de MySQL, si tienes una

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>