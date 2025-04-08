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
    if(isset($_GET['ID_cuenta'])){
        $transferenciasController->Movimientos($_GET);
    }
}
if ($_SERVER["REQUEST_METHOD"]==="PATCH"){
    $transferenciasController->bloqDesbloqRecibo($data);
}
?>