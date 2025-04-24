<?php
require_once ('../config/conn.php');

class MovimientosController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getMovimientos(){

        try {
                    
            $sql = "SELECT m.*, CONCAT(cli_emisor.Nombre, ' ', cli_emisor.Apellidos) AS Titular_Emisor,
                    CONCAT(cli_benef.Nombre, ' ', cli_benef.Apellidos) AS Titular_Beneficiario FROM movimientos m
                LEFT JOIN cliente_cuenta cc_emisor ON m.ID_cuenta_emisor = cc_emisor.ID_cuenta
                LEFT JOIN clientes cli_emisor ON cc_emisor.ID_cliente = cli_emisor.ID_cliente
                LEFT JOIN cliente_cuenta cc_benef ON m.ID_cuenta_beneficiario = cc_benef.ID_cuenta
                LEFT JOIN clientes cli_benef ON cc_benef.ID_cliente = cli_benef.ID_cliente
                WHERE m.Tipo_movimiento != 'Transferencia';";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            $movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            header('Content-Type: application/json');
            echo json_encode($movimientos);

        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'Error al obtener movimientos: ' . $e->getMessage()
            ]);
        }

    }

    public function getMovimientoById($ID_movimiento){
        $ID_movimiento = $_GET['ID_movimiento'];

        try {
                    
            $sql = "SELECT m.*, CONCAT(cli_emisor.Nombre, ' ', cli_emisor.Apellidos) AS Titular_Emisor,
                    CONCAT(cli_benef.Nombre, ' ', cli_benef.Apellidos) AS Titular_Beneficiario FROM movimientos m
                LEFT JOIN cliente_cuenta cc_emisor ON m.ID_cuenta_emisor = cc_emisor.ID_cuenta
                LEFT JOIN clientes cli_emisor ON cc_emisor.ID_cliente = cli_emisor.ID_cliente
                LEFT JOIN cliente_cuenta cc_benef ON m.ID_cuenta_beneficiario = cc_benef.ID_cuenta
                LEFT JOIN clientes cli_benef ON cc_benef.ID_cliente = cli_benef.ID_cliente
                WHERE m.ID_movimiento = ? AND m.Tipo_movimiento != 'Transferencia';";

            $stmt = $this->conn->prepare($sql);
                    
            $stmt->execute([$ID_movimiento]);

            $movimiento = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($movimiento) {
                header('Content-Type: application/json');
                echo json_encode($movimiento);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    'error' => 'Movimiento no encontrado'
                ]);
            }

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Error al obtener movimientos: ' . $e->getMessage()
            ]);
        }

    }

    public function getCamposMovimientos() {
        $tableName = "movimientos";
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
    // SELECT DE LOS MOVIMIENTOS DE LA CUENTA A LA CUENTA DE AHORRO
    public function AhorroMovimientos($data){
        if (!isset($data['ID_cuenta-Ahorro'])) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Faltan datos obligatorios"]);
            exit;
        }
        try{
            $sql="SELECT DATE_FORMAT(Fecha_movimiento,'%Y-%m-%d %H:%i') AS Fecha_movimiento, Concepto, Importe, ID_movimiento, Saldo_nuevo from movimientos where Tipo_movimiento='Traspaso' and ID_cuenta_beneficiario=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$data['ID_cuenta-Ahorro']]);
    
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

    // SELECT DE LOS MOVIMIENTOS DE TIPO RECIBOS
    public function RecibosCuenta($data){
        if (!isset($data['ID_cuentaRecibos'])) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Faltan datos obligatorios"]);
            exit;
        }
        try{
            $sql="SELECT DATE_FORMAT(Fecha_movimiento,'%Y-%m-%d %H:%i') AS Fecha_movimiento, Concepto, Importe, Estado, ID_movimiento from movimientos where Tipo_movimiento='Recibo' and ID_cuenta_beneficiario=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$data['ID_cuentaRecibos']]);
    
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
    public function bloqDesbloqRecibo($data){

        if (!isset($data['ID_cuenta']) || !isset($data['ID_movimiento']) || !isset($data['Estado'])) {
            echo json_encode(["error" => "Faltan datos obligatorios"]);
            exit;
        }
    
        try {
            if ($data['Estado'] == 'Activo') {
                $sql = "UPDATE movimientos SET Estado = 'Bloqueado' WHERE ID_movimiento = ?";
            } else {
                $sql = "UPDATE movimientos SET Estado = 'Activo' WHERE ID_movimiento = ?";
            }
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$data['ID_movimiento']]);
    
            if ($stmt->rowCount() > 0) {
                echo json_encode(["success" => "Estado actualizado correctamente"]);
            } else {
                echo json_encode(["warning" => "No se realizó ningún cambio"]);
            }
    
        } catch (Exception $e) {
            echo json_encode(["error" => "Error en la consulta: " . $e->getMessage()]);
        }
    }

    // SELECT MOVIMIENTOS INICO DE LA CUENTA
    public function Movimientos($data) {
        if (!isset($data['ID_cuenta'])) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Faltan datos obligatorios"]);
            exit;
        }
    
        try {
            $sql = "SELECT 
                        m.ID_movimiento, 
                        DATE_FORMAT(m.Fecha_movimiento,'%Y-%m-%d %H:%i') AS Fecha_movimiento, 
                        m.Importe,
                        m.Concepto, 
                        (c.Saldo 
                            + CASE 
                                WHEN m.Tipo_movimiento = 'Ingreso' THEN m.Importe 
                                WHEN m.Tipo_movimiento = 'Transferencia' AND m.ID_cuenta_emisor = c.ID_cuenta THEN -m.Importe
                                WHEN m.Tipo_movimiento = 'Transferencia' AND m.ID_cuenta_beneficiario = c.ID_cuenta THEN m.Importe
                                WHEN m.Tipo_movimiento IN ('Pago Tarjeta', 'Cobro', 'Comisión') THEN -m.Importe 
                                ELSE 0 
                              END
                        ) AS Saldo_Post_Movimiento
                    FROM Movimientos m
                    JOIN Cuentas c ON c.ID_cuenta = m.ID_cuenta_emisor
                    WHERE c.ID_cuenta = ?
                    ORDER BY m.Fecha_movimiento ASC";
    
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$data['ID_cuenta']]); // Ejecutamos con ID_cuenta como parámetro
    
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

}

?>