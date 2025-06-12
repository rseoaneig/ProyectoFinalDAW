<?php
require_once 'BD/Database.php';

$mensaje = '';
$tipo_mensaje = '';

try{

    $con = Database::connect();

    if (!$con) {
        throw new Exception("Error: No se pudo establecer conexión con la base de datos");
    }

    // Manejar la actualización de trabajo
    if ($_POST && isset($_POST['action']) && $_POST['action'] === 'update') {
        $id = $_POST['id_trabajo'];
        $id_cliente = $_POST['id_cliente'];
        $id_empleado = $_POST['id_empleado'];
        $descripcion = $_POST['descripcion'];
        $tipo_trabajo = $_POST['tipo_trabajo'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        $costo_total = $_POST['costo_total'];
        
        $stmt = "";
        $stmt = $con->prepare("UPDATE trabajos SET id_cliente=?, id_empleado=?, descripcion=?, tipo_trabajo=?, fecha_inicio=?, fecha_fin=?, costo_total=? WHERE id_trabajo=?");
        
        if (!$stmt) {
            throw new Exception("Error: No se pudo preparar la consulta SQL");
        }

        if ($stmt->execute([$id_cliente, $id_empleado, $descripcion, $tipo_trabajo, $fecha_inicio, $fecha_fin, $costo_total, $id])) {
            $mensaje = "Trabajo actualizado correctamente";
            $tipo_mensaje = "success";
        } else {
            $tipo_mensaje = "error";
            throw new Exception("Error: No se pudo ejecutar la consulta");
        }
    }

    // Manejar la inserción de nuevo trabajo
    if ($_POST && isset($_POST['action']) && $_POST['action'] === 'insert') {
        $id_cliente = $_POST['id_cliente'];
        $id_empleado = $_POST['id_empleado'];
        $descripcion = $_POST['descripcion'];
        $tipo_trabajo = $_POST['tipo_trabajo'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        $costo_total = $_POST['costo_total'];
        
        // Obtener el último ID de la tabla
        $stmt = "";
        $stmt = $con->query("SELECT MAX(id_trabajo) as ultimo_id FROM trabajos");

        if (!$stmt) {
            throw new Exception("Error: No se pudo preparar la consulta SQL");
        }

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            throw new Exception("Error: No se pudo ejecutar la consulta");
        }

        $ultimo_id = $resultado['ultimo_id'];
        $siguiente_id = $ultimo_id + 1;
        
        // Insertar con el ID específico
        $stmt = "";
        $stmt = $con->prepare("INSERT INTO trabajos (id_trabajo, id_cliente, id_empleado, descripcion, tipo_trabajo, fecha_inicio, fecha_fin, costo_total) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        if (!$stmt) {
            throw new Exception("Error: No se pudo preparar la consulta SQL");
        }

        if ($stmt->execute([$siguiente_id, $id_cliente, $id_empleado, $descripcion, $tipo_trabajo, $fecha_inicio, $fecha_fin, $costo_total])) {
            $mensaje = "Trabajo agregado correctamente";
            $tipo_mensaje = "success";
        } else {
            $tipo_mensaje = "error";
            throw new Exception("Error: No se pudo ejecutar la consulta");
        }
    }

    // Manejar la eliminación de trabajo
    if ($_POST && isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = $_POST['id_trabajo'];
        
        $stmt = "";
        $stmt = $con->prepare("DELETE FROM trabajos WHERE id_trabajo = ?");

        if (!$stmt) {
            throw new Exception("Error: No se pudo preparar la consulta SQL");
        }
        
        if ($stmt->execute([$id])) {
            $mensaje = "Trabajo eliminado correctamente";
            $tipo_mensaje = "success";
        } else {
            $tipo_mensaje = "error";
            throw new Exception("Error: No se pudo ejecutar la consulta");
        }
    }

    // Obtener todos los trabajos
    $stmt = "";
    $stmt = $con->prepare("SELECT * FROM trabajos");
    
    if (!$stmt) {
        throw new Exception("Error: No se pudo preparar la consulta SQL");
    }

    $stmt->execute();
    $trabajos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$trabajos) {
        throw new Exception("Error: No se pudo ejecutar la consulta");
    }

} catch (PDOException $e) {
    // Capturar errores específicos de PDO/Base de datos
    error_log("Error de base de datos: " . $e->getMessage());
    $mensajeError = $e->getMessage();
    
} catch (Exception $e) {
    // Capturar otros errores generales
    error_log("Error general: " . $e->getMessage());
    $mensajeError = $e->getMessage();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Trabajos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="estilosTabla.css">
</head>
<body>
    <h1>Lista de Trabajos</h1>

    <?php if ($mensaje): ?>
        <div class="<?php echo $tipo_mensaje; ?>"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID Trabajo</th>
                <th>ID Cliente</th>
                <th>ID Empleado</th>
                <th>Descripción</th>
                <th>Tipo de Trabajo</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Costo Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($trabajos as $trabajo): ?>
                <tr>
                    <td><?php echo $trabajo['id_trabajo']; ?></td>
                    <td><?php echo $trabajo['id_cliente']; ?></td>
                    <td><?php echo $trabajo['id_empleado']; ?></td>
                    <td><?php echo $trabajo['descripcion']; ?></td>
                    <td><?php echo $trabajo['tipo_trabajo']; ?></td>
                    <td><?php echo $trabajo['fecha_inicio']; ?></td>
                    <td><?php echo $trabajo['fecha_fin']; ?></td>
                    <td><?php echo $trabajo['costo_total']; ?></td>
                    <td>
                        <button class="btn btn-edit" onclick="editarTrabajo(
                            <?php echo $trabajo['id_trabajo']; ?>, 
                            '<?php echo htmlspecialchars($trabajo['id_cliente']); ?>', 
                            '<?php echo htmlspecialchars($trabajo['id_empleado']); ?>', 
                            '<?php echo htmlspecialchars($trabajo['descripcion']); ?>', 
                            '<?php echo htmlspecialchars($trabajo['tipo_trabajo']); ?>', 
                            '<?php echo $trabajo['fecha_inicio']; ?>', 
                            '<?php echo $trabajo['fecha_fin']; ?>', 
                            '<?php echo $trabajo['costo_total']; ?>'
                        )">
                            Editar
                        </button>
                        <button class="btn btn-delete" onclick="confirmarEliminar(
                            <?php echo $trabajo['id_trabajo']; ?>, 
                            '<?php echo htmlspecialchars($trabajo['descripcion']); ?>'
                        )">
                            Eliminar
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div style="text-align: center; margin-top: 20px;">
        <button class="btn btn-add" onclick="agregarTrabajo()">Agregar Nuevo Trabajo</button>
        <button class="btn btn-back" onclick="window.location.href='paginaPrincipal.php'">Volver a la página principal</button>
    </div>

    <!-- Modal de Edición -->
    <div id="modalEditar" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="cerrarModal()">&times;</span>
                <h3>Editar Trabajo</h3>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id_trabajo" id="edit_id">

                <div class="form-group">
                    <label for="edit_id_cliente">ID Cliente:</label>
                    <input type="text" id="edit_id_cliente" name="id_cliente" required>
                </div>

                <div class="form-group">
                    <label for="edit_id_empleado">ID Empleado:</label>
                    <input type="text" id="edit_id_empleado" name="id_empleado" required>
                </div>

                <div class="form-group">
                    <label for="edit_descripcion">Descripción:</label>
                    <input type="text" id="edit_descripcion" name="descripcion" required>
                </div>

                <div class="form-group">
                    <label for="edit_tipo_trabajo">Tipo de Trabajo:</label>
                    <input type="text" id="edit_tipo_trabajo" name="tipo_trabajo">
                </div>

                <div class="form-group">
                    <label for="edit_fecha_inicio">Fecha Inicio:</label>
                    <input type="date" id="edit_fecha_inicio" name="fecha_inicio">
                </div>

                <div class="form-group">
                    <label for="edit_fecha_fin">Fecha Fin:</label>
                    <input type="date" id="edit_fecha_fin" name="fecha_fin">
                </div>

                <div class="form-group">
                    <label for="edit_costo_total">Costo Total:</label>
                    <input type="text" id="edit_costo_total" name="costo_total">
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" onclick="cerrarModal()">Cancelar</button>
                    <button type="submit" class="btn btn-save">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Agregar -->
    <div id="modalAgregar" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="cerrarModalAgregar()">&times;</span>
                <h3>Agregar Nuevo Trabajo</h3>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="action" value="insert">

                <div class="form-group">
                    <label for="add_id_cliente">ID Cliente:</label>
                    <input type="text" id="add_id_cliente" name="id_cliente" required>
                </div>

                <div class="form-group">
                    <label for="add_id_empleado">ID Empleado:</label>
                    <input type="text" id="add_id_empleado" name="id_empleado" required>
                </div>

                <div class="form-group">
                    <label for="add_descripcion">Descripción:</label>
                    <input type="text" id="add_descripcion" name="descripcion" required>
                </div>

                <div class="form-group">
                    <label for="add_tipo_trabajo">Tipo de Trabajo:</label>
                    <input type="text" id="add_tipo_trabajo" name="tipo_trabajo">
                </div>

                <div class="form-group">
                    <label for="add_fecha_inicio">Fecha Inicio:</label>
                    <input type="date" id="add_fecha_inicio" name="fecha_inicio">
                </div>

                <div class="form-group">
                    <label for="add_fecha_fin">Fecha Fin:</label>
                    <input type="date" id="add_fecha_fin" name="fecha_fin">
                </div>

                <div class="form-group">
                    <label for="add_costo_total">Costo Total:</label>
                    <input type="text" id="add_costo_total" name="costo_total">
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" onclick="cerrarModalAgregar()">Cancelar</button>
                    <button type="submit" class="btn btn-save">Agregar Trabajo</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div id="modalEliminar" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="cerrarModalEliminar()">&times;</span>
                <h3>Confirmar Eliminación</h3>
            </div>
            <div class="modal-confirm">
                <p>¿Está seguro que desea eliminar el trabajo?</p>
                <div class="trabajo-info" id="trabajo-eliminar"></div>
                <p><strong>Esta acción no se puede deshacer.</strong></p>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id_trabajo" id="delete_id">
                    <div class="form-actions">
                        <button type="button" class="btn btn-cancel" onclick="cerrarModalEliminar()">Cancelar</button>
                        <button type="submit" class="btn btn-delete">Eliminar Trabajo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editarTrabajo(id, id_cliente, id_empleado, descripcion, tipo_trabajo, fecha_inicio, fecha_fin, costo_total) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_id_cliente').value = id_cliente;
            document.getElementById('edit_id_empleado').value = id_empleado;
            document.getElementById('edit_descripcion').value = descripcion;
            document.getElementById('edit_tipo_trabajo').value = tipo_trabajo;
            document.getElementById('edit_fecha_inicio').value = fecha_inicio;
            document.getElementById('edit_fecha_fin').value = fecha_fin;
            document.getElementById('edit_costo_total').value = costo_total;
            document.getElementById('modalEditar').style.display = 'block';
        }

        function cerrarModal() {
            document.getElementById('modalEditar').style.display = 'none';
        }

        function agregarTrabajo() {
            document.getElementById('add_id_cliente').value = '';
            document.getElementById('add_id_empleado').value = '';
            document.getElementById('add_descripcion').value = '';
            document.getElementById('add_tipo_trabajo').value = '';
            document.getElementById('add_fecha_inicio').value = '';
            document.getElementById('add_fecha_fin').value = '';
            document.getElementById('add_costo_total').value = '';
            document.getElementById('modalAgregar').style.display = 'block';
        }

        function cerrarModalAgregar() {
            document.getElementById('modalAgregar').style.display = 'none';
        }

        function confirmarEliminar(id, descripcion) {
            document.getElementById('delete_id').value = id;
            document.getElementById('trabajo-eliminar').textContent = descripcion;
            document.getElementById('modalEliminar').style.display = 'block';
        }

        function cerrarModalEliminar() {
            document.getElementById('modalEliminar').style.display = 'none';
        }
    </script>
</body>
</html>

<?php
Database::disconnect();
?>
