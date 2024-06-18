<?php
require_once 'fotos-db.php';

$fotos = obtener_fotos();

header('Content-Type: application/json');
echo json_encode($fotos);
?>