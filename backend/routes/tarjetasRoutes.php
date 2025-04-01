<?php
// routes/empleadosRoutes.php
require_once __DIR__ . '/../controllers/tarjetasController.php';

$controller = new TarjetasController();
$method = $_SERVER['REQUEST_METHOD'];

header("Content-Type: application/json");

switch ($method) {
    case "GET":
        if (isset($_GET['ID_tarjeta'])) {
            $controller->getTarjetaById($_GET['ID_tarjeta']); // Obtener un solo empleado por ID
        } else {
            $controller->getTarjetas(); // Obtener todos los empleados
        }
        break;

    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        $controller->addTarjeta($data);
        break;

    case "PUT":
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($_GET['ID_tarjeta'])) {
            $controller->editTarjeta($_GET['ID_tarjeta'], $data);
        } else {
            echo json_encode(["error" => "ID requerido para actualizar"]);
        }
        break;

    case "DELETE":
        if (isset($_GET['ID_tarjeta'])) {
            $controller->deleteTarjeta($_GET['ID_tarjeta']);
        } else {
            echo json_encode(["error" => "ID requerido para eliminar"]);
        }
        break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
        break;
}
?>