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

}

?>