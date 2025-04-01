<?php
// routes/empleadosRoutes.php
require_once __DIR__ . '/../controllers/empleadosController.php';

$controller = new EmpleadosController();
$method = $_SERVER['REQUEST_METHOD'];

header("Content-Type: application/json");

switch ($method) {
    case "GET":
        if (isset($_GET['id'])) {
            $controller->getEmpleadoById($_GET['id']); // Obtener un solo empleado por ID
        } else {
            $controller->getEmpleados(); // Obtener todos los empleados
        }
        break;

    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        $controller->insertEmpleado($data);
        break;

    case "PUT":
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($_GET['id'])) {
            $controller->editEmpleado($_GET['id'], $data);
        } else {
            echo json_encode(["error" => "ID requerido para actualizar"]);
        }
        break;

    case "DELETE":
        if (isset($_GET['id'])) {
            $controller->deleteEmpleado($_GET['id']);
        } else {
            echo json_encode(["error" => "ID requerido para eliminar"]);
        }
        break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
        break;
}
?>
