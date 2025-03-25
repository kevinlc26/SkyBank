<?php
// controllers/clientesController.php
require_once ('../config/conn.php');

class ClientesController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function insertCliente($data) {
        if (!isset($data['Num_ident'], $data['Nombre'], $data['Apellidos'], $data['Email'])) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Faltan datos obligatorios"]);
            exit;
        }
    
        try {
            $query = "INSERT INTO clientes 
                        (Num_ident, Nombre, Apellidos, Email, Telefono, Direccion, Nacionalidad, Fecha_nacimiento, PIN) 
                      VALUES 
                        (:Num_ident, :Nombre, :Apellidos, :Email, :Telefono, :Direccion, :Nacionalidad, :Fecha_nacimiento, :PIN)";
            
            $stmt = $this->conn->prepare($query);
    
            // Asociar valores manualmente
            $stmt->bindParam(":Num_ident", $data["Num_ident"]);
            $stmt->bindParam(":Nombre", $data["Nombre"]);
            $stmt->bindParam(":Apellidos", $data["Apellidos"]);
            $stmt->bindParam(":Email", $data["Email"]);
            $stmt->bindParam(":Telefono", $data["Telefono"]);
            $stmt->bindParam(":Direccion", $data["Direccion"]);
            $stmt->bindParam(":Nacionalidad", $data["Nacionalidad"]);
            $stmt->bindParam(":Fecha_nacimiento", $data["Fecha_nacimiento"]);
            $stmt->bindParam(":PIN", $data["PIN"]);
            $resultado = $stmt->execute();
    
            if ($resultado) {
                $idCliente = $this->conn->lastInsertId(); // Obtener el ID recién insertado
                header('Content-Type: application/json');
                echo json_encode([
                    "mensaje" => "Cliente registrado con éxito",
                    "ID_cliente" => $idCliente
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(["error" => "Error: No se pudo insertar el cliente"]);
            }
    
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Error al insertar cliente: " . $e->getMessage()]);
        }
    }              
}
?>


