<?php
// routes/empleadosRoutes.php
require_once __DIR__ . '/../controllers/empleadosController.php';

$controller = new EmpleadosController();

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "GET") {
    $controller->getEmpleados();
} elseif ($method == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->insertEmpleado($data);
} else {
    echo json_encode(["error" => "Método no permitido"]);
}
?>
