<?php
require_once __DIR__ . '/../controllers/traduccionController.php';

$controller = new TraduccionController();
$method = $_SERVER['REQUEST_METHOD'];

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    echo json_encode($input);
    exit;
    if (isset($input['text']) && isset($input['lang'])) {
        $controller->traducir($input);
    } else if (isset($_POST['lang'])) {
       // $controller->traducirTodosLosTextos($_POST['lang']);
    } else {
        echo json_encode(['error' => 'Faltan parámetros: text o lang']);
    }
}

?>