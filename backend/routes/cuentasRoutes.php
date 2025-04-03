<?php
require_once __DIR__ . '/../config/conn.php';  
require_once __DIR__ . '/../controllers/cuentasController.php';

ob_start();

$database = new Database();
$db = $database->getConnection();

$cuentasController= new cuentasController($db);

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cuentasController->CuentasInicio($data);
}
if ($_SERVER["REQUEST_METHOD"]==="GET"){
    $cuentasController->Movimientos($_GET);
}

?>