<?php
require_once __DIR__ . '/../config/conn.php';  
require_once __DIR__ . '/../controllers/cuentasController.php';

ob_start();

$database = new Database();
$db = $database->getConnection();

$cuentasController= new cuentasController($db);

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($data['ID_cliente'])) { // INSERT CUENTA
        $cuentasController->addCuenta($data); 
    } else if ($data) {
        $cuentasController->CuentasInicio($data); // SELECT CUENTA SEGUN ID_CLIENTE
    } else {
        
    }
    
}

if ($_SERVER["REQUEST_METHOD"]==="GET") {

    if (isset($_GET['ID_cuentaDetalle'])){
        $cuentasController->DetallesCuenta($_GET);
    } else if (isset($_GET['ID_cliente'])){
        $cuentasController->getCuentasIdCliente($_GET);
    } elseif (isset($_GET['ID_cliente_cuentas'])){
        $cuentasController->verCuentas($_GET);
    } else if (isset($_GET['ID_cuenta'], $_GET['ID_delete'])){
        $cuentasController->DetallesCuenta($_GET);
    } else if (isset($_GET['ID_cliente'])){
        $cuentasController->getCuentasIdCliente($_GET);
    } else if (isset($_GET['campos'])) {
        $cuentasController->getCamposCuentas($_GET);
    } else {
        $cuentasController->getCuentas();
    }
    
}

if ($_SERVER["REQUEST_METHOD"]==="PATCH"){
    $cuentasController->bloqDesbloqRecibo($data);
}
?>