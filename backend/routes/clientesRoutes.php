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

// POST
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
    } else if (strpos($_SERVER['REQUEST_URI'], "loginCliente") !== false) {
        $data = json_decode(file_get_contents("php://input"), true);
        $clienteController->LoginCliente($data);
    } else {
        $data = json_decode(file_get_contents("php://input"), true);
        $clienteController->insertCliente($data);
    }

// GET
} elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
    header('Content-Type: application/json');

    if (isset($_GET['Num_ident'], $_GET['PIN'])) { // VERIFICACION PARA LOGIN 
        $data = [
            "Num_ident" => $_GET['Num_ident'],
            "PIN" => $_GET['PIN']
        ];
        $clienteController->LoginCliente($data);

    } else if (isset($_GET['Estado_cliente'])) { // CLIENTES SEGUN ESTADO
        $clienteController->getClientesEstado($_GET['Estado_cliente']);

    } else if (isset($_GET['ID_cliente_empleado'])) {
        $clienteController->getClienteByIdEdit($_GET['ID_cliente_empleado']);
    } else if (isset($_GET['campos'])) {
        $clienteController->getCamposClientes();
    } else { // TODOS
        $clienteController->getClientes();
    }

} else {
    ob_end_clean();
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
?>
