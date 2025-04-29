<?php
require_once ('../config/conn.php');

class ClientesController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // ADD NUEVO CLIENTE
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
    // LOGIN
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
                    "DNI" => $data["Num_ident"]
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
    // GET CLIENTE PARA FORMULARIO EDIT EMPRESA
    public function getClienteByIdEdit($ID_cliente) {
        if (!isset($ID_cliente) || empty($ID_cliente)) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Falta ID de cliente"]);
            exit;
        }

        try {

            $sql = "SELECT Telefono, Email, Direccion, Estado_Clientes FROM clientes WHERE ID_cliente = ?"; 
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$ID_cliente]);
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cliente) {
                header('Content-Type: application/json');
                echo json_encode($cliente);
            } else {
                header('Content-Type: application/json');
                echo json_encode(["error" => "Cliente no encontrado"]);
            }

        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Error al obtener el cliente: " . $e->getMessage()]);
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
            $stmt->execute();
    
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
            $sql = "SELECT ID_cliente, Num_ident, Nombre, Apellidos, Nacionalidad, Fecha_nacimiento, Telefono, Email, Direccion, Estado_Clientes  FROM clientes"; 
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

    // GET CAMPOS DE LA TABLA CUENTAS
    public function getCamposClientes() {
        $tableName = "clientes";
        try {
            $stmt = $this->conn->prepare("DESCRIBE " . $tableName);
            $stmt->execute();
            
            $campos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            $camposConValoresEnum = [];
            
            foreach ($campos as $campo) {
                if (strpos($campo['Type'], 'enum') !== false) {
                    preg_match("/enum\((.*)\)/", $campo['Type'], $matches);
                    $enumValues = explode(",", $matches[1]);
                    $enumValues = array_map(function($value) {
                        return trim($value, "'"); 
                    }, $enumValues);
                    
                    $campo['EnumValues'] = $enumValues;
                }
                
                $camposConValoresEnum[] = $campo;
            }
    
            if ($camposConValoresEnum) {
                echo json_encode($camposConValoresEnum);  
            } else {
                echo json_encode(["error" => "No se encontraron campos para la tabla especificada"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener los campos: " . $e->getMessage()]);
        }
    }

    // GET CLIENTE SEGUN NUM_IDENT
    public function getClienteByNumIdent($Num_Ident){
        $sql = "SELECT ID_cliente, Num_ident, Nombre, Apellidos, Nacionalidad, Fecha_nacimiento, Telefono, Email, Direccion, Estado_Clientes  FROM clientes WHERE Num_ident = :Num_ident";
    
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':Num_ident', $Num_Ident, PDO::PARAM_STR);
            $stmt->execute();
    
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($cliente) {
                echo json_encode($cliente);
            } else {
                echo json_encode(["error" => "Cliente no encontrado."]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error en la consulta: " . $e->getMessage()]);
        }
    }

    // GET CLIENTE PARA DETALLE EMPLEADO
    public function getClienteDetalleEmpleado($Num_Ident) {

        $sql = "SELECT cli.ID_cliente, cli.Num_ident, cli.Nombre, cli.Apellidos, cli.Nacionalidad, cli.Fecha_nacimiento, cli.Telefono, cli.Email, cli.Direccion, cli.Estado_Clientes, cc.ID_cuenta, t.ID_tarjeta
                FROM clientes cli
                JOIN cliente_cuenta cc ON cli.ID_cliente = cc.ID_cliente
                JOIN cuentas c ON cc.ID_cuenta = c.ID_cuenta
                LEFT JOIN tarjetas t ON c.ID_cuenta = t.ID_cuenta
                WHERE cli.Num_ident = :Num_ident";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':Num_ident', $Num_Ident);
        $stmt->execute();
        $rawData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $clientes = [];
    
        foreach ($rawData as $row) {
            $idCliente = $row['ID_cliente'];
    
            if (!isset($clientes[$idCliente])) {
                $clientes[$idCliente] = [
                    'ID_cliente' => $row['ID_cliente'],
                    'Num_ident' => $row['Num_ident'],
                    'Nombre' => $row['Nombre'],
                    'Apellidos' => $row['Apellidos'],
                    'Nacionalidad' => $row['Nacionalidad'],
                    'Fecha_nacimiento' => $row['Fecha_nacimiento'],
                    'Telefono' => $row['Telefono'],
                    'Email' => $row['Email'],
                    'Direccion' => $row['Direccion'],
                    'Estado_Clientes' => $row['Estado_Clientes'],
                    'cuentas_asociadas' => [],
                    'tarjetas_asociadas' => []
                ];
            }
    
            if ($row['ID_cuenta'] && !in_array($row['ID_cuenta'], $clientes[$idCliente]['cuentas_asociadas'])) {
                $clientes[$idCliente]['cuentas_asociadas'][] = $row['ID_cuenta'];
            }
    
            if ($row['ID_tarjeta'] && !in_array($row['ID_tarjeta'], $clientes[$idCliente]['tarjetas_asociadas'])) {
                $clientes[$idCliente]['tarjetas_asociadas'][] = $row['ID_tarjeta'];
            }
        }
    
        header('Content-Type: application/json');
        if (empty($clientes)) {
            echo json_encode(["error" => "El cliente no se encuentra en la base de datos"]);
        } else {
            echo json_encode(array_values($clientes)[0]);
        }
        
 
        exit();
    }
        


    // EDIT CLIENTE
    public function updateCliente($data) {
        $ID_cliente = $data['id'];
        $Telefono = $data['Telefono'];
        $Email = $data['Email'];
        $Direccion = $data['Direccion'];
        $Estado = $data['Estado_Clientes'];

        try {
            $sql = "UPDATE clientes SET Telefono = ?, Email = ?, Direccion = ?, Estado_Clientes = ? WHERE ID_cliente = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$Telefono, $Email, $Direccion, $Estado, $ID_cliente]);

            echo json_encode( ["success" => true, "message" => "Cliente actualizado correctamente"]);
        } catch (PDOException $e) {
            echo json_encode(["success" => false, "error" => "Error al actualizar cliente: " . $e->getMessage()]);
        }
    }

    //PATCH CLIENTE ESTADO
    public function editEstadoCliente ($data){

        $ID_cliente = $data['ID_cliente'];
        $Estado_cliente = $data['Estado_Clientes'];

        $sql = "UPDATE clientes SET Estado_Clientes = :Estado_Clientes WHERE ID_cliente = :ID_cliente";

        $stmt = $this->conn->prepare($sql);

        if ($stmt) {
            if ($stmt->execute([
                ':Estado_Clientes' => $Estado_cliente,
                ':ID_cliente' => $ID_cliente
            ])) {
                echo json_encode(["success" => true, "mensaje" => "Estado de cliente actualizado correctamente."]);
            } else {
                echo json_encode(["success" => false, "error" => "Error al actualizar el estado del cliente."]);
            }
        } else {
            echo json_encode(["success" => false, "error" => "Error en la preparación de la consulta."]);
        }
    }
}
                   
?>


