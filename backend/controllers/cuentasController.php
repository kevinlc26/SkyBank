<?php
require_once ('../config/conn.php');

class cuentasController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }
    public function CuentasInicio($data){
        // Verificar si el DNI está presente en los datos
        if (!isset($data['DNI'])){
            header('Content-Type: application/json');
            echo json_encode(["error" => "Faltan datos obligatorios"]);
            exit;
        }
    
        try {
            $sql = "SELECT c.ID_cuenta, c.Saldo 
                    FROM Cuentas c
                    JOIN Cliente_Cuenta cc ON c.ID_cuenta = cc.ID_cuenta 
                    WHERE cc.ID_cliente = (SELECT ID_cliente FROM Clientes WHERE Num_ident = ?)";
    
            $stmt = $this->conn->prepare($sql);
            
            $stmt->execute([$data['DNI']]);
    
            if ($stmt->rowCount() > 0) {
                $cuentas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                header('Content-Type: application/json');
                echo json_encode($cuentas);
            } else {
                header('Content-Type: application/json');
                echo json_encode(["error" => "No se encontraron cuentas para el DNI proporcionado"]);
            }
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Error en la consulta: " . $e->getMessage()]);
        }
    }
    public function verCuentas($data){
        if (!isset($data['ID_cliente_cuentas'])){
            header('Content-Type: application/json');
            echo json_encode(["error" => "Faltan datos obligatorios"]);
            exit;
        }
        try{
            $sql="SELECT c.ID_cuenta, c.Tipo_cuenta, c.Saldo 
                    FROM cuentas c
                    JOIN cliente_cuenta cc ON c.ID_cuenta = cc.ID_cuenta
                    WHERE cc.ID_cliente = ?
                ";
                $stmt = $this->conn->prepare($sql);
            
                $stmt->execute([$data['ID_cliente_cuentas']]);
        
                if ($stmt->rowCount() > 0) {
                    $cuentas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    header('Content-Type: application/json');
                    echo json_encode($cuentas);
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(["error" => "No se encontraron cuentas para el ID_cliente proporcionado"]);
                }
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Error en la consulta: " . $e->getMessage()]);
        } 
    }


    public function DetallesCuenta($data){
        if (!isset($data['ID_cuentaDetalle'])) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Faltan datos obligatorios"]);
            exit;
        }
        try{
            $sql="SELECT 
                cl.Nombre AS Nombre_Cliente, 
                cl.Apellidos as Apellidos_Cliente,
                c.Saldo AS Saldo_Cuenta, 
                c.ID_cuenta, 
                c.Tipo_cuenta
            FROM Cuentas c
            JOIN Cliente_Cuenta cc ON c.ID_cuenta = cc.ID_cuenta
            JOIN Clientes cl ON cc.ID_cliente = cl.ID_cliente
            WHERE c.ID_cuenta = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$data['ID_cuentaDetalle']]);
    
            if ($stmt->rowCount() > 0) {
                $movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                header('Content-Type: application/json');
                echo json_encode($movimientos);
            } else {
                header('Content-Type: application/json');
                echo json_encode(["mensaje" => "No hay movimientos disponibles"]);
            }
    
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Error en la consulta: " . $e->getMessage()]);
        }
    }

    // GET CUENTA BY ID PARA EMPRESA
    public function getCuentaByIdEdit($ID_cuenta) {

        try {
            $sql = "SELECT CONCAT(cli.Nombre, ' ', cli.Apellidos) AS Titular, c.Estado_cuenta FROM cuentas c
                    JOIN cliente_cuenta cc ON c.ID_cuenta = cc.ID_cuenta
                    JOIN clientes cli ON cc.ID_cliente = cli.ID_cliente
                    WHERE c.ID_cuenta = ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$ID_cuenta]);
    
            $cuenta = $stmt->fetch(PDO::FETCH_ASSOC);

            header('Content-Type: application/json');
            echo json_encode($cuenta);

        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Error en la consulta: " . $e->getMessage()]);
            exit;
        }
    }

    //GET TODAS LAS CUENTAS
    public function getCuentas() {
        try {
            $sql = "SELECT c.ID_cuenta, CONCAT(cli.Nombre, ' ', cli.Apellidos) AS Titular, 
                           c.Tipo_cuenta, c.Saldo, c.Estado_cuenta, c.Fecha_creacion 
                    FROM cuentas c
                    JOIN cliente_cuenta cc ON c.ID_cuenta = cc.ID_cuenta
                    JOIN clientes cli ON cc.ID_cliente = cli.ID_cliente";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            $cuentas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            header('Content-Type: application/json');
            echo json_encode($cuentas);
            exit;
    
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Error en la consulta: " . $e->getMessage()]);
            exit;
        }
    }
    

    //GET CUENTAS SEGUN CLIENTE
    public function getCuentasIdCliente($data) {
        try {
            if (!isset($data['ID_cliente'])) {
                header('Content-Type: application/json');
                echo json_encode(["error" => "Faltan datos obligatorios"]);
                exit;
            }
    
            $sql = "SELECT ID_cuenta FROM cliente_cuenta WHERE ID_cliente = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$data['ID_cliente']]);
    
            $cuentas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            header('Content-Type: application/json');
            echo json_encode($cuentas);
            exit;
    
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Error en la consulta: " . $e->getMessage()]);
            exit;
        }
    }
    
    // GET CAMPOS DE LA TABLA CUENTAS
    public function getCamposCuentas() {
        $tableName = "cuentas";
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

    // ADD CUENTA
    public function addCuenta($data) {
        $ID_cuenta = $data['ID'];
        $ID_cliente = $data['ID_cliente'];
        $Tipo_cuenta = $data['Tipo_cuenta'];

        try {
            $this->conn->beginTransaction(); // se usa para hacer los inserts a la vez
    
            $sql = "INSERT INTO cuentas (ID_cuenta, Tipo_cuenta) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$ID_cuenta, $Tipo_cuenta]);
    
            $sql2 = "INSERT INTO cliente_cuenta (ID_cliente, ID_cuenta) VALUES (?, ?)";
            $stmt2 = $this->conn->prepare($sql2);
            $stmt2->execute([$ID_cliente, $ID_cuenta]);
    
            $this->conn->commit(); // confirmar la transaction
    
             echo json_encode(["mensaje" => "Cuenta creada y asociada correctamente"]);
        } catch (PDOException $e) {
            $this->conn->rollBack(); // si hay un error se tira para atrás
            echo json_encode(["error" => "Error al crear la cuenta: " . $e->getMessage()]);
        }
    }
}

?>