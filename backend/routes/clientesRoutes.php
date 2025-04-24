<?php
require_once __DIR__ . '/../config/conn.php';  
require_once __DIR__ . '/../controllers/ClientesController.php';

ob_start();

$database = new Database();
$db = $database->getConnection();

if (!class_exists("ClientesController")) {
    echo json_encode(["error" => "ClientesController no encontrado"]);
    exit;
}

$clienteController = new ClientesController($db);

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    ob_end_clean();
    header('Content-Type: application/json');

    if (isset($_FILES['imagen']) && isset($_POST['ID_cliente'])) {
        $idCliente = $_POST['ID_cliente'];
        $directorioDestino = __DIR__ . '/../info/';
        if (!file_exists($directorioDestino)) {
            mkdir($directorioDestino, 0777, true);
        }

        $nombreArchivo = $idCliente . ".jpg";
        $rutaArchivo = $directorioDestino . $nombreArchivo;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaArchivo)) {
            echo json_encode(["mensaje" => "Imagen subida correctamente"]);
        } else {
            echo json_encode(["error" => "Error al mover la imagen"]);
        }
    }
    else{
        $clienteController->insertCliente($data);
    }
}elseif($_SERVER["REQUEST_METHOD"] === "GET"){
    $data = [
        "Num_ident" => $_GET['Num_ident'] ?? null,
        "PIN" => $_GET['PIN'] ?? null
    ];

    // Verificar si los datos existen
    if (isset($data['Num_ident'], $data['PIN'])) {
        $clienteController->LoginCliente($data);
    } else {
        echo json_encode(["error" => "Faltan datos obligatorios"]);
    }
} 
 else {
    ob_end_clean();
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
?>
