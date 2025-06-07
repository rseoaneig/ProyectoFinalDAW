<?php
require_once 'BD/Database.php';

$con = Database::connect();
$mensaje = '';
$tipo_mensaje = '';

// Manejar la actualización de empleado
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = $_POST['id_empleado'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $puesto = $_POST['puesto'];
    $fecha_contratacion = $_POST['fecha_contratacion'];
    
    $stmt = $con->prepare("UPDATE empleados SET nombre=?, apellido=?, telefono=?, puesto=?, fecha_contratacion=? WHERE id_empleado=?");
    
    if ($stmt->execute([$nombre, $apellido, $telefono, $puesto, $fecha_contratacion, $id])) {
        $mensaje = "Empleado actualizado correctamente";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al actualizar el empleado";
        $tipo_mensaje = "error";
    }
}

// Manejar la inserción de nuevo empleado
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'insert') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $puesto = $_POST['puesto'];
    $fecha_contratacion = $_POST['fecha_contratacion'];
    
    // Obtener el último ID de la tabla
    $stmt = $con->query("SELECT MAX(id_empleado) as ultimo_id FROM empleados");
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    $ultimo_id = $resultado['ultimo_id'];

    $siguiente_id = $ultimo_id + 1;
    
    // Insertar con el ID específico
    $stmt = $con->prepare("INSERT INTO empleados (id_empleado, nombre, apellido, telefono, puesto, fecha_contratacion) VALUES (?, ?, ?, ?, ?, ?)");
    
    if ($stmt->execute([$siguiente_id, $nombre, $apellido, $telefono, $puesto, $fecha_contratacion])) {
        $mensaje = "Empleado agregado correctamente";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al agregar el empleado";
        $tipo_mensaje = "error";
    }
}

// Manejar la eliminación de empleado
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id_empleado'];
    
    $stmt = $con->prepare("DELETE FROM empleados WHERE id_empleado = ?");
    
    if ($stmt->execute([$id])) {
        $mensaje = "Empleado eliminado correctamente";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al eliminar el empleado";
        $tipo_mensaje = "error";
    }
}

// Obtener todos los empleados
$stmt = $con->prepare("SELECT * FROM empleados");
$stmt->execute();
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Empleados</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="estilosTabla.css">
</head>
<body>
    <h1>Lista de Empleados</h1>
    
    <?php if ($mensaje): ?>
        <div class="<?php echo $tipo_mensaje; ?>"><?php echo $mensaje; ?></div>
    <?php endif; ?>
    
    <table>
        <thead>
            <tr>
                <th>ID Empleado</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Teléfono</th>
                <th>Puesto</th>
                <th>Fecha de Contratación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($empleados as $empleado): ?>
                <tr>
                    <td><?php echo $empleado['id_empleado']; ?></td>
                    <td><?php echo $empleado['nombre']; ?></td>
                    <td><?php echo $empleado['apellido']; ?></td>
                    <td><?php echo $empleado['telefono']; ?></td>
                    <td><?php echo $empleado['puesto']; ?></td>
                    <td><?php echo $empleado['fecha_contratacion']; ?></td>
                    <td>
                        <button class="btn btn-edit" onclick="editarEmpleado(
                            <?php echo $empleado['id_empleado']; ?>, 
                            '<?php echo htmlspecialchars($empleado['nombre']); ?>', 
                            '<?php echo htmlspecialchars($empleado['apellido']); ?>', 
                            '<?php echo htmlspecialchars($empleado['telefono']); ?>', 
                            '<?php echo htmlspecialchars($empleado['puesto']); ?>', 
                            '<?php echo $empleado['fecha_contratacion']; ?>'
                        )">
                            Editar
                        </button>
                        <button class="btn btn-delete" onclick="confirmarEliminar(
                            <?php echo $empleado['id_empleado']; ?>, 
                            '<?php echo htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellido']); ?>'
                        )">
                            Eliminar
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
        <div style="text-align: center; margin-top: 20px;">
            <button class="btn btn-add" onclick="agregarEmpleado()">
                Agregar Nuevo Empleado
            </button>
              <button class="btn btn-back" onclick="window.location.href='paginaPrincipal.php'">
                Volver a la página principal
              </button>
        </div>
    
    <!-- Modal de Edición -->
    <div id="modalEditar" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="cerrarModal()">&times;</span>
                <h3>Editar Empleado</h3>
            </div>
            
            <form method="POST" action="">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id_empleado" id="edit_id">
                
                <div class="form-group">
                    <label for="edit_nombre">Nombre:</label>
                    <input type="text" id="edit_nombre" name="nombre" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_apellido">Apellido:</label>
                    <input type="text" id="edit_apellido" name="apellido" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_telefono">Teléfono:</label>
                    <input type="text" id="edit_telefono" name="telefono">
                </div>
                
                <div class="form-group">
                    <label for="edit_puesto">Puesto:</label>
                    <input type="text" id="edit_puesto" name="puesto">
                </div>
                
                <div class="form-group">
                    <label for="edit_fecha">Fecha de Contratación:</label>
                    <input type="date" id="edit_fecha" name="fecha_contratacion">
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
                <span class="close" onclick="cerrarModalAgregar()">uuu</span>
                <h3>Agregar Nuevo Empleado</h3>
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
                    <label for="add_telefono">Teléfono:</label>
                    <input type="text" id="add_telefono" name="telefono">
                </div>
                
                <div class="form-group">
                    <label for="add_puesto">Puesto:</label>
                    <input type="text" id="add_puesto" name="puesto">
                </div>
                
                <div class="form-group">
                    <label for="add_fecha">Fecha de Contratación:</label>
                    <input type="date" id="add_fecha" name="fecha_contratacion">
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" onclick="cerrarModalAgregar()">Cancelar</button>
                    <button type="submit" class="btn btn-save">Agregar Empleado</button>
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
                <p>¿Está seguro que desea eliminar al empleado?</p>
                <div class="empleado-info" id="empleado-eliminar"></div>
                <p><strong>Esta acción no se puede deshacer.</strong></p>
                
                <form method="POST" action="">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id_empleado" id="delete_id">
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-cancel" onclick="cerrarModalEliminar()">Cancelar</button>
                        <button type="submit" class="btn btn-delete">Eliminar Empleado</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editarEmpleado(id, nombre, apellido, telefono, puesto, fecha_contratacion) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_apellido').value = apellido;
            document.getElementById('edit_telefono').value = telefono;
            document.getElementById('edit_puesto').value = puesto;
            document.getElementById('edit_fecha').value = fecha_contratacion;

            document.getElementById('modalEditar').style.display = 'block';
        }
        
        function cerrarModal() {
            document.getElementById('modalEditar').style.display = 'none';
        }

        function agregarEmpleado() {
            // Limpiar el formulario
            document.getElementById('add_nombre').value = '';
            document.getElementById('add_apellido').value = '';
            document.getElementById('add_telefono').value = '';
            document.getElementById('add_puesto').value = '';
            document.getElementById('add_fecha').value = '';
            
            // Mostrar el modal
            document.getElementById('modalAgregar').style.display = 'block';
        }
        
        function cerrarModalAgregar() {
            document.getElementById('modalAgregar').style.display = 'none';
        }

        function confirmarEliminar(id, nombreCompleto) {
            document.getElementById('delete_id').value = id;
            document.getElementById('empleado-eliminar').textContent = nombreCompleto;
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