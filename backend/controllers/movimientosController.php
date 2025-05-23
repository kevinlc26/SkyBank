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
                    
            $sql = "SELECT m.ID_movimiento,
                    m.ID_cuenta_emisor,
                    CONCAT(cli_emisor.Nombre, ' ', cli_emisor.Apellidos) AS Titular_Emisor,
                    cli_emisor.Num_ident AS Num_ident_Emisor,
                    m.ID_cuenta_beneficiario,
                    CONCAT(cli_benef.Nombre, ' ', cli_benef.Apellidos) AS Titular_Beneficiario,
                    cli_benef.Num_ident AS Num_ident_Beneficiario,
                    m.ID_tarjeta,
                    m.Tipo_movimiento,
                    m.Importe,
                    m.Fecha_movimiento,
                    m.Concepto,
                    m.Estado
                FROM movimientos m
                LEFT JOIN cliente_cuenta cc_emisor ON m.ID_cuenta_emisor = cc_emisor.ID_cuenta
                LEFT JOIN clientes cli_emisor ON cc_emisor.ID_cliente = cli_emisor.ID_cliente
                LEFT JOIN cliente_cuenta cc_benef ON m.ID_cuenta_beneficiario = cc_benef.ID_cuenta
                LEFT JOIN clientes cli_benef ON cc_benef.ID_cliente = cli_benef.ID_cliente
                WHERE m.Tipo_movimiento != 'Transferencia'";

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
            $sql="SELECT DATE_FORMAT(Fecha_movimiento,'%Y-%m-%d %H:%i') AS Fecha_movimiento, Concepto, Importe, Estado, ID_movimiento from movimientos where Tipo_movimiento='Recibo' and ID_cuenta_emisor=?";
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

    // GET MOVIMIENTOS SEGUN CLIENTE
    public function getMovimientosByNumIdent($Num_ident) {

        $sql = "SELECT m.ID_movimiento, 
                   m.ID_cuenta_emisor, 
                   CONCAT(cli_emisor.Nombre, ' ', cli_emisor.Apellidos) AS Titular_Emisor,
                   cli_emisor.Num_ident AS Num_ident_Emisor,  
                   m.ID_cuenta_beneficiario, 
                   CONCAT(cli_benef.Nombre, ' ', cli_benef.Apellidos) AS Titular_Beneficiario,
                   cli_benef.Num_ident AS Num_ident_Beneficiario,  
                   m.ID_tarjeta, 
                   m.Tipo_movimiento, 
                   m.Importe, 
                   m.Fecha_movimiento, 
                   m.Concepto, 
                   m.Estado 
            FROM movimientos m
            LEFT JOIN cliente_cuenta cc_emisor ON m.ID_cuenta_emisor = cc_emisor.ID_cuenta
            LEFT JOIN clientes cli_emisor ON cc_emisor.ID_cliente = cli_emisor.ID_cliente
            LEFT JOIN cliente_cuenta cc_benef ON m.ID_cuenta_beneficiario = cc_benef.ID_cuenta
            LEFT JOIN clientes cli_benef ON cc_benef.ID_cliente = cli_benef.ID_cliente
            WHERE cli_emisor.Num_Ident = ? OR cli_benef.Num_Ident = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([$Num_ident, $Num_ident]);

        $movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($movimientos);
    }

    //GET MOVIMIENTOS SEGUN CUENTA
    public function getMovimientosByCuenta($ID_cuenta) {

        $sql = "SELECT m.ID_movimiento, 
                   m.ID_cuenta_emisor, 
                   CONCAT(cli_emisor.Nombre, ' ', cli_emisor.Apellidos) AS Titular_Emisor,
                   cli_emisor.Num_ident AS Num_ident_Emisor,  -- Agregado el Num_ident del emisor
                   m.ID_cuenta_beneficiario, 
                   CONCAT(cli_benef.Nombre, ' ', cli_benef.Apellidos) AS Titular_Beneficiario,
                   cli_benef.Num_ident AS Num_ident_Beneficiario,  -- Agregado el Num_ident del beneficiario
                   m.ID_tarjeta, 
                   m.Tipo_movimiento, 
                   m.Importe, 
                   m.Fecha_movimiento, 
                   m.Concepto, 
                   m.Estado 
            FROM movimientos m
            LEFT JOIN cliente_cuenta cc_emisor ON m.ID_cuenta_emisor = cc_emisor.ID_cuenta
            LEFT JOIN clientes cli_emisor ON cc_emisor.ID_cliente = cli_emisor.ID_cliente
            LEFT JOIN cliente_cuenta cc_benef ON m.ID_cuenta_beneficiario = cc_benef.ID_cuenta
            LEFT JOIN clientes cli_benef ON cc_benef.ID_cliente = cli_benef.ID_cliente
            WHERE m.ID_cuenta_emisor = ? OR m.ID_cuenta_beneficiario = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([$ID_cuenta, $ID_cuenta]);

        $movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($movimientos);
    }

    //GET MOVIMIENTOS SEGUN TARJETA
    public function getMovimientosByTarjeta($ID_tarjeta) {
        $sql = "SELECT m.ID_movimiento, 
                   m.ID_cuenta_emisor, 
                   CONCAT(cli_emisor.Nombre, ' ', cli_emisor.Apellidos) AS Titular_Emisor,
                   cli_emisor.Num_ident AS Num_ident_Emisor,  -- Agregado el Num_ident del emisor
                   m.ID_cuenta_beneficiario, 
                   CONCAT(cli_benef.Nombre, ' ', cli_benef.Apellidos) AS Titular_Beneficiario,
                   cli_benef.Num_ident AS Num_ident_Beneficiario,  -- Agregado el Num_ident del beneficiario
                   m.ID_tarjeta, 
                   m.Tipo_movimiento, 
                   m.Importe, 
                   m.Fecha_movimiento, 
                   m.Concepto, 
                   m.Estado 
            FROM movimientos m
            LEFT JOIN cliente_cuenta cc_emisor ON m.ID_cuenta_emisor = cc_emisor.ID_cuenta
            LEFT JOIN clientes cli_emisor ON cc_emisor.ID_cliente = cli_emisor.ID_cliente
            LEFT JOIN cliente_cuenta cc_benef ON m.ID_cuenta_beneficiario = cc_benef.ID_cuenta
            LEFT JOIN clientes cli_benef ON cc_benef.ID_cliente = cli_benef.ID_cliente
            WHERE m.ID_tarjeta = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([$ID_tarjeta]);

        $movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($movimientos);
    }


    // PATCH ESTADO MOVIMIENTO ("DELETE")
    public function editMovimientoEstado($data) {

        $ID_movimiento = $data['ID_movimiento'];
        $Estado = $data['Estado'];

        $sql = "UPDATE movimientos SET Estado = :Estado WHERE ID_movimiento = :ID_movimiento";
        $stmt = $this->conn->prepare($sql);

        if ($stmt) {
            if ($stmt->execute([
                ':Estado' => $Estado,
                ':ID_movimiento' => $ID_movimiento
            ])) {
                echo json_encode(["success" => true, "mensaje" => "Estado del movimiento actualizado correctamente."]);
            } else {
                echo json_encode(["success" => false, "error" => "Error al actualizar el estado del movimiento."]);
            }
        } else {
            echo json_encode(["success" => false, "error" => "Error en la preparación de la consulta."]);
        }
    }

    //OBTENER LAS TRANSFERENCIAS DE UNA CUENTA (RECIBIDAS Y ENVIADAS)

    public function obtenerTransferenciasCuenta($data){
        if(!isset($data['cuenta_ID-Traspasos'])){
            header('Content-Type: application/json');
            echo json_encode(["error" => "Faltan datos obligatorios."]);
            exit;
        }
    
        try {
            $cuentaLimpia =$data['cuenta_ID-Traspasos'];
    
            $sql = "SELECT 
                    m.ID_movimiento, 
                    m.Concepto, 
                    m.Importe, 
                    m.Tipo_movimiento,
                    DATE_FORMAT(m.Fecha_movimiento, '%Y-%m-%d %H:%i') AS Fecha_movimiento,
                    m.ID_cuenta_emisor,
                    m.ID_cuenta_beneficiario
                FROM Movimientos m
                WHERE m.Tipo_movimiento = 'Transferencia enviada'
                AND (:ID_cuenta = m.ID_cuenta_emisor OR :ID_cuenta = m.ID_cuenta_beneficiario)
                ORDER BY m.Fecha_movimiento ASC;
                ";
    
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':ID_cuenta', $cuentaLimpia, PDO::FETCH_ASSOC);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                $movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                header('Content-Type: application/json');
                echo json_encode($movimientos);
            } else {
                header('Content-Type: application/json');
                echo json_encode(["mensaje" => "No hay movimientos de transferencia disponibles."]);
            }
    
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Error en la consulta: " . $e->getMessage()]);
        }
    }    
}

?>