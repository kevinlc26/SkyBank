<?php
// routes/empleadosRoutes.php
require_once __DIR__ . '/../controllers/tarjetasController.php';

$controller = new TarjetasController();
$method = $_SERVER['REQUEST_METHOD'];

header("Content-Type: application/json");

switch ($method) {
    case "GET":
        if (isset($_GET['campos'])) {
            $controller->getCamposTarjeta();   // CAMPOS TABLA
        } else if (isset($_GET['ID_tarjeta']) || isset($_GET['ID_delete'])) {
            $controller->getTarjetaById($_GET['ID_tarjeta']); // 1 TARJETA
        }elseif(isset($_GET['ID_cliente'])){
            $controller ->getTarjetasCliente($_GET['ID_cliente']); // Muestra las tarjetas del Cliente 
        }elseif(isset($_GET['ID_tarjeta']) && !isset($_GET['ID_delete'])){
            $controller->detallesTarjeta($_GET['ID_tarjeta']);
        } elseif(isset($_GET['LimiteTarjeta'])){
            $controller->getLimitesOperaciones($_GET['LimiteTarjeta']);
        } elseif(isset($_GET['TarjetaPIN'])){
            $controller->getPINTarjeta($_GET['TarjetaPIN']);
        } else if (isset($_GET['ID_tarjeta_datos'])) {
            $controller->getTarjetaByIdDetalle($_GET['ID_tarjeta_datos']);  // DATOS PARA DETALLE EMPLEADO
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
        if(isset($data['ID_tarjeta']) && isset($data['nuevoPin'])){
            $controller->editPINTarjeta($data);
        } elseif (isset($data['id'])) {
            $controller->editTarjeta( $data);
        } else {
            echo json_encode(["error" => "ID requerido para actualizar"]);
        }
        break;
    case "PATCH":
        $data = json_decode(file_get_contents("php://input"), true); //PARA CAMBIAR EL ESTADO
        if (isset($data['ID_tarjeta']) && isset($data["Limite_operativo"])) {
            $controller->editLimitesOperaciones($data);
        } elseif (isset($data['ID_tarjeta'])) {
            $controller->editEstadoTarjeta($data);
        } else {
            echo json_encode(["error" => "ID requerido para actualizar"]);
        }
        break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
        break;
}
?>