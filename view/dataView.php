<?php
    require_once '../config/config.php';
    require_once '../helpers/db.php';
    require_once '../controllers/DataController.php';

    $controlador = new DataController();
    $datos = $controlador->obtenerDatos(''); // Aqui ponemos el nombre de la tabla.
    
    // Creamos la tabla para visualizar la información de la tabla.
    function mostrarDatos($datos) {
        if (!empty($datos)) {
            echo '<table border="1">';
            echo '<thead><tr>';
            foreach (array_keys($datos[0]) as $columna) {
                echo "<th>$columna</th>";
            }
            echo '</tr></thead><tbody>';
            foreach ($datos as $fila) {
                echo '<tr>';
                foreach ($fila as $valor) {
                    echo "<td>$valor</td>";
                }
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo '<p>No se encontraron datos.</p>';
        }
    }
?>

    <h1>Datos de la Tabla</h1>
    <?php mostrarDatos($datos); ?>
