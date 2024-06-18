<?php
require_once 'db.php';

function obtener_fotos() {
    // Get the global connection variable
    global $conn;

    // Prepare the SQL statement with a LIKE clause for searching
    $stmt = $conn->prepare("SELECT * FROM fotos");
    //$stmt->bind_param("s", $search_text);

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all results
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    $stmt->close();
    $conn->close();

    return $messages;
}
?>