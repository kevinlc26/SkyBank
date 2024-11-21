<?php
    require_once '../config/config.php'; // Importa el archivo config.php para
    // las credenciales.

    $conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Chivato para verificar la conexión con la base de datos.
    echo "Conexión a la base de datos establecida.";

    // Función basica para recoger datos de una tabla.
    function getDatos($tabla) {
        global $conexion;

        $sql = "SELECT * FROM $tabla";
        $resultado = $conexion->query($sql);

        if ($resultado->num_rows > 0) {
            return $resultado->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }
?>