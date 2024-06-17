
<?php
require_once 'codigos-postales-db.php';
$codigos = obtener_codigos_postales();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Codigos Postales</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>

<h2>Codigos Postales</h2>

<?php if (!empty($codigos)) { ?>
    <table>
        <tr>
            <th>Codigo Postal</th>
            <th>Población</th>
        </tr>
        <?php foreach ($codigos as $row) { ?>
            <tr>
                <td><?= htmlspecialchars($row['CP']); ?></td>
                <td><?= htmlspecialchars($row['poblacion']); ?></td>                
            </tr>
        <?php } ?>
    </table>
<?php } else { ?>
    <p>No hay codigos postales.</p>
<?php } ?>

</body>
</html>
