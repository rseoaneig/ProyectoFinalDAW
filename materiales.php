<?php
require_once 'BD/Database.php';

$con = Database::connect();

$query = $con->query("SELECT * FROM materiales");

echo "<h2>Lista de empleados</h2>";
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Unidad</th><th>Stock</th><th>Precio unitario</th></tr>";

while($row = $query->fetch(PDO::FETCH_ASSOC)){
    echo "<tr>
            <td>{$row['id_material']}</td>
            <td>{$row['nombre']}</td>
            <td>{$row['descripcion']}</td>
            <td>{$row['unidad']}</td>
            <td>{$row['stock']}</td>
            <td>{$row['precio_unitario']}</td>
          </tr>";
}

echo '</table>';

Database::disconnect();