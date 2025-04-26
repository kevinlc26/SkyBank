<?php
require_once ('../config/conn.php');

class transferenciasController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }
    
   
    private function saldoActual($data){
    
        if (!isset($data['ID_cuenta'])) {
            echo json_encode(["error" => "Faltan datos obligatorios"]);
            exit;
        }
    
        try {
            $sql = "SELECT Saldo FROM cuentas WHERE ID_cuenta = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$data['ID_cuenta']]);
    
            $resultado = $stmt->conn;
    
            if ($resultado) {
                return $resultado['Saldo'];
            } else {
                return "Cuenta no encontrada";
            }
    
        } catch (Exception $e) {
            echo json_encode(["error" => "Error en la consulta: " . $e->getMessage()]);
        }
    }
    public function insertTraspaso($data){
        if (!isset($data['ID_cuenta_Origen'])|| !isset($data['ID_cuenta_Destino']) || !isset($data['Cantidad']) || !isset($data['Descripción'])) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Faltan datos obligatorios"]);
            exit;
        }
        try{
            $sql="INSERT INTO movimientos ('ID_cuenta_emisor, ID_cuenta_beneficiario, Importe, Concepto) VALUES (?, ?, ?, ?)";
        
        } catch (Exception $e) {
            echo json_encode(["error" => "Error en la consulta: " . $e->getMessage()]);
        }
    }
    private function updateSaldoNuevo($data){
        if (!isset($data['ID_movimiento']) || !isset($data['Importe']) || !isset($data['ID_cuenta'])){
            header('Content-Type: application/json');
            echo json_encode(["error" => "Faltan datos obligatorios"]);
            exit;
        }
        try{
            //$SaldoActual= saldoActual($data['ID_cuenta']);
            //$saldoNuevo=$SaldoActual+$data['Importe'];
            $sql="UPDATE movimientos SET Saldo_nuevo WHERE ID_movimiento=?";

        } catch (Exception $e) {
            echo json_encode(["error" => "Error en la consulta: " . $e->getMessage()]);
        }
    }

    // GET TODAS 
    public function getTransferencias(){
        $tipo_movimiento = "Transferencia";

        $sql = "SELECT m.ID_movimiento, m.ID_cuenta_emisor, CONCAT(cli_emisor.Nombre, ' ', cli_emisor.Apellidos) AS Titular_Emisor, m.ID_cuenta_beneficiario, CONCAT(cli_benef.Nombre, ' ', cli_benef.Apellidos) AS Titular_Beneficiario, 
                m.Importe, m.Estado, m.Fecha_movimiento, m.Concepto FROM Movimientos m
                LEFT JOIN cliente_cuenta cc_emisor ON m.ID_cuenta_emisor = cc_emisor.ID_cuenta
                LEFT JOIN clientes cli_emisor ON cc_emisor.ID_cliente = cli_emisor.ID_cliente
                LEFT JOIN cliente_cuenta cc_benef ON m.ID_cuenta_beneficiario = cc_benef.ID_cuenta
                LEFT JOIN clientes cli_benef ON cc_benef.ID_cliente = cli_benef.ID_cliente
                WHERE m.Tipo_Movimiento = ?";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$tipo_movimiento]);
    
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            echo json_encode($result);
        } catch (PDOException $e) {
            echo json_encode("Error al obtener transferencias: " . $e->getMessage());;
        }
    }

    //GET CAMPOS DE LA TABLA
    public function getCamposTransferencias() {
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

    // GET DATOS TRANSFER PARA EDIT

    public function getTransferenciaByIdEdit($ID_movimiento) {
        // decidir que campos se van a poder editar
    }

}
?>