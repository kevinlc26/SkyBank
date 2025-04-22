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
}
?>