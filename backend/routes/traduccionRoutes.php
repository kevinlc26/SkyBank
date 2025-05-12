<?php
require_once __DIR__ . '/../controllers/traduccionController.php';

$controller = new TraduccionController();
$method = $_SERVER['REQUEST_METHOD'];

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    if (isset($_POST['text']) && isset($_POST['lang'])) {
        $controller->traducir($_POST['text'], $_POST['lang']);
    } else if (isset($_POST['lang'])) {
        $controller->traducirTodosLosTextos($_POST['lang']);
    } else {
        echo json_encode(['error' => 'Faltan parámetros: text o lang']);
    }
}

?>