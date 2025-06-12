<?php
require_once 'BD/Database.php';

$mensaje = '';
$tipo_mensaje = '';

try{

    $con = Database::connect();

    if (!$con) {
        throw new Exception("Error: No se pudo establecer conexión con la base de datos");
    }

    // Manejar la actualización de cliente
    if ($_POST && isset($_POST['action']) && $_POST['action'] === 'update') {
        $id = $_POST['id_cliente'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $correo_electronico = $_POST['correo_electronico'];
        
        $stmt = "";
        $stmt = $con->prepare("UPDATE clientes SET nombre=?, apellido=?, direccion=?, telefono=?, correo_electronico=? WHERE id_cliente=?");
        
        if (!$stmt) {
            throw new Exception("Error: No se pudo preparar la consulta SQL");
        }

        if ($stmt->execute([$nombre, $apellido, $direccion, $telefono, $correo_electronico, $id])) {
            $mensaje = "Cliente actualizado correctamente";
            $tipo_mensaje = "success";
        } else {
            $tipo_mensaje = "error";
            throw new Exception("Error: No se pudo ejecutar la consulta");
        }
    }

    // Manejar la inserción de nuevo cliente
    if ($_POST && isset($_POST['action']) && $_POST['action'] === 'insert') {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $correo_electronico = $_POST['correo_electronico'];
        
        // Obtener el último ID de la tabla
        $stmt = "";
        $stmt = $con->query("SELECT MAX(id_cliente) as ultimo_id FROM clientes");

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
        $stmt = $con->prepare("INSERT INTO clientes (id_cliente, nombre, apellido, direccion, telefono, correo_electronico) VALUES (?, ?, ?, ?, ?, ?)");
        
        if (!$stmt) {
            throw new Exception("Error: No se pudo preparar la consulta SQL");
        }

        if ($stmt->execute([$siguiente_id, $nombre, $apellido, $direccion, $telefono, $correo_electronico])) {
            $mensaje = "Cliente agregado correctamente";
            $tipo_mensaje = "success";
        } else {
            $tipo_mensaje = "error";
            throw new Exception("Error: No se pudo ejecutar la consulta");
        }
    }

    // Manejar la eliminación de cliente
    if ($_POST && isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = $_POST['id_cliente'];
        
        $stmt = "";
        $stmt = $con->prepare("DELETE FROM clientes WHERE id_cliente = ?");

        if (!$stmt) {
            throw new Exception("Error: No se pudo preparar la consulta SQL");
        }
        
        if ($stmt->execute([$id])) {
            $mensaje = "Cliente eliminado correctamente";
            $tipo_mensaje = "success";
        } else {
            $tipo_mensaje = "error";
            throw new Exception("Error: No se pudo ejecutar la consulta");
        }
    }

    // Obtener todos los clientes
    $stmt = "";
    $stmt = $con->prepare("SELECT * FROM clientes");
    
    if (!$stmt) {
        throw new Exception("Error: No se pudo preparar la consulta SQL");
    }

    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$clientes) {
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
    <title>Lista de Clientes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="estilosTabla.css">
</head>
<body>
    <h1>Lista de Clientes</h1>
    
    <?php if ($mensaje): ?>
        <div class="<?php echo $tipo_mensaje; ?>"><?php echo $mensaje; ?></div>
    <?php endif; ?>
    
    <table>
        <thead>
            <tr>
                <th>ID Cliente</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Correo Electrónico</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?php echo $cliente['id_cliente']; ?></td>
                    <td><?php echo $cliente['nombre']; ?></td>
                    <td><?php echo $cliente['apellido']; ?></td>
                    <td><?php echo $cliente['direccion']; ?></td>
                    <td><?php echo $cliente['telefono']; ?></td>
                    <td><?php echo $cliente['correo_electronico']; ?></td>
                    <td>
                        <button class="btn btn-edit" onclick="editarCliente(
                            <?php echo $cliente['id_cliente']; ?>,
                            '<?php echo htmlspecialchars($cliente['nombre']); ?>',
                            '<?php echo htmlspecialchars($cliente['apellido']); ?>',
                            '<?php echo htmlspecialchars($cliente['direccion']); ?>',
                            '<?php echo htmlspecialchars($cliente['telefono']); ?>',
                            '<?php echo htmlspecialchars($cliente['correo_electronico']); ?>'
                        )">
                            Editar
                        </button>
                        <button class="btn btn-delete" onclick="confirmarEliminar(
                            <?php echo $cliente['id_cliente']; ?>,
                            '<?php echo htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']); ?>'
                        )">
                            Eliminar
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="text-align: center; margin-top: 20px;">
        <button class="btn btn-add" onclick="agregarCliente()">Agregar Nuevo Cliente</button>
        <button class="btn btn-back" onclick="window.location.href='paginaPrincipal.php'">Volver a la página principal</button>
    </div>

    <!-- Modal de Edición -->
    <div id="modalEditar" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="cerrarModal()">&times;</span>
                <h3>Editar Cliente</h3>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id_cliente" id="edit_id">
                <div class="form-group">
                    <label for="edit_nombre">Nombre:</label>
                    <input type="text" id="edit_nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="edit_apellido">Apellido:</label>
                    <input type="text" id="edit_apellido" name="apellido" required>
                </div>
                <div class="form-group">
                    <label for="edit_direccion">Dirección:</label>
                    <input type="text" id="edit_direccion" name="direccion">
                </div>
                <div class="form-group">
                    <label for="edit_telefono">Teléfono:</label>
                    <input type="text" id="edit_telefono" name="telefono">
                </div>
                <div class="form-group">
                    <label for="edit_correo">Correo Electrónico:</label>
                    <input type="text" id="edit_correo" name="correo_electronico">
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
                <h3>Agregar Nuevo Cliente</h3>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="action" value="insert">
                <div class="form-group">
                    <label for="add_nombre">Nombre:</label>
                    <input type="text" id="add_nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="add_apellido">Apellido:</label>
                    <input type="text" id="add_apellido" name="apellido" required>
                </div>
                <div class="form-group">
                    <label for="add_direccion">Dirección:</label>
                    <input type="text" id="add_direccion" name="direccion">
                </div>
                <div class="form-group">
                    <label for="add_telefono">Teléfono:</label>
                    <input type="text" id="add_telefono" name="telefono">
                </div>
                <div class="form-group">
                    <label for="add_correo">Correo Electrónico:</label>
                    <input type="text" id="add_correo" name="correo_electronico">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" onclick="cerrarModalAgregar()">Cancelar</button>
                    <button type="submit" class="btn btn-save">Agregar Cliente</button>
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
                <p>¿Está seguro que desea eliminar al cliente?</p>
                <div class="cliente-info" id="cliente-eliminar"></div>
                <p><strong>Esta acción no se puede deshacer.</strong></p>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id_cliente" id="delete_id">
                    <div class="form-actions">
                        <button type="button" class="btn btn-cancel" onclick="cerrarModalEliminar()">Cancelar</button>
                        <button type="submit" class="btn btn-delete">Eliminar Cliente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editarCliente(id, nombre, apellido, direccion, telefono, correo) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_apellido').value = apellido;
            document.getElementById('edit_direccion').value = direccion;
            document.getElementById('edit_telefono').value = telefono;
            document.getElementById('edit_correo').value = correo;
            document.getElementById('modalEditar').style.display = 'block';
        }

        function cerrarModal() {
            document.getElementById('modalEditar').style.display = 'none';
        }

        function agregarCliente() {
            document.getElementById('add_nombre').value = '';
            document.getElementById('add_apellido').value = '';
            document.getElementById('add_direccion').value = '';
            document.getElementById('add_telefono').value = '';
            document.getElementById('add_correo').value = '';
            document.getElementById('modalAgregar').style.display = 'block';
        }

        function cerrarModalAgregar() {
            document.getElementById('modalAgregar').style.display = 'none';
        }

        function confirmarEliminar(id, nombreCompleto) {
            document.getElementById('delete_id').value = id;
            document.getElementById('cliente-eliminar').textContent = nombreCompleto;
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
