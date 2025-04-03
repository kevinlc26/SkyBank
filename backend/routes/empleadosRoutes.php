<?php
// routes/empleadosRoutes.php
require_once __DIR__ . '/../controllers/empleadoController.php';

$controller = new EmpleadosController();
$method = $_SERVER['REQUEST_METHOD'];

header("Content-Type: application/json");

switch ($method) {
    case "GET":
        if (isset($_GET['ID_empleado'])) {
            $controller->getEmpleadoById($_GET['ID_empleado']); // Obtener un solo empleado por ID
        } else if (isset($_GET['Estado_empleado'])) {
            $controller->getEmpleadosEstado($_GET['Estado_empleado']);
        } else {
            $controller->getEmpleados(); // Obtener todos los empleados
        }
        break;

    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        $controller->addEmpleado($data);
        break;

    case "PUT":
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($_GET['ID_empleado'])) {
            $controller->editEmpleado($_GET['ID_empleado'], $data);
        } else {
            echo json_encode(["error" => "ID requerido para actualizar"]);
        }
        break;

    case "DELETE":
        if (isset($_GET['ID_empleado'])) {
            $controller->deleteEmpleado($_GET['ID_empleado']);
        } else {
            echo json_encode(["error" => "ID requerido para eliminar"]);
        }
        break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
        break;
}
?>
