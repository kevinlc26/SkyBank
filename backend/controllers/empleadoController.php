<?php
// controllers/empleadosController.php
require_once  __DIR__ . '/../config/database.php';

class EmpleadosController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // GET DE TODOS LOS EMPLEADOS
    public function getEmpleados() {
        $stmt = $this->conn->prepare("SELECT * FROM Empleados");
        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

        if (empty($empleados)) {
            echo json_encode(["mensaje" => "No hay empleados registrados"]);
        } else {
            echo json_encode($empleados);
        }
    }

    // GET DE 1 EMPLEADO
    public function getEmpleadoById ($ID_empleado) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM empleados WHERE id = :id");
            $stmt->bindParam(':id', $ID_empleado, PDO::PARAM_INT);
            $stmt->execute();
            $empleado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($empleado) {
                echo json_encode($empleado);
            } else {
                echo json_encode(["error" => "Empleado no encontrado"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener el empleado: " . $e->getMessage()]);
        }
    }

    // INSERT DE EMPLEADO
    public function insertEmpleado($data) {
        if (!isset($data['NIE'], $data['Nombre'], $data['Apellidos'], $data['Rol'], $data['Fecha_contratacion'])) {
            echo json_encode(["error" => "Datos incompletos"]);
            return;
        }

        $sql = "INSERT INTO Empleados (NIE, Nombre, Apellidos, Nacionalidad, Fecha_nacimiento, Telefono, Email, Direccion, Rol, Num_SS, Fecha_contratacion)
                VALUES (:nie, :nombre, :apellidos, :nacionalidad, :fecha_nacimiento, :telefono, :email, :direccion, :rol, :num_ss, :fecha_contratacion)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ":nie" => $data['NIE'],
            ":nombre" => $data['Nombre'],
            ":apellidos" => $data['Apellidos'],
            ":nacionalidad" => $data['Nacionalidad'] ?? null,
            ":fecha_nacimiento" => $data['Fecha_nacimiento'] ?? null,
            ":telefono" => $data['Telefono'] ?? null,
            ":email" => $data['Email'] ?? null,
            ":direccion" => $data['Direccion'] ?? null,
            ":rol" => $data['Rol'],
            ":num_ss" => $data['Num_SS'] ?? null,
            ":fecha_contratacion" => $data['Fecha_contratacion']
        ]);

        echo json_encode(["mensaje" => "Empleado registrado con éxito"]);
    }

    public function editEmpleado ($data) {

        if (!isset($data['NIE'], $data['Nombre'], $data['Apellidos'], $data['Rol'], $data['Fecha_contratacion'])) {
            echo json_encode(["error" => "Datos incompletos"]);
            return;
        }
    
        try {
            // Verificar si el empleado existe antes de actualizar
            $stmt = $this->conn->prepare("SELECT id FROM Empleados WHERE id = :id");
            $stmt->bindParam(':id', $ID_empleado, PDO::PARAM_INT);
            $stmt->execute();
    
            if ($stmt->rowCount() == 0) {
                echo json_encode(["error" => "Empleado no encontrado"]);
                return;
            }
    
            // Consulta SQL para actualizar los datos del empleado
            $sql = "UPDATE Empleados SET 
                        NIE = :nie, 
                        Nombre = :nombre, 
                        Apellidos = :apellidos, 
                        Nacionalidad = :nacionalidad, 
                        Fecha_nacimiento = :fecha_nacimiento, 
                        Telefono = :telefono, 
                        Email = :email, 
                        Direccion = :direccion, 
                        Rol = :rol, 
                        Num_SS = :num_ss, 
                        Fecha_contratacion = :fecha_contratacion
                    WHERE id = :id";
    
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ":nie" => $data['NIE'],
                ":nombre" => $data['Nombre'],
                ":apellidos" => $data['Apellidos'],
                ":nacionalidad" => $data['Nacionalidad'] ?? null,
                ":fecha_nacimiento" => $data['Fecha_nacimiento'] ?? null,
                ":telefono" => $data['Telefono'] ?? null,
                ":email" => $data['Email'] ?? null,
                ":direccion" => $data['Direccion'] ?? null,
                ":rol" => $data['Rol'],
                ":num_ss" => $data['Num_SS'] ?? null,
                ":fecha_contratacion" => $data['Fecha_contratacion'],
                ":id" => $ID_empleado
            ]);
    
            echo json_encode(["mensaje" => "Empleado actualizado correctamente"]);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al actualizar el empleado: " . $e->getMessage()]);
        }
        

    }

    public function deleteEmpleado ($ID_empleado) {

        try {
            $stmt = $this->conn->prepare("DELETE FROM empleados WHERE id = :id");
            $stmt->bindParam(':id', $ID_empleado, PDO::PARAM_INT);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                echo json_encode(["mensaje" => "Empleado eliminado correctamente"]);
            } else {
                echo json_encode(["error" => "Empleado no encontrado"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al eliminar el empleado: " . $e->getMessage()]);
        }
    }
}
?>
