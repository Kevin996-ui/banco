<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// === Función para registrar accesos ===
function logAcceso($modulo, $accion)
{
    $fecha = date("Y-m-d H:i:s");
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'IP_DESCONOCIDA';
    $log = "[$fecha] Acceso: $modulo/$accion desde $ip\n";
    file_put_contents(__DIR__ . "/logs/api.log", $log, FILE_APPEND);
}

// === Función para registrar errores ===
function logError($mensaje)
{
    $fecha = date("Y-m-d H:i:s");
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'IP_DESCONOCIDA';
    $log = "[$fecha] Error desde $ip: $mensaje\n";
    file_put_contents(__DIR__ . "/logs/error.log", $log, FILE_APPEND);
}

// === 1. Definición de roles y tokens asociados ===
$roles_validos = [
    'admin_token' => 'administrador',
    'dev_token'   => 'desarrollador',
    'sup_token'   => 'supervisor'
];

$rol_usuario = '';

// === 2. Validación del token (desde GET para facilitar pruebas) ===
if (isset($_GET['token']) && array_key_exists($_GET['token'], $roles_validos)) {
    $rol_usuario = $roles_validos[$_GET['token']];
} else {
    http_response_code(401);
    logError("Token inválido o no autorizado.");
    echo json_encode(["error" => "No autorizado"]);
    exit;
}

// === 3. Validación de parámetros ===
$modulo = $_GET['modulo'] ?? '';
$accion = $_GET['accion'] ?? '';

if (!$modulo || !$accion) {
    http_response_code(400);
    logError("Faltan parámetros 'modulo' o 'accion'.");
    echo json_encode(["error" => "Faltan parámetros 'modulo' o 'accion'"]);
    exit;
}

// === 4. Control de permisos según el rol ===
$acciones_por_rol = [
    "administrador" => ["listar", "crear", "actualizar", "eliminar"],
    "desarrollador" => ["listar"], // Solo pruebas de lectura
    "supervisor"    => ["listar"]  // Solo lectura
];

if (!in_array($accion, $acciones_por_rol[$rol_usuario])) {
    http_response_code(403);
    logError("Rol $rol_usuario no tiene permiso para $accion en $modulo");
    echo json_encode(["error" => "Acceso denegado para esta operación"]);
    exit;
}

// === 5. Validación de existencia del archivo ===
$ruta = __DIR__ . "/$modulo/$accion.php";
if (!file_exists($ruta)) {
    http_response_code(404);
    logError("Ruta no encontrada: $modulo/$accion");
    echo json_encode(["error" => "Ruta no encontrada: $modulo/$accion"]);
    exit;
}

// === 6. Registro de acceso ===
logAcceso($modulo, $accion);

// === 7. Ejecución del microservicio correspondiente ===
require $ruta;
