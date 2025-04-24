<?php
// routes/empleadosRoutes.php
require_once __DIR__ . '/../controllers/tarjetasController.php';

$controller = new TarjetasController();
$method = $_SERVER['REQUEST_METHOD'];

header("Content-Type: application/json");

switch ($method) {
    case "GET":
        if (isset($_GET['campos'])) { // CAMPOS TABLA
            $controller->getCamposTarjeta(); 
        } else if (isset($_GET['ID_tarjeta'], $_GET['ID_delete'])) {
            $controller->getTarjetaById($_GET['ID_tarjeta']); // 1 TARJETA
        } else {
            $controller->getTarjetas(); // TODAS LAS TARJETAS
        }
        break;

    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        $controller->addTarjeta();
        break;

    case "PUT":
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['ID_tarjeta'])) {
            $controller->editTarjeta( $data);
        } else {
            echo json_encode(["error" => "ID requerido para actualizar"]);
        }
        break;
    case "PATCH":
        $data = json_decode(file_get_contents("php://input"), true); //PARA CAMBIAR EL ESTADO
        if (isset($data['ID_tarjeta'])) {
            $controller->editEstadoTarjeta( $data);
        } else {
            echo json_encode(["error" => "ID requerido para actualizar"]);
        }
        break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
        break;
}
?>