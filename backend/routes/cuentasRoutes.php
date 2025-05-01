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
    }else {
        $cuentasController->CuentasInicio($data); // SELECT CUENTA SEGUN ID_CLIENTE
    }
    
}

if ($_SERVER["REQUEST_METHOD"]==="GET") {

    if (isset($_GET['ID_cuentaDetalle'])){
        $cuentasController->DetallesCuenta($_GET);
    } else if (isset($_GET['ID_cliente'])){
        $cuentasController->getCuentasIdCliente($_GET);
    } elseif (isset($_GET['ID_cliente_cuentas'])){
        $cuentasController->verCuentas($_GET);
    } else if (isset($_GET['ID_cuenta'])){
        $cuentasController->DetallesCuenta($_GET);
    } else if (isset($_GET['ID_cliente'])){
        $cuentasController->getCuentasIdCliente($_GET);
    } else if (isset($_GET['ID_cuenta_empleado'])) {
        $cuentasController->getCuentaById($_GET['ID_cuenta_empleado']);
    } else if (isset($_GET['campos'])) {
        $cuentasController->getCamposCuentas($_GET['campos']);
    } elseif(isset($_GET['ID_clienteContratos'])){
        $cuentasController->getContratosCuentas($_GET['ID_clienteContratos']);
    } else {
        $cuentasController->getCuentas();
    }
    
}

if ($_SERVER["REQUEST_METHOD"]==="PATCH"){
    $cuentasController->bloqDesbloqRecibo($data);
}
?>