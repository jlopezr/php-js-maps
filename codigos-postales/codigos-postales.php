<?php
require_once 'codigos-postales-db.php';

$codigos = obtener_codigos_postales();

if (!empty($codigos)) {
    foreach ($codigos as $row) {
        echo "CP: " . $row['CP'] . "<br>";
        echo "Población: " . $row['poblacion'] . "<br>";
    }
} else {
    echo "No hay codigos postales.";
}
?>