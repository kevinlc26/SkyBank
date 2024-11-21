<?php
    require_once '../helpers/db.php'; // Importa el archivo db.php para
    // exportar la función getDatos().

    class DataController {
        public function obtenerDatos($tabla) {
            return getDatos($tabla);
        }
    }
?>