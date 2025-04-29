<?php
require_once __DIR__ . '/../controllers/empleadoController.php';

$controller = new EmpleadosController();
$method = $_SERVER['REQUEST_METHOD'];

header("Content-Type: application/json");

switch ($method) {
    case "GET":
        if (isset($_GET['campos'])) { // CAMPOS TABLA
            $controller->getCamposEmpleado();
        } else if (isset($_GET['ID_empleado'])) { // EMPLEADO BY ID
            $controller->getEmpleadoById($_GET['ID_empleado']); 
        } else if (isset($_GET['Estado_empleado'])) { // EMPLEADOS BY ESTADO
            $controller->getEmpleadosEstado($_GET['Estado_empleado']);
        } else if (isset($_GET['Num_ident'])) { // EMPLEADO BY NUM IDENT
            $controller->getEmpleadoNombre($_GET['Num_ident']);
        }  else {
            $controller->getEmpleados(); // TODOS
        }
        break;

    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['Num_ident'], $data['PIN'])) { // LOGIN DE EMPLEADO
            $controller->loginEmpleado($data);
        } else if (isset($_GET['verif_password'], $_GET['ID_empleado'])) { // VERIFICAR CONTRASEÑA
            $controller->getOldPassword($data, $_GET['ID_empleado']);
        } else if (isset($_GET['cambioPassword'], $_GET['ID_empleado'])) {  // CAMBIAR CONTRASEÑA
            $controller->updatePassword($data, $_GET['ID_empleado']);
        } else { // ALTA EMPLEADO
            $controller->addEmpleado($data);
        }
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
        if (isset($data['ID_empleado'], $data['Estado_empleado'])) {
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
