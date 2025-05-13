<?php
require_once __DIR__ . '/../controllers/traduccionController.php';

$controller = new TraduccionController();

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $input = json_decode(file_get_contents("php://input"), true);

    if (isset($input['texto']) && isset($input['idioma_origen']) && isset($input['idioma_destino'])) {
        $controller->traducir($input);
    } else {
        echo json_encode(['error' => 'Faltan parámetros: text o lang']);
    }
}

?>