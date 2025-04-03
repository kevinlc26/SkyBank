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
    public function RecibosCuenta($data){
        if (!isset($data['ID_cuenta'])) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Faltan datos obligatorios"]);
            exit;
        }
        try{
            $sql="SELECT DATE_FORMAT(Fecha_movimiento,'%Y-%m-%d %H:%i') AS Fecha_movimiento, Concepto, Importe, Estado from movimientos where Tipo_movimiento='Recibo' and ID_cuenta_beneficiario=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$data['ID_cuenta']]);
    
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

    public function DetallesCuenta($data){
        if (!isset($data['ID_cuenta'])) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Faltan datos obligatorios"]);
            exit;
        }
        try{
            $sql="SELECT 
                cl.Nombre AS Nombre_Cliente, 
                cl.Apellidos as Apellidos_Cliente,
                c.Saldo AS Saldo_Cuenta, 
                c.ID_cuenta 
            FROM Cuentas c
            JOIN Cliente_Cuenta cc ON c.ID_cuenta = cc.ID_cuenta
            JOIN Clientes cl ON cc.ID_cliente = cl.ID_cliente
            WHERE c.ID_cuenta = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$data['ID_cuenta']]);
    
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