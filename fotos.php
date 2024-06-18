
<?php
require_once 'fotos-db.php';
$fotos = obtener_fotos();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fotos</title>
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

<h2>Fotos y coordenadas</h2>

<?php if (!empty($fotos)) { ?>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Longitud</th>
            <th>Latitud</th>
            <th>Fecha</th>
        </tr>
        <?php foreach ($fotos as $row) { ?>
            <tr>
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td><?= htmlspecialchars($row['longitude']); ?></td>
                <td><?= htmlspecialchars($row['latitude']); ?></td>
                <td><?= htmlspecialchars($row['date']); ?></td>
            </tr>
        <?php } ?>
    </table>
<?php } else { ?>
    <p>No hay fotos.</p>
<?php } ?>

</body>
</html>
