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

// Obtiene la URL sin los parámetros de consulta
$request_uri = trim($_SERVER['REQUEST_URI'], "/");

// Ajusta la base según tu estructura
$base_path = "SkyBank/backend/public/api.php";  
$endpoint = str_replace($base_path, "", $request_uri);

// Si hay una barra al inicio, la eliminamos
$endpoint = ltrim($endpoint, "/");

// Manejo de rutas
switch ($endpoint) {
    case "clientes":
        require_once __DIR__ . '/../routes/clientesRoutes.php';
        break;
    case "empleados": 
        require_once __DIR__ . '/../routes/empleadosRoutes.php';
    default:
        http_response_code(404);
        echo json_encode(["error" => "Ruta no encontrada", "endpoint" => $endpoint]);
}
?>
