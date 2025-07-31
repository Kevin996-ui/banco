<?php
function verificarUsuario($username, $password, $conn)
{
    $sql = "SELECT u.id_usuario, p.nombre_perfil 
            FROM usuarios u 
            JOIN perfiles p ON u.perfil_id = p.id_perfil 
            WHERE u.username = ? AND u.password = SHA2(?, 256)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        return $usuario['nombre_perfil'];
    } else {
        return null;
    }
}
