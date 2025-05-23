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

if ($_SERVER["REQUEST_METHOD"] === "GET") {

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
        $cuentasController->getCuentaByIdEdit($_GET['ID_cuenta_empleado']);
    } else if (isset($_GET['campos'])) {
        $cuentasController->getCamposCuentas();
    } elseif(isset($_GET['ID_clienteContratos'])){
        $cuentasController->getContratosCuentas($_GET['ID_clienteContratos']);
    } elseif(isset($_GET['cli_ID_Cuentas'])){
        $cuentasController->getCuentasSinTarjetas($_GET['cli_ID_Cuentas']);
    } else if (isset($_GET['ID_cuenta_datos'])) {
        $cuentasController->getDatosCuenta($_GET['ID_cuenta_datos']);
    } else {
        $cuentasController->getCuentas();
    }
    
}

if ($_SERVER["REQUEST_METHOD"] === "PUT") {

    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['ID_cliente'], $data['id'])) {
        $cuentasController->editCuentaById($data);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "PATCH"){
    
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['Estado_cuenta'], $data['ID_cuenta'])) {
        $cuentasController->editEstadoCuenta($data);
    } else {
        $cuentasController->bloqDesbloqRecibo($data);
    }
    
}
?>