<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obtiene la URL sin los parámetros de consulta
$request_uri = explode("?", $_SERVER['REQUEST_URI'])[0];

// Ajusta la base según tu estructura
$base_path = "/SkyBank/backend/public/api.php";  
$endpoint = str_replace($base_path, "", $request_uri);

// Manejo de rutas
switch ($endpoint) {
    case "/clientes":
        require_once __DIR__ . '/../routes/clientesRoutes.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(["error" => "Ruta no encontrada", "endpoint" => $endpoint]);
}
?>
