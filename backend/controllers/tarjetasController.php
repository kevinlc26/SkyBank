<?php
require_once  __DIR__ . '/../config/conn.php';

class TarjetasController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    //GET TODAS LAS TARJETAS
    public function getTarjetas() {
        $stmt = $this->conn->prepare("SELECT t.ID_tarjeta, t.ID_cuenta, CONCAT(c.Nombre, ' ', c.Apellidos) AS Titular, t.Tipo_tarjeta, t.Fecha_caducidad, t.Limite_operativo, t.Estado_tarjeta FROM Tarjetas t
                                            JOIN Cliente_Cuenta cc ON cc.ID_cuenta = t.ID_cuenta
                                            JOIN Clientes c ON c.ID_cliente = cc.ID_cliente
                                            ORDER BY Titular");
        $stmt->execute();
        $tarjetas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($tarjetas)) {
            echo json_encode(["mensaje" => "No hay tarjetas registradas"]);
        } else {
            echo json_encode($tarjetas);
        }
    }

    //GET 1 TARJETA
    public function getTarjetaById ($ID_tarjeta) {
        $ID_tarjeta = trim($ID_tarjeta);
        try {
            $stmt = $this->conn->prepare("SELECT t.ID_tarjeta, t.ID_cuenta, CONCAT(c.Nombre, ' ', c.Apellidos) AS Titular, t.Tipo_tarjeta, t.Estado_tarjeta, t.Fecha_caducidad, t.Limite_operativo FROM Tarjetas t
                                                    JOIN Cliente_Cuenta cc ON cc.ID_cuenta = t.ID_cuenta
                                                    JOIN Clientes c ON c.ID_cliente = cc.ID_cliente
                                                    WHERE ID_tarjeta = :id");
            $stmt->bindParam(':id', $ID_tarjeta, PDO::PARAM_STR);
            $stmt->execute();
            $tarjeta = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($tarjeta) {
                echo json_encode($tarjeta);
            } else {
                //echo json_encode(["error" => "Tarjeta no encontrado"]);
                echo json_encode(var_dump($tarjeta));
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener la tarjeta: " . $e->getMessage()]);
        }
    }

    //GET CAMPOS
    public function getCamposTarjeta () {
        $tableName = "tarjetas";
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

    // GET DETALLES TARJETA PARA EMPLEADO
    public function getTarjetaByIdDetalle($ID_tarjeta) {
        try {    
            $sql = "SELECT t.Tipo_tarjeta, t.Estado_tarjeta, t.Fecha_caducidad, t.Limite_operativo, c.ID_cuenta, cli.ID_cliente, cli.Num_ident, CONCAT(cli.Nombre, ' ', cli.Apellidos) AS Titular
                FROM tarjetas t
                JOIN cuentas c ON t.ID_cuenta = c.ID_cuenta
                JOIN cliente_cuenta cc ON c.ID_cuenta = cc.ID_cuenta
                JOIN clientes cli ON cc.ID_cliente = cli.ID_cliente
                WHERE t.ID_tarjeta = :ID_tarjeta";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':ID_tarjeta', $ID_tarjeta);
            $stmt->execute();
            $rawData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($rawData)) {
                echo json_encode(["error" => "La tarjeta no se encuentra en la base de datos"]);
                return;
            }

            $tarjeta = null;
            $titulares = [];
            $cuentas = [];

            foreach ($rawData as $row) {
                if (!$tarjeta) {
                    $tarjeta = [
                        'ID_tarjeta' => $ID_tarjeta,  
                        'Tipo_tarjeta' => $row['Tipo_tarjeta'],
                        'Estado_tarjeta' => $row['Estado_tarjeta'],
                        'Fecha_caducidad' => $row['Fecha_caducidad'],
                        'Limite_operativo' => $row['Limite_operativo'],
                        'cuentas_asociadas' => [],
                        'titulares' => []  
                    ];
                }
                $cuenta = [
                    'ID_cuenta' => $row['ID_cuenta'],
                    'Titular' => $row['ID_cuenta']
                ];
        
                if (!isset($cuentas[$row['ID_cuenta']])) {
                    $cuentas[$row['ID_cuenta']] = $cuenta;
                }

                $titular = [
                    'ID_cliente' => $row['ID_cliente'],
                    'Num_ident' => $row['Num_ident'],
                    'Titular' => $row['Titular']
                ];

                if (!isset($titulares[$row['ID_cliente']])) {
                    $titulares[$row['ID_cliente']] = $titular;
                }
            }

            $tarjeta['titulares'] = array_values($titulares); 
            $tarjeta['cuentas_asociadas'] = array_values($cuentas); 

            
            echo json_encode($tarjeta);
            
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener los detalles de la tarjeta: " . $e->getMessage()]);
        }
    }

    //ADD TARJETA
    public function addTarjeta () {
        
        $data = json_decode(file_get_contents("php://input"), true);
       
        if (!$data) {
            echo json_encode(["error" => "No se recibieron datos"]);
            return;
        }

        $ID_tarjeta = $data['ID'] ?? null;
        $ID_cuenta = $data['ID_cuenta'] ?? null;
        $Tipo_tarjeta = $data['Tipo_tarjeta'] ?? null;
        $Limite_operativo = $data['Limite_operativo'] ?? null;

        if (!$ID_tarjeta || !$ID_cuenta || !$Tipo_tarjeta || !$Limite_operativo) {
            echo json_encode(["error" => "Faltan datos requeridos"]);
            return;
        }

        $sql = "INSERT INTO Tarjetas (ID_tarjeta, ID_cuenta, Tipo_tarjeta, Limite_operativo)
                VALUES (:ID_tarjeta, :ID_cuenta, :Tipo_tarjeta, :Limite_operativo)";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':ID_tarjeta', $ID_tarjeta);
            $stmt->bindParam(':ID_cuenta', $ID_cuenta);
            $stmt->bindParam(':Tipo_tarjeta', $Tipo_tarjeta);
            $stmt->bindParam(':Limite_operativo', $Limite_operativo);

            // Ejecutar la sentencia
            $stmt->execute();

            // Responder con éxito
            echo json_encode(["mensaje" => "Tarjeta añadida con éxito", "ID_tarjeta" => $ID_tarjeta]);
        } catch (Exception $e) {
            echo json_encode(["error" => "Error al insertar la tarjeta: " . $e->getMessage()]);
        }
    }

    //EDIT TARJETA
    public function editTarjeta ($data) {
        $data = json_decode(file_get_contents("php://input"), true);
       
        if (!$data) {
            echo json_encode(["error" => "No se recibieron datos"]);
            return;
        }

        $sql = "UPDATE tarjetas SET Tipo_tarjeta = :Tipo_tarjeta, Fecha_caducidad = :Fecha_caducidad, Limite_operativo = :Limite_operativo WHERE ID_tarjeta = :ID_tarjeta";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':Tipo_tarjeta', $data['Tipo_tarjeta']);
            $stmt->bindParam(':Fecha_caducidad', $data['Fecha_caducidad']);
            $stmt->bindParam(':Limite_operativo', $data['Limite_operativo']);
            $stmt->bindParam(':ID_tarjeta', $data['id']);
    
            $stmt->execute();

            echo json_encode(["mensaje" => "Tarjeta editada con éxito", "ID_tarjeta" => $data['ID_tarjeta']]);
        } catch (Exception $e) {
            echo json_encode(["error" => "Error al editar la tarjeta: " . $e->getMessage()]);
        }
    }

    //PATCH ESTADO TARJETA
    public function editEstadoTarjeta($data) {
        if (!isset($data['ID_tarjeta']) || !isset($data['Estado_tarjeta'])) {
            echo json_encode(["error" => "Faltan parámetros necesarios."]);
            return;
        }
    
        // VERIFICAR QUE LA CUENTA ASOCIADA ESTE ACTIVA
        $sqlCuenta = "SELECT C.Estado_cuenta FROM Tarjetas T JOIN Cuentas C ON T.ID_cuenta = C.ID_cuenta WHERE T.ID_tarjeta = :ID_tarjeta";

        $stmtCuenta = $this->conn->prepare($sqlCuenta);
        $stmtCuenta->bindParam(':ID_tarjeta', $data['ID_tarjeta'], PDO::PARAM_STR);

        if ($stmtCuenta->execute()) {
            $row = $stmtCuenta->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $estadoCuenta = $row['Estado_cuenta'];

                if ($estadoCuenta === 'Inactiva') {
                    echo json_encode(["error" => "No se puede cambiar el estado de la tarjeta porque la cuenta asociada está inactiva."]);
                    return;
                }
            } else {
                echo json_encode(["error" => "No se encontró la tarjeta o la cuenta asociada."]);
                return;
            }
        } else {
            echo json_encode(["error" => "No se pudo obtener el estado de la cuenta asociada."]);
            return;
        }

        // ACTIVAR SI SE PUEDE
        $sql = "UPDATE Tarjetas SET Estado_tarjeta = :Estado_tarjeta WHERE ID_tarjeta = :ID_tarjeta";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':Estado_tarjeta', $data['Estado_tarjeta'], PDO::PARAM_STR);
        $stmt->bindParam(':ID_tarjeta', $data['ID_tarjeta'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo json_encode(["mensaje" => "Estado de tarjeta actualizado con éxito."]);
        } else {
            echo json_encode(["error" => "No se pudo actualizar el estado de la tarjeta."]);
        }
    }

    public function getTarjetasCliente($ID_cliente){
        if (!isset($ID_cliente)){
            echo json_encode(["error" => "No se recibieron datos"]);
            return;
        }
    
        $sql = "SELECT 
                    t.ID_tarjeta,
                    t.Tipo_tarjeta
                FROM Clientes cl
                JOIN Cliente_Cuenta cc ON cl.ID_cliente = cc.ID_cliente
                JOIN Cuentas c ON cc.ID_cuenta = c.ID_cuenta
                JOIN Tarjetas t ON c.ID_cuenta = t.ID_cuenta
                WHERE (t.Estado_tarjeta = 'Activa' OR t.Estado_tarjeta = 'Inactiva') AND cl.ID_cliente = ?";
    
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$ID_cliente]);

    
            $tarjetas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            if ($tarjetas) {
                echo json_encode($tarjetas);
            } else {
                echo json_encode(["mensaje" => "No tienes tarjetas dadas de alta en este momento."]);
            }
    
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
        }
    }
    public function detallesTarjeta($ID_tarjeta){
        if (!isset($ID_tarjeta)){
            echo json_encode(["error" => "No se recibieron datos"]);
            return;
        }
    
        $sql = "SELECT 
                t.ID_tarjeta,
                t.Tipo_tarjeta,
                t.Estado_tarjeta,
                t.Fecha_caducidad,
                t.Limite_operativo,
                c.ID_cuenta
            FROM Clientes cl
            JOIN Cliente_Cuenta cc ON cl.ID_cliente = cc.ID_cliente
            JOIN Cuentas c ON cc.ID_cuenta = c.ID_cuenta
            JOIN Tarjetas t ON c.ID_cuenta = t.ID_cuenta
            WHERE t.ID_tarjeta = ?";
    
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$ID_tarjeta]);

    
            $tarjetas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            if ($tarjetas) {
                echo json_encode($tarjetas);
            } else {
                echo json_encode(["mensaje" => "No se encontraron datos para esta tarjetas"]);
            }
    
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
        }
    }

    public function getLimitesOperaciones($ID_tarjeta){
        if (!isset($ID_tarjeta)){
            echo json_encode(["error" => "No se recibieron datos"]);
            return;
        }
    
        $sql = "SELECT 
                Limite_operativo,
                Limite_Mensual, 
                Compras_online,
                Compras_internacional
            FROM  Tarjetas 
            WHERE ID_tarjeta = ?";
    
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$ID_tarjeta]);

    
            $tarjetas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            if ($tarjetas) {
                echo json_encode($tarjetas);
            } else {
                echo json_encode(["mensaje" => "No se encontraron datos para esta tarjetas"]);
            }
    
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
        }
    }


    public function editLimitesOperaciones($data) {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            !isset($data["Limite_operativo"]) ||
            !isset($data["Limite_Mensual"]) ||
            !isset($data["Compras_online"]) ||
            !isset($data["Compras_internacional"]) ||
            !isset($data["ID_tarjeta"])
        ) {
            echo json_encode(["error" => "Faltan campos obligatorios"]);
            return;
        }
    
        $sql = "UPDATE TARJETAS 
                SET Limite_operativo = ?, 
                    Limite_Mensual = ?, 
                    Compras_online = ?, 
                    Compras_internacionales = ? 
                WHERE ID_tarjeta = ?";
    
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data["Limite_operativo"],
                $data["Limite_Mensual"],
                $data["Compras_online"],
                $data["Compras_internacional"],
                $data["ID_tarjeta"]
            ]);
    
            if ($stmt->rowCount() > 0) {
                echo json_encode(["mensaje" => "Límites de tarjeta actualizados correctamente"]);
            } else {
                echo json_encode(["mensaje" => "No se realizaron cambios o ID no válido"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
        }
    }
    
    public function editPINTarjeta($data){
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data["ID_tarjeta"]) || !isset($data["nuevoPin"]) ) {
            echo json_encode(["error" => "Faltan campos obligatorios"]);
            return;
        }
        $sql="UPDATE Tarjetas SET PIN =? WHERE ID_tarjeta=?";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data["nuevoPin"],
                $data["ID_tarjeta"]
            ]);
    
            if ($stmt->rowCount() > 0) {
                echo json_encode(["mensaje" => "PIN de tarjeta actualizado correctamente"]);
            } else {
                echo json_encode(["mensaje" => "No se realizaron cambios o ID no válido"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
        }
    }
    public function getPINTarjeta($ID_tarjeta){
        if (!isset($ID_tarjeta)){
            echo json_encode(["error" => "No se recibieron datos"]);
            return;
        }
        $sql="SELECT PIN FROM Tarjetas WHERE ID_Tarjeta =?";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$ID_tarjeta]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(["mensaje" => "No se encontró PIN para la tarjeta"]);
            }
    
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
        }
    }    
}
?>