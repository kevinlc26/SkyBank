<?php
require_once ('../config/conn.php');

class transferenciasController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }
    

    // GET TODAS 
    public function getTransferencias(){
        $tipo_movimiento = "Transferencia";

        $sql = "SELECT m.ID_movimiento, m.ID_cuenta_emisor, CONCAT(cli_emisor.Nombre, ' ', cli_emisor.Apellidos) AS Titular_Emisor, m.ID_cuenta_beneficiario, CONCAT(cli_benef.Nombre, ' ', cli_benef.Apellidos) AS Titular_Beneficiario, 
                m.Importe, m.Fecha_movimiento, m.Concepto, m.Estado FROM Movimientos m
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

    // PATCH ESTADO TRANSFERENCIA ("DELETE")
    public function editTransferenciaEstado($data) {

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

    public function traspasoEntreCuentas($data){
        if(!isset($data)){
            header('Content-Type: application/json');
            echo json_encode(["error" => "Faltan datos obligatorios"]);
            exit;
        }
        $id_cliente = $data['ID_cliente'];
        $cuenta_origen = $data['cuentaOrigen'];
        $cuenta_destino = $data['cuentaDestino'];
        $importe = $data['cantidad'];
        $descripcion = $data['Descripcion'];

        try {
            $stmt = $this->conn->prepare("CALL TraspasarEntreCuentas(:id_cliente, :cuenta_origen, :cuenta_destino, :importe, :descripcion)");
            $stmt->bindParam(':id_cliente', $id_cliente);
            $stmt->bindParam(':cuenta_origen', $cuenta_origen);
            $stmt->bindParam(':cuenta_destino', $cuenta_destino);
            $stmt->bindParam(':importe', $importe);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->execute();

            echo json_encode(["mensaje" => "Traspaso realizado correctamente"]);
        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public function transferencia($data){
        if(
            !isset($data['ID_cliente']) ||
            !isset($data['cuenta_origen']) ||
            !isset($data['cuenta_destino']) ||
            !isset($data['importe']) ||
            !isset($data['concepto'])
        ){
            header('Content-Type: application/json');
            echo json_encode(["error" => "Faltan datos obligatorios"]);
            exit;
        }
    
        try {
            $stmt = $this->conn->prepare("CALL TransferenciaACliente(:id_cliente, :cuenta_origen, :cuenta_destino, :importe, :concepto)");
            $stmt->bindParam(':id_cliente', $data['ID_cliente'], PDO::PARAM_INT);
            $stmt->bindParam(':cuenta_origen', $data['cuenta_origen'], PDO::PARAM_STR);
            $stmt->bindParam(':cuenta_destino', $data['cuenta_destino'], PDO::PARAM_STR);
            $stmt->bindParam(':importe', $data['importe'], PDO::PARAM_STR);
            $stmt->bindParam(':concepto', $data['concepto'], PDO::PARAM_STR);
    
            $stmt->execute();
    
            http_response_code(200);
            echo json_encode(["mensaje" => "Transferencia realizada correctamente"]);
    
        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
    
}
?>