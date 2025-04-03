<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obtiene la parte de la URL sin parámetros
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Ajusta la base según tu estructura
$base_path = "/SkyBank/backend/public/api.php";

// Obtiene solo el endpoint (sin la ruta base)
$endpoint = str_replace($base_path, "", $request_uri);
$endpoint = trim($endpoint, "/"); // Elimina barras innecesarias

// Manejo de rutas
switch ($endpoint) {
    case "clientes":
        require_once __DIR__ . '/../routes/clientesRoutes.php';
        break;
    case "loginCliente":
        require_once __DIR__ . '/../routes/clientesRoutes.php';
        break;
    case "CuentasInicio":
        require_once __DIR__ . '/../routes/cuentasRoutes.php';
        break;
    case "verCuenta":
        require_once __DIR__ . '/../routes/cuentasRoutes.php';
        break;
    case "recibosCliente":
        require_once __DIR__ . '/../routes/cuentasRoutes.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(["error" => "Ruta no encontrada", "endpoint" => $endpoint]);
}
?>
