<?php
require_once ('../config/conn.php');

class ClientesController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function insertCliente($data) {
        if (!isset($data['Num_ident'], $data['Nombre'], $data['Apellidos'], $data['Email'], $data['PIN'])) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Faltan datos obligatorios"]);
            exit;
        }
    
        try {
            // Hashear la contraseña antes de guardarla
            $hashedPIN = md5($data['PIN']);
    
            $query = "INSERT INTO clientes 
                        (Num_ident, Nombre, Apellidos, Email, Telefono, Direccion, Nacionalidad, Fecha_nacimiento, PIN) 
                      VALUES 
                        (:Num_ident, :Nombre, :Apellidos, :Email, :Telefono, :Direccion, :Nacionalidad, :Fecha_nacimiento, :PIN)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":Num_ident", $data["Num_ident"]);
            $stmt->bindParam(":Nombre", $data["Nombre"]);
            $stmt->bindParam(":Apellidos", $data["Apellidos"]);
            $stmt->bindParam(":Email", $data["Email"]);
            $stmt->bindParam(":Telefono", $data["Telefono"]);
            $stmt->bindParam(":Direccion", $data["Direccion"]);
            $stmt->bindParam(":Nacionalidad", $data["Nacionalidad"]);
            $stmt->bindParam(":Fecha_nacimiento", $data["Fecha_nacimiento"]);
            $stmt->bindParam(":PIN", $hashedPIN);
            
            $resultado = $stmt->execute();
    
            if ($resultado) {
                $idCliente = $this->conn->lastInsertId();
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
    public function LoginCliente($data) {
        if (!isset($data['Num_ident'], $data['PIN'])) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Faltan datos obligatorios"]);
            exit;
        }
    
        try {
            $query = "SELECT PIN, ID_cliente FROM clientes WHERE Num_ident = :Num_ident";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":Num_ident", $data["Num_ident"]);
            $stmt->execute();
    
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $passwordmd5 = md5($data['PIN']);
    
            if (!$user) {
                header('Content-Type: application/json');
                echo json_encode([
                    "error" => "Usuario o contraseña incorrectos",
                    "Usuario" => $data['Num_ident'],
                    "PIN DATA MD5" => $passwordmd5
                ]);
            } elseif ($passwordmd5 === $user['PIN']) {
                header('Content-Type: application/json');
                echo json_encode([
                    "mensaje" => "Login Correcto",
                    "DNI" => $data["Num_ident"],
                    "ID cliente"=> $user["ID_cliente"]
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    "error" => "Usuario o contraseña incorrectos",
                    "Usuario" => $data['Num_ident'],
                    "PIN DATA MD5" => $passwordmd5
                ]);
            }
    
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Error al iniciar sesión: " . $e->getMessage()]);
        }
    }    
    

    // GET DE CLIENTES SEGUN ESTADO
    public function getClientesEstado($estado_cliente) {
        if (!isset($estado_cliente) || empty($estado_cliente)) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Falta estado de cliente"]);
            exit;
        }
    
        try {
            $stmt = $this->conn->prepare("SELECT * FROM clientes WHERE Estado_Clientes = :estadoCliente");
            $stmt->bindParam(":estadoCliente", $estado_cliente, PDO::PARAM_STR);
            $stmt->execute(); // Ejecutar la consulta
    
            $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            if (empty($clientes)) {
                header('Content-Type: application/json');
                echo json_encode(["mensaje" => "No se encontraron clientes con ese estado."]);
                exit;
            }
    
            header('Content-Type: application/json');
            echo json_encode($clientes);
    
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Error al obtener clientes: " . $e->getMessage()]);
        }
    }
    

    // GET CLIENTES TODOS
    public function getClientes (){
        try {
            $sql = "SELECT ID_cliente, Num_ident, Nombre, Apellidos, Nacionalidad, Fecha_nacimiento, Telefono, Email, Direccion  FROM clientes"; 
            $stmt = $this->conn->prepare($sql); 
            $stmt->execute();
    
            $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    
            header('Content-Type: application/json');
            echo json_encode($clientes); 
            
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Error al obtener clientes: " . $e->getMessage()]);
        }

    }

    public function getInfoClientebyID($ID_cliente) {
        if (!isset($ID_cliente)) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "No se ha enviado el ID del cliente"]);
            exit;
        }
    
        $sql = "SELECT Num_ident, Nombre, Apellidos, Nacionalidad, Fecha_nacimiento, Telefono, Email, Direccion FROM clientes WHERE ID_cliente = ?";
    
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$ID_cliente]);
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    
            header('Content-Type: application/json');
            if ($cliente) {
                echo json_encode($cliente);
            } else {
                echo json_encode(["mensaje" => "Cliente no encontrado."]);
            }
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
        }
    }
    
    public function getDatosCliente($ID_cliente){
        if (!isset($ID_cliente)) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "No se ha enviado el ID del cliente"]);
            exit;
        }
    
        $sql = "SELECT Nombre, Apellidos, Telefono, Email, Direccion, Num_ident FROM clientes WHERE ID_cliente=?";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$ID_cliente]);
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    
            header('Content-Type: application/json');
            if ($cliente) {
                echo json_encode($cliente);
            } else {
                echo json_encode(["mensaje" => "Cliente no encontrado"]);
            }
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
        }
    }

    public function editDatosCliente($data) {
        header('Content-Type: application/json');
    
        if (!isset($data['ID_cliente'])) {
            echo json_encode(["error" => "No se ha enviado el ID del cliente"]);
            exit;
        }
    
        try {
            if (isset($data['PIN']) && !empty($data['PIN'])) {
                
                $hashedPIN = md5($data['PIN']);
                $sql = "UPDATE Clientes SET Telefono = ?, Email = ?, Direccion = ?, PIN = ? WHERE ID_cliente = ?";
                $params = [
                    $data['Telefono'],
                    $data['Email'],
                    $data['Direccion'],
                    $hashedPIN,
                    $data['ID_cliente']
                ];
            } else {
                $sql = "UPDATE Clientes SET Telefono = ?, Email = ?, Direccion = ? WHERE ID_cliente = ?";
                $params = [
                    $data['Telefono'],
                    $data['Email'],
                    $data['Direccion'],
                    $data['ID_cliente']
                ];
            }
    
            $stmt = $this->conn->prepare($sql);
            $success = $stmt->execute($params);
    
            if ($success) {
                echo json_encode(["mensaje" => "Datos del cliente actualizados con éxito."]);
            } else {
                echo json_encode(["mensaje" => "Ha ocurrido un error al actualizar los datos del cliente."]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
        }
    }
    
}
                   
?>


