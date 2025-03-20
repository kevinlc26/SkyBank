<?php
// controllers/clientesController.php
require_once ('../config/conn.php'); // Asegurar que está bien la ruta

class ClientesController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function insertCliente($data) {
        if (!isset($data['Num_ident'], $data['Nombre'], $data['Apellidos'], $data['Email'])) {
            echo json_encode(["error" => "Faltan datos obligatorios"]);
            return;
        }

        try {
            $query = "INSERT INTO clientes (Num_ident, Nombre, Apellidos, Email, Telefono, Direccion, Nacionalidad, Fecha_nacimiento, PIN) 
                      VALUES (:Num_ident, :Nombre, :Apellidos, :Email, :Telefono, :Direccion, :Nacionalidad, :Fecha_nacimiento, :PIN)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute($data);
            echo json_encode(["mensaje" => "Cliente registrado con éxito"]);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al insertar cliente: " . $e->getMessage()]);
        }
    }
}
?>


