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
    
}
?>