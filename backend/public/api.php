<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Configuración de la base de datos
$host = "localhost";
$db_name = "mi_base_de_datos";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["error" => "Error de conexión: " . $e->getMessage()]);
    exit;
}

// Manejo de peticiones
$method = $_SERVER['REQUEST_METHOD'];

if ($method == "GET") {
    // Obtener usuarios
    $stmt = $conn->prepare("SELECT * FROM usuarios");
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

} elseif ($method == "POST") {
    // Recibir datos JSON desde el frontend
    $datos = json_decode(file_get_contents("php://input"), true);

    if (isset($datos['nombre']) && isset($datos['email'])) {
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email) VALUES (:nombre, :email)");
        $stmt->bindParam(":nombre", $datos['nombre']);
        $stmt->bindParam(":email", $datos['email']);

        if ($stmt->execute()) {
            echo json_encode(["mensaje" => "Usuario registrado con éxito"]);
        } else {
            echo json_encode(["error" => "Error al registrar usuario"]);
        }
    } else {
        echo json_encode(["error" => "Datos incompletos"]);
    }
} else {
    echo json_encode(["error" => "Método no permitido"]);
}
?>
