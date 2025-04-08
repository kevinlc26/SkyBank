<?php
// routes/empleadosRoutes.php
require_once __DIR__ . '/../controllers/empleadoController.php';

$controller = new EmpleadosController();
$method = $_SERVER['REQUEST_METHOD'];

header("Content-Type: application/json");

switch ($method) {
    case "GET":
        if (isset($_GET['campos'])) { // obtener campos de la tabla
            $controller->getCamposEmpleado();
        } else if (isset($_GET['ID_empleado'])) {
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
        if (isset($data['ID_empleado'])) {
            $controller->editEmpleado( $data);
        } else {
            echo json_encode(["error" => "ID requerido para actualizar put"]);
        }
        break;

    case "PATCH":
        $data = json_decode(file_get_contents("php://input"), true); //PARA CAMBIAR EL ESTADO
        if (isset($data['ID_empleado'])) {
            $controller->editEstadoEmpleado($data);
        } else {
            echo json_encode(["error" => "ID requerido para actualizar"]);
        }
        break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
        break;
}
?>
