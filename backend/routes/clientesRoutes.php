<?php
require_once __DIR__ . '/../config/conn.php';  
require_once __DIR__ . '/../controllers/ClientesController.php';  // <-- IMPORTANTE

// Crear conexión con la base de datos
$database = new Database();
$db = $database->getConnection();

// Verifica que la clase ClientesController exista
if (!class_exists("ClientesController")) {
    echo json_encode(["error" => "ClientesController no encontrado"]);
    exit;
}

$clienteController = new ClientesController($db);

// Recibe los datos del cuerpo de la petición
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $clienteController->insertCliente($data);
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
?>
