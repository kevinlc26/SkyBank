<?php
require_once  __DIR__ . '/../config/conn.php';

class EmpleadosController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // GET DE TODOS LOS EMPLEADOS
    public function getEmpleados() {
        $stmt = $this->conn->prepare("SELECT ID_empleado, Num_ident, Nombre, Apellidos, Estado_empleado, Nacionalidad, Fecha_nacimiento, Telefono, Email, Direccion, Rol, Num_SS, Fecha_contratacion FROM Empleados");
        $stmt->execute();
        $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($empleados)) {
            echo json_encode(["mensaje" => "No hay empleados registrados"]);
        } else {
            echo json_encode($empleados);
        }
    }

    // GET EMPLEADOS ACTIVOS/INACTIVOS
    public function getEmpleadosEstado ($estado_empleado) {
        $stmt = $this->conn->prepare("SELECT * FROM Empleados WHERE Estado_empleado = :estado");
        $stmt->bindParam(':estado', $estado_empleado, PDO::PARAM_STR);
    
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode($empleados);
        } else {
            echo json_encode(["mensaje" => "No se encontraron empleados con el estado indicado."]);
        }
    }


    // GET DE 1 EMPLEADO BY ID
    public function getEmpleadoById ($ID_empleado) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM empleados WHERE ID_empleado = :id");
            $stmt->bindParam(':id', $ID_empleado, PDO::PARAM_INT);
            $stmt->execute();
            $empleado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($empleado) {
                echo json_encode($empleado);
            } else {
                echo json_encode(["error" => "Empleado no encontrado segun ID"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener el empleado: " . $e->getMessage()]);
        }
    }

    //GET DE 1 EMPLEADO BY NUM INDENT
    public function getEmpleadoNombre ($num_ident) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM empleados WHERE Num_ident = :num_ident");
            $stmt->bindParam(':num_ident', $num_ident, PDO::PARAM_INT);
            $stmt->execute();
            $empleado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($empleado) {
                echo json_encode($empleado);
            } else {
                echo json_encode(["error" => "Empleado no encontrado segun nombre"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener el empleado: " . $e->getMessage()]);
        }
    }

    // GET OLD PASSWORD (VERIFICACION)
    public function getOldPassword ($data, $Num_Ident) {
        $oldPassword = $data['PIN_empleado_old'];

        $sql = "SELECT COUNT(*) as total FROM empleados WHERE PIN_empleado = :oldPassword AND Num_ident = :NumIdent";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':oldPassword', $oldPassword);
        $stmt->bindParam(':NumIdent', $Num_Ident);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result > 0) {
            http_response_code(200);
            echo json_encode(["success" => true, "message" => "Contraseña válida"]);
        } else {
            http_response_code(401);
            echo json_encode(["success" => false, "message" => "Contraseña incorrecta"]);
        }
        exit;
 

    }

    //GET CAMPOS
    public function getCamposEmpleado () {
        $tableName = "empleados";
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

    // LOGIN EMPLEADO (GET) 
    public function loginEmpleado($data) {

        if (!isset($data['Num_ident'], $data['PIN'])) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Faltan datos para login"]);
            exit;
        }
    
        try {
            $query = "SELECT PIN_empleado, Rol FROM empleados WHERE Num_ident = :Num_ident";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":Num_ident", $data["Num_ident"]);
            $stmt->execute();
    
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $passwordmd5= md5($data['PIN']);

            if ($passwordmd5 == $user['PIN_empleado']) {
                header('Content-Type: application/json');
                echo json_encode(["mensaje" => 
                                  "Login Correcto", 
                                  "DNI"=>$data["Num_ident"],
                                  "Rol"=>$user["Rol"]  
                                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(["error" => "Usuario o contraseña incorrectos",
                                    "Usuario"=>$data['Num_ident'],
                                    "PIN"=> $user['PIN_empleado'],
                                    "PIN DATA MD5"=> $passwordmd5
                ]);
            }
    
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Error al iniciar sesión: " . $e->getMessage()]);
        }
    }

    // INSERT DE EMPLEADO
    public function addEmpleado(){
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            echo json_encode(["error" => "No se recibieron datos para nuevo empleado"]);
            return;
        }

        $sql = "INSERT INTO Empleados (Num_ident, Nombre, Apellidos, Nacionalidad, Fecha_nacimiento, Telefono, Email, Direccion, Rol, Num_SS, Fecha_contratacion)
                VALUES (:num, :nombre, :apellidos, :nacionalidad, :fecha_nacimiento, :telefono, :email, :direccion, :rol, :num_ss, :fecha_contratacion)";

        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute([
                ":num" => $data['Num_ident'],
                ":nombre" => $data['Nombre'],
                ":apellidos" => $data['Apellidos'],
                ":nacionalidad" => $data['Nacionalidad'] ?? null,
                ":fecha_nacimiento" => $data['Fecha_nacimiento'] ?? null,
                ":telefono" => $data['Telefono'] ?? null,
                ":email" => $data['Email'] ?? null,
                ":direccion" => $data['Direccion'] ?? null,
                ":rol" => $data['Rol'],
                ":num_ss" => $data['Num_SS'] ?? null,
                ":fecha_contratacion" => $data['Fecha_contratacion']
            ]);

            echo json_encode(["mensaje" => "Empleado registrado con éxito"]);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al registrar empleado: " . $e->getMessage()]);
        }
    }

    // EDIT EMPLEADO
    public function editEmpleado ($data) {

        if (!isset($data['ID_empleado'])) {
            echo json_encode(["error" => "Datos incompletos"]);
            return;
        }
    
        try {
            // Verificar si el empleado existe antes de actualizar
            $stmt = $this->conn->prepare("SELECT ID_empleado FROM Empleados WHERE ID_empleado = :ID_empleado");
            $stmt->bindParam(':ID_empleado', $data['ID_empleado'], PDO::PARAM_INT);
            $stmt->execute();
    
            if ($stmt->rowCount() == 0) {
                echo json_encode(["error" => "Empleado no encontrado"]);
                return;
            }
    
            // Consulta SQL para actualizar los datos del empleado
            $sql = "UPDATE Empleados SET  Num_ident = :Num_ident, Nombre = :nombre, 
                    Apellidos = :apellidos, 
                    Nacionalidad = :nacionalidad, 
                    Fecha_nacimiento = :fecha_nacimiento, 
                    Telefono = :telefono, 
                    Email = :email, 
                    Direccion = :direccion, 
                    Rol = :rol, 
                    Num_SS = :num_ss
                WHERE ID_empleado = :ID_empleado";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ":Num_ident" => $data['Num_ident'],
                ":nombre" => $data['Nombre'],
                ":apellidos" => $data['Apellidos'],
                ":nacionalidad" => $data['Nacionalidad'] ?? null,
                ":fecha_nacimiento" => $data['Fecha_nacimiento'] ?? null,
                ":telefono" => $data['Telefono'] ?? null,
                ":email" => $data['Email'] ?? null,
                ":direccion" => $data['Direccion'] ?? null,
                ":rol" => $data['Rol'],
                ":num_ss" => $data['Num_SS'] ?? null,
                ":ID_empleado" => $data['ID_empleado']
            ]);
    
            echo json_encode(["mensaje" => "Empleado actualizado correctamente"]);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al actualizar el empleado: " . $e->getMessage()]);
        }
        

    }

    //PATCH ESTADO EMPLEADO
    public function editEstadoEmpleado($data) {
        if (!isset($data['ID_empleado']) || !isset($data['Estado_empleado'])) {
            echo json_encode(["error" => "Faltan parámetros necesarios."]);
            return;
        }
    
        $sql = "UPDATE Empleados SET Estado_empleado = :Estado_empleado WHERE ID_empleado = :ID_empleado";
    
        $stmt = $this->conn->prepare($sql);
    
        $stmt->bindParam(':Estado_empleado', $data['Estado_empleado'], PDO::PARAM_STR);
        $stmt->bindParam(':ID_empleado', $data['ID_empleado'], PDO::PARAM_STR);
    
        if ($stmt->execute()) {
            echo json_encode(["mensaje" => "Estado del empleado actualizado con éxito."]);
        } else {
            echo json_encode(["error" => "No se pudo actualizar el estado del empleado."]);
        }
    }

    //PATCH PASSWORD EMPLEADO
    public function editPasswordEmpleado ($data) {
        if (!isset($data['ID_empleado']) || !isset($data['password_empleado'])) {
            echo json_encode(["error" => "Faltan parámetros necesarios."]);
            return;
        }
    
        $sql = "UPDATE Empleados SET PIN_empleado = :password_empleado WHERE ID_empleado = :ID_empleado";
    
        $stmt = $this->conn->prepare($sql);
    
        $stmt->bindParam(':password_empleado', $data['password_empleado'], PDO::PARAM_STR);
        $stmt->bindParam(':ID_empleado', $data['ID_empleado'], PDO::PARAM_STR);
    
        if ($stmt->execute()) {
            echo json_encode(["mensaje" => "Contraseña del empleado actualizado con éxito."]);
        } else {
            echo json_encode(["error" => "No se pudo actualizar la contraseña del empleado."]);
        }
    }
}
?>
