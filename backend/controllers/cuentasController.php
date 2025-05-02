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
                WHERE cc.ID_cliente = (
                    SELECT ID_cliente 
                    FROM Clientes 
                    WHERE Num_ident = ?);";
    
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
    public function verCuentas($data){
        if (!isset($data['ID_cliente_cuentas'])){
            header('Content-Type: application/json');
            echo json_encode(["error" => "Faltan datos obligatorios"]);
            exit;
        }
        try{
            $sql="SELECT c.ID_cuenta, c.Tipo_cuenta, c.Saldo 
                    FROM cuentas c
                    JOIN cliente_cuenta cc ON c.ID_cuenta = cc.ID_cuenta
                    WHERE cc.ID_cliente = ?
                ";
                $stmt = $this->conn->prepare($sql);
            
                $stmt->execute([$data['ID_cliente_cuentas']]);
        
                if ($stmt->rowCount() > 0) {
                    $cuentas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    header('Content-Type: application/json');
                    echo json_encode($cuentas);
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(["error" => "No se encontraron cuentas para el ID_cliente proporcionado"]);
                }
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Error en la consulta: " . $e->getMessage()]);
        } 
    }


    public function DetallesCuenta($data){
        if (!isset($data['ID_cuentaDetalle'])) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Faltan datos obligatorios"]);
            exit;
        }
        try{
            $sql="SELECT 
                cl.Nombre AS Nombre_Cliente, 
                cl.Apellidos as Apellidos_Cliente,
                c.Saldo AS Saldo_Cuenta, 
                c.ID_cuenta, 
                c.Tipo_cuenta
            FROM Cuentas c
            JOIN Cliente_Cuenta cc ON c.ID_cuenta = cc.ID_cuenta
            JOIN Clientes cl ON cc.ID_cliente = cl.ID_cliente
            WHERE c.ID_cuenta = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$data['ID_cuentaDetalle']]);
    
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

    // GET CUENTA BY ID PARA EMPRESA
    public function getCuentaByIdEdit($ID_cuenta) {

        try {
            $sql = "SELECT CONCAT(cli.Nombre, ' ', cli.Apellidos) AS Titular, cc.ID_cliente FROM cuentas c
                    JOIN cliente_cuenta cc ON c.ID_cuenta = cc.ID_cuenta
                    JOIN clientes cli ON cc.ID_cliente = cli.ID_cliente
                    WHERE c.ID_cuenta = ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$ID_cuenta]);

            $titulares = $stmt->fetchAll(PDO::FETCH_ASSOC);

            header('Content-Type: application/json');
            echo json_encode($titulares);

            } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Error en la consulta: " . $e->getMessage()]);
            exit;
            }
    }

    //GET TODAS LAS CUENTAS
    public function getCuentas() {
        try {
            $sql = "SELECT c.ID_cuenta, GROUP_CONCAT(CONCAT(cli.Nombre, ' ', cli.Apellidos) SEPARATOR ', ') AS Titular, 
                           c.Tipo_cuenta, c.Saldo, c.Fecha_creacion, c.Estado_cuenta, cli.Num_ident
                    FROM cuentas c
                    JOIN cliente_cuenta cc ON c.ID_cuenta = cc.ID_cuenta
                    JOIN clientes cli ON cc.ID_cliente = cli.ID_cliente
                    GROUP BY c.ID_cuenta, c.Tipo_cuenta, c.Saldo, c.Estado_cuenta, c.Fecha_creacion;";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            $cuentas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            header('Content-Type: application/json');
            echo json_encode($cuentas);
            exit;
    
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Error en la consulta: " . $e->getMessage()]);
            exit;
        }
    }
    

    //GET CUENTAS SEGUN CLIENTE
    public function getCuentasIdCliente($data) {
        try {
            if (!isset($data['ID_cliente'])) {
                header('Content-Type: application/json');
                echo json_encode(["error" => "Faltan datos obligatorios"]);
                exit;
            }
    
            $sql = "SELECT ID_cuenta FROM cliente_cuenta WHERE ID_cliente = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$data['ID_cliente']]);
    
            $cuentas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            header('Content-Type: application/json');
            echo json_encode($cuentas);
            exit;
    
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Error en la consulta: " . $e->getMessage()]);
            exit;
        }
    }
    
    // GET CAMPOS DE LA TABLA CUENTAS
    public function getCamposCuentas() {
        $tableName = "cuentas";
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

    // GET DATOS CUENTA
    public function getDatosCuenta($ID_cuenta) {
        try {
            $sql = "SELECT c.Fecha_creacion, c.Tipo_cuenta, c.Saldo, c.Estado_cuenta, 
                    t.ID_tarjeta, cli.ID_cliente, cli.Num_ident, CONCAT(cli.Nombre, ' ', cli.Apellidos) AS Titular 
                FROM cuentas c
                JOIN cliente_cuenta cc ON c.ID_cuenta = cc.ID_cuenta
                JOIN clientes cli ON cc.ID_cliente = cli.ID_cliente
                LEFT JOIN tarjetas t ON c.ID_cuenta = t.ID_cuenta
                WHERE c.ID_cuenta = :ID_cuenta";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':ID_cuenta', $ID_cuenta);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($rows)) {
                echo json_encode(["error" => "La cuenta no se encuentra en la base de datos"]);
                return;
            }

            $cuenta = [
                "Fecha_creacion" => $rows[0]["Fecha_creacion"],
                "Tipo_cuenta" => $rows[0]["Tipo_cuenta"],
                "Saldo" => $rows[0]["Saldo"],
                "Estado_cuenta" => $rows[0]["Estado_cuenta"],
                "Titulares" => [],
                "Tarjetas" => []
            ];

            $titularesUnicos = [];
            $tarjetasUnicas = [];

            foreach ($rows as $row) {
                $idCliente = $row["ID_cliente"];
                if (!isset($titularesUnicos[$idCliente])) {
                    $titularesUnicos[$idCliente] = [
                        "ID_cliente" => $idCliente,
                        "Num_ident" => $row["Num_ident"],
                        "Titular" => $row["Titular"]
                    ];
                }

                if (!empty($row["ID_tarjeta"]) && !isset($tarjetasUnicas[$row["ID_tarjeta"]])) {
                    $tarjetasUnicas[$row["ID_tarjeta"]] = [
                        "Titular" => $row["ID_tarjeta"],
                        "ID_tarjeta" => $row["ID_tarjeta"]
                    ];
                }
            }

            $cuenta["Titulares"] = array_values($titularesUnicos);
            $cuenta["Tarjetas"] = array_values($tarjetasUnicas);

            header('Content-Type: application/json');        
            echo json_encode($cuenta);
            
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener los datos de la cuenta: " . $e->getMessage()]);
        }
    }
    

    // ADD CUENTA
    public function addCuenta($data) {
        $ID_cuenta = $data['ID'];
        $ID_cliente = $data['ID_cliente'];
        $Tipo_cuenta = $data['Tipo_cuenta'];

        try {
            $this->conn->beginTransaction(); // se usa para hacer los inserts a la vez
    
            $sql = "INSERT INTO cuentas (ID_cuenta, Tipo_cuenta) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$ID_cuenta, $Tipo_cuenta]);
    
            $sql2 = "INSERT INTO cliente_cuenta (ID_cliente, ID_cuenta) VALUES (?, ?)";
            $stmt2 = $this->conn->prepare($sql2);
            $stmt2->execute([$ID_cliente, $ID_cuenta]);

            if (isset($data['ID_cliente_2'])) {
                $ID_cliente2 = $data['ID_cliente_2'];
                $sql3 = "INSERT INTO cliente_cuenta (ID_cliente, ID_cuenta) VALUES (?, ?)";
                $stmt3 = $this->conn->prepare($sql3);
                $stmt3->execute([$ID_cliente2, $ID_cuenta]);
            }
    
            $this->conn->commit(); // confirmar la transaction
    
             echo json_encode(["mensaje" => "Cuenta creada y asociada correctamente"]);
        } catch (PDOException $e) {
            $this->conn->rollBack(); // si hay un error se tira para atrás
            echo json_encode(["error" => "Error al crear la cuenta: " . $e->getMessage()]);
        }
    }

    public function getContratosCuentas($ID_cliente){
        if (!isset($ID_cliente)){
            header('Content-Type: application/json');
            echo json_encode(["error" => "Faltan datos obligatorios"]);
            exit;
        }
        try{
            $sql="SELECT c.Tipo_cuenta, c.Fecha_creacion 
                    FROM cuentas c
                    JOIN cliente_cuenta cc ON c.ID_cuenta = cc.ID_cuenta
                    WHERE cc.ID_cliente = ?
                ";
                $stmt = $this->conn->prepare($sql);
            
                $stmt->execute([$ID_cliente]);
        
                if ($stmt->rowCount() > 0) {
                    $cuentas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    header('Content-Type: application/json');
                    echo json_encode($cuentas);
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(["error" => "No se encontraron contratos de cuentas para el ID_cliente proporcionado"]);
                }
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Error en la consulta: " . $e->getMessage()]);
        }
    }

    public function getCuentasSinTarjetas($ID_cliente){
        if (!isset($ID_cliente)){
            echo json_encode(["error" => "No se recibieron datos"]);
            return;
        }
        $sql="SELECT c.ID_cuenta, c.Tipo_cuenta, c.Saldo, c.Estado_cuenta
                FROM Cliente_Cuenta cc
                JOIN Cuentas c ON cc.ID_cuenta = c.ID_cuenta
                LEFT JOIN Tarjetas t ON c.ID_cuenta = t.ID_cuenta
                WHERE cc.ID_cliente = ? AND tipo_cuenta != 'Ahorro' AND t.ID_tarjeta IS NULL;";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$ID_cliente]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(["mensaje" => "No se encontró cuentas para dar de alta una tarjeta"]);
            }            
    
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);

    // EDIT CUENTA
    public function editCuentaById($data) {
        $ID_cliente = $data['ID_cliente'];
        $ID_cliente_2 = $data['ID_cliente_2'] ?? null;
        $ID_cuenta = $data['id'];

        $stmt = $this->conn->prepare("SELECT ID_cliente FROM cliente_cuenta WHERE ID_cuenta = ?");
        $stmt->execute([$ID_cuenta]);
        $titularesActuales = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
        // VERIFICAR DUPLICADOS
        if ($ID_cliente_2 && $ID_cliente === $ID_cliente_2) {
            echo json_encode(["error" => "No se puede asignar dos veces el mismo titular."]);
            return;
        }
    
        // DELETE 1 TITULAR
        if ($ID_cliente_2 === 'deleteTitular') {
            if (!in_array($ID_cliente, $titularesActuales)) {
                $this->updateTitulares($ID_cuenta, [$ID_cliente]);
            } else {
                $this->updateTitulares($ID_cuenta, [$ID_cliente]);
            }
            echo json_encode(["mensaje" => "Titular actualizado correctamente."]);
            return;
        }
    
        // VERIFICAR SI HAY CAMBIOS
        $nuevosTitulares = array_filter([$ID_cliente, $ID_cliente_2]);
        $nuevosTitulares = array_unique($nuevosTitulares); // VERIFICAR DUPLICADSO
    
        //
        if (array_diff($titularesActuales, $nuevosTitulares) || array_diff($nuevosTitulares, $titularesActuales)) {
            $this->updateTitulares($ID_cuenta, $nuevosTitulares);
        }
    
        echo json_encode(["mensaje" => "Titulares de cuenta actualizados."]);
    }
    
    // FUNCION AUXILIAR DE EDIT CUENTA BY ID
    private function updateTitulares($ID_cuenta, $nuevosTitulares) {
        $del = $this->conn->prepare("DELETE FROM cliente_cuenta WHERE ID_cuenta = ?");
        $del->execute([$ID_cuenta]);
    
        $ins = $this->conn->prepare("INSERT INTO cliente_cuenta (ID_cliente, ID_cuenta) VALUES (?, ?)");
        foreach ($nuevosTitulares as $clienteID) {
            $ins->execute([$clienteID, $ID_cuenta]);
        }
    }


    // PATCH ESTADO CUENTA
    public function editEstadoCuenta($data) {

        $ID_cuenta = $data['ID_cuenta'];
        $Estado_cuenta = $data['Estado_cuenta'];

        // VERIFICAR SI TIENE TITULARES ACTIVOS
        $sqlTitulares = "SELECT COUNT(*) AS totalActivos FROM Cliente_Cuenta CC JOIN Clientes C ON CC.ID_cliente = C.ID_cliente WHERE CC.ID_cuenta = :ID_cuenta AND C.Estado_Clientes = 'Activo'";

        $stmtTitulares = $this->conn->prepare($sqlTitulares);
        $stmtTitulares->bindParam(':ID_cuenta', $ID_cuenta, PDO::PARAM_STR);

        if ($stmtTitulares->execute()) {
            $row = $stmtTitulares->fetch(PDO::FETCH_ASSOC);
            $totalActivos = $row['totalActivos'];

            if ($Estado_cuenta === 'Activa' && $totalActivos === 0) {
                echo json_encode(["success" => false, "error" => "No se puede activar la cuenta porque no tiene titulares activos."]);
                return;
            }
        } else {
            echo json_encode(["success" => false, "error" => "Error al verificar los titulares activos de la cuenta."]);
            return;
        }

        // ACTIVAR SI SE PUEDE
        $sql = "UPDATE Cuentas SET Estado_cuenta = :Estado_cuenta WHERE ID_cuenta = :ID_cuenta";

        $stmt = $this->conn->prepare($sql);

        if ($stmt) {
            if ($stmt->execute([
                ':Estado_cuenta' => $Estado_cuenta,
                ':ID_cuenta' => $ID_cuenta
                ])) {
                    echo json_encode(["success" => true, "mensaje" => "Estado de cuenta actualizado correctamente."]);
                } else {
                    echo json_encode(["success" => false, "error" => "Error al actualizar estado de cuenta."]);
                }
            } else {
            echo json_encode(["success" => false, "error" => "Error en la preparación de la consulta."]);

        }
    }
}

?>