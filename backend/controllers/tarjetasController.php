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
                                            JOIN Clientes c ON c.ID_cliente = cc.ID_cliente");
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
        try {
            $stmt = $this->conn->prepare("SELECT t.ID_tarjeta, t.ID_cuenta, CONCAT(c.Nombre, ' ', c.Apellidos) AS Titular, t.Tipo_tarjeta, t.Estado_tarjeta, t.Fecha_caducidad, t.Limite_operativo FROM tarjetas t
                                                    JOIN Cliente_Cuenta cc ON cc.ID_cuenta = t.ID_cuenta
                                                    JOIN Clientes c ON c.ID_cliente = cc.ID_cliente
                                                    WHERE ID_tarjeta = :id");
            $stmt->bindParam(':id', $ID_tarjeta, PDO::PARAM_INT);
            $stmt->execute();
            $tarjeta = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($tarjeta) {
                echo json_encode($tarjeta);
            } else {
                echo json_encode(["error" => "Tarjeta no encontrado"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener la tarjeta: " . $e->getMessage()]);
        }
    }

    //ADD TARJETA
    public function addTarjeta ($data) {

    }

    //EDIT TARJETA
    public function edittarjeta ($data) {

    }

    //DELETE TARJETA
    public function deleteTarjeta ($ID_tarjeta) {

    }
}
?>