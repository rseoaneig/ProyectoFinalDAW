<?php
require_once 'BD/Database.php';

$con = Database::connect();

$query = $con->query("SELECT * FROM empleados");

echo "<h2>Lista de empleados</h2>";
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Teléfono</th><th>Puesto</th><th>Fecha contratación</th></tr>";

while($row = $query->fetch(PDO::FETCH_ASSOC)){
    echo "<tr>
            <td>{$row['id_empleado']}</td>
            <td>{$row['nombre']}</td>
            <td>{$row['apellido']}</td>
            <td>{$row['telefono']}</td>
            <td>{$row['puesto']}</td>
            <td>{$row['fecha_contratacion']}</td>
          </tr>";
}

echo '</table>';

Database::disconnect();