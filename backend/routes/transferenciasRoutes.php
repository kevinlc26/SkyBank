<?php
require_once __DIR__ . '/../config/conn.php';  
require_once __DIR__ . '/../controllers/transferenciasController.php';

ob_start();

$database = new Database();
$db = $database->getConnection();

$transferenciasController= new transferenciasController($db);

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $transferenciasController->insertTraspaso($data);
}
if ($_SERVER["REQUEST_METHOD"]==="GET"){
    if(isset($_GET['ID_cuenta'])) { // GET TRANSFERENCIAS BY CUENTA
        $transferenciasController->Movimientos($_GET);
    } else if (isset($_GET['campos'])) { // CAMPOS DE LA TABLA
        $transferenciasController->getCamposTransferencias();
    } else if (isset($_GET['ID_movimiento_empresa'])) {
        $transferenciasController->getTransferenciaByIdEdit($_GET['ID_movimiento_empresa']);
    } else  { // TODAS
        $transferenciasController->getTransferencias();
    }
}
if ($_SERVER["REQUEST_METHOD"]==="PATCH"){
    $transferenciasController->bloqDesbloqRecibo($data);
}
?>