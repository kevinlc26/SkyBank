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
        $stmt = $this->conn->prepare("SELECT t.ID_tarjeta, t.ID_cuenta, CONCAT(c.Nombre, ' ', c.Apellidos) AS Titular, t.Tipo_tarjeta, t.Estado_tarjeta, t.Fecha_caducidad, t.Limite_operativo FROM Tarjetas t
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

    }

    //EDIT ESTADO TARJETA

    public function editEstadoTarjeta($data) {
        if (!isset($data['ID_tarjeta']) || !isset($data['Estado_tarjeta'])) {
            echo json_encode(["error" => "Faltan parámetros necesarios."]);
            return;
        }
    
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
}
?>