<?php
require_once 'BD/Database.php';

$con = Database::connect();

$query = $con->query("SELECT * FROM materiales");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Materiales</title>
  <link rel="stylesheet" href="estilos.css">
</head>
<body>
  <h2 class="tituloTabla">Tabla de Materiales</h2>

<table class="tablaBD">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Unidad</th>
            <th>Stock</th>
            <th>Precio unitario</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $query->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id_material']); ?></td>
            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
            <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
            <td><?php echo htmlspecialchars($row['unidad']); ?></td>
            <td><?php echo htmlspecialchars($row['stock']); ?></td>
            <td><?php echo htmlspecialchars($row['precio_unitario']); ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</body>
</html>

<?php
Database::disconnect();
?>