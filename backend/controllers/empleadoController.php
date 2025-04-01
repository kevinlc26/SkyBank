<?php
// controllers/empleadosController.php
require_once ('/../config/database.php');

class EmpleadosController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obtener todos los empleados
    public function getEmpleados() {
        $stmt = $this->conn->prepare("SELECT * FROM Empleados");
        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // Insertar un empleado
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
}
?>
