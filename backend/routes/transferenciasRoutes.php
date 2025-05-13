<?php
require_once __DIR__ . '/../config/conn.php';  
require_once __DIR__ . '/../controllers/transferenciasController.php';

ob_start();

$database = new Database();
$db = $database->getConnection();

$transferenciasController= new transferenciasController($db);

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($data['Descripcion'])){
        $transferenciasController->traspasoEntreCuentas($data);
    }elseif($data['concepto']){
        $transferenciasController->transferencia($data);
    }
    
}
if ($_SERVER["REQUEST_METHOD"]==="GET"){

    if (isset($_GET['ID_cuenta'])) { // GET TRANSFERENCIAS BY CUENTA
        $transferenciasController->Movimientos($_GET);
    } else  { // TODAS
        $transferenciasController->getTransferencias();
    }
}
if ($_SERVER["REQUEST_METHOD"]==="PATCH"){
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data['ID_movimiento'], $data['Estado'])) {
        $transferenciasController->editTransferenciaEstado($data);
    } 
   
}
?>