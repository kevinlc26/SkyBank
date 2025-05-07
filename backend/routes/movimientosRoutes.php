<?php
require_once __DIR__ . '/../controllers/movimientosController.php';

$controller = new MovimientosController();
$method = $_SERVER['REQUEST_METHOD'];

header("Content-Type: application/json");

switch ($method) {
    case "GET":
        if (isset($_GET['ID_movimiento']) || isset($_GET['ID_delete'])) {
            $controller->getMovimientoById($_GET['ID_movimiento']); // 1 MOVIMIENTO
        }else if (isset($_GET['ID_cuenta-Ahorro'])){
            $controller->AhorroMovimientos($_GET);
        } elseif (isset($_GET['ID_cuenta'])){
            $controller->Movimientos($_GET);
        } else if (isset($_GET['ID_cuentaRecibos'])){
            $controller->RecibosCuenta($_GET);
        } else if (isset($_GET['ID_tarjeta'])) { // SEGUN TARJETA
            $controller->getMovimientosByTarjeta($_GET['ID_tarjeta']);
        } else if (isset($_GET['Num_Ident'])) { // SEGUN CLIENTE
            $controller->getMovimientosByNumIdent($_GET['Num_Ident']);
        } else if (isset($_GET['ID_cuenta_empleado'])) { // SEGUN CLIENTE
            $controller->getMovimientosByCuenta($_GET['ID_cuenta_empleado']);
        } else if(isset($_GET['cuenta_ID-Traspasos'])){
            $controller->obtenerTransferenciasCuenta($_GET);
        } else {
            $controller->getMovimientos(); // TODOS LOS MOVIMIENTOS
        }
        break;

    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        break;

    case "PUT":
        $data = json_decode(file_get_contents("php://input"), true);

        break;
    case "PATCH":
        $data = json_decode(file_get_contents("php://input"), true); //PARA CAMBIAR EL ESTADO

        if (isset($data['ID_movimiento'], $data['Estado'])) {
            $controller->editMovimientoEstado($data);
        }
        break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
        break;
}
?>