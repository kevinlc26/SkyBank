<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$request_uri = $_SERVER['REQUEST_URI'];
$parsed_url = parse_url($request_uri);
$path = isset($parsed_url['path']) ? $parsed_url['path'] : '';

$base_path = "/SkyBank/backend/public/api.php";

$endpoint = str_replace($base_path, "", $path);
$endpoint = trim($endpoint, "/");

switch ($endpoint) {
    case "clientes":
        require_once __DIR__ . '/../routes/clientesRoutes.php';
        break;
    case "cuentas":
        require_once __DIR__ . '/../routes/cuentasRoutes.php';
        break;
    case "empleados": 
        require_once __DIR__ . '/../routes/empleadosRoutes.php';
        break;
    case "tarjetas":
        require_once __DIR__ . '/../routes/tarjetasRoutes.php';
        break;
    case "transferencias":
        require_once __DIR__ .'/../routes/transferenciasRoutes.php';
        break;
    case "movimientos":    
        require_once __DIR__ . '/../routes/MovimientosRoutes.php';   
        break;
    default:
        http_response_code(404);
        echo json_encode(["error" => "Ruta no encontrada", "endpoint" => $endpoint]);
        break;
}
?>
