<?php
require_once 'BD/Database.php';

$con = Database::connect();
$mensaje = '';
$tipo_mensaje = '';

// Manejar la actualización de material
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = $_POST['id_material'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $unidad = $_POST['unidad'];
    $stock = $_POST['stock'];
    $precio_unitario = $_POST['precio_unitario'];
    
    $stmt = $con->prepare("UPDATE materiales SET nombre=?, descripcion=?, unidad=?, stock=?, precio_unitario=? WHERE id_material=?");
    
    if ($stmt->execute([$nombre, $descripcion, $unidad, $stock, $precio_unitario, $id])) {
        $mensaje = "Material actualizado correctamente";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al actualizar el material";
        $tipo_mensaje = "error";
    }
}

// Manejar la inserción de nuevo material
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'insert') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $unidad = $_POST['unidad'];
    $stock = $_POST['stock'];
    $precio_unitario = $_POST['precio_unitario'];
    
    // Obtener el último ID de la tabla
    $stmt = $con->query("SELECT MAX(id_material) as ultimo_id FROM materiales");
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    $ultimo_id = $resultado['ultimo_id'];

    $siguiente_id = $ultimo_id + 1;
    
    // Insertar con el ID específico
    $stmt = $con->prepare("INSERT INTO materiales (id_material, nombre, descripcion, unidad, stock, precio_unitario) VALUES (?, ?, ?, ?, ?, ?)");
    
    if ($stmt->execute([$siguiente_id, $nombre, $descripcion, $unidad, $stock, $precio_unitario])) {
        $mensaje = "Material agregado correctamente";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al agregar el material";
        $tipo_mensaje = "error";
    }
}

// Manejar la eliminación de material
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id_material'];
    
    $stmt = $con->prepare("DELETE FROM materiales WHERE id_material = ?");
    
    if ($stmt->execute([$id])) {
        $mensaje = "Material eliminado correctamente";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al eliminar el material";
        $tipo_mensaje = "error";
    }
}

// Obtener todos los materiales
$stmt = $con->prepare("SELECT * FROM materiales");
$stmt->execute();
$materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Materiales</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="estilosTabla.css">
</head>
<body>
    <h1>Lista de Materiales</h1>
    
    <?php if ($mensaje): ?>
        <div class="<?php echo $tipo_mensaje; ?>"><?php echo $mensaje; ?></div>
    <?php endif; ?>
    
    <table>
        <thead>
            <tr>
                <th>ID Material</th>
                <th>Nombre</th>
                <th>Descripcion</th>
                <th>Unidad</th>
                <th>Stock</th>
                <th>Precio Unitario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($materiales as $material): ?>
                <tr>
                    <td><?php echo $material['id_material']; ?></td>
                    <td><?php echo $material['nombre']; ?></td>
                    <td><?php echo $material['descripcion']; ?></td>
                    <td><?php echo $material['unidad']; ?></td>
                    <td><?php echo $material['stock']; ?></td>
                    <td><?php echo $material['precio_unitario']; ?></td>
                    <td>
                        <button class="btn btn-edit" onclick="editarMaterial(
                            <?php echo $material['id_material']; ?>, 
                            '<?php echo htmlspecialchars($material['nombre']); ?>', 
                            '<?php echo htmlspecialchars($material['descripcion']); ?>', 
                            '<?php echo htmlspecialchars($material['unidad']); ?>', 
                            '<?php echo htmlspecialchars($material['stock']); ?>', 
                            '<?php echo $material['precio_unitario']; ?>'
                        )">
                            Editar
                        </button>
                        <button class="btn btn-delete" onclick="confirmarEliminar(
                            <?php echo $material['id_material']; ?>, 
                            '<?php echo htmlspecialchars($material['nombre'] . ' ' . $material['descripcion']); ?>'
                        )">
                            Eliminar
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
        <div style="text-align: center; margin-top: 20px;">
            <button class="btn btn-add" onclick="agregarMaterial()">
                Agregar Nuevo Material
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
                <h3>Editar Material</h3>
            </div>
            
            <form method="POST" action="">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id_material" id="edit_id">
                
                <div class="form-group">
                    <label for="edit_nombre">Nombre:</label>
                    <input type="text" id="edit_nombre" name="nombre" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_descripcion">Descripcion:</label>
                    <input type="text" id="edit_descripcion" name="descripcion" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_unidad">Unidad:</label>
                    <input type="text" id="edit_unidad" name="unidad">
                </div>
                
                <div class="form-group">
                    <label for="edit_stock">Stock:</label>
                    <input type="text" id="edit_stock" name="stock">
                </div>
                
                <div class="form-group">
                    <label for="edit_fecha">Precio Unitario:</label>
                    <input type="date" id="edit_fecha" name="precio_unitario">
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
                <h3>Agregar Nuevo Material</h3>
            </div>
            
            <form method="POST" action="">
                <input type="hidden" name="action" value="insert">
                
                <div class="form-group">
                    <label for="add_nombre">Nombre:</label>
                    <input type="text" id="add_nombre" name="nombre" required>
                </div>
                
                <div class="form-group">
                    <label for="add_descripcion">Descripcion:</label>
                    <input type="text" id="add_descripcion" name="descripcion" required>
                </div>
                
                <div class="form-group">
                    <label for="add_unidad">Unidad:</label>
                    <input type="text" id="add_unidad" name="unidad">
                </div>
                
                <div class="form-group">
                    <label for="add_stock">Stock:</label>
                    <input type="text" id="add_stock" name="stock">
                </div>
                
                <div class="form-group">
                    <label for="add_fecha">Precio Unitario:</label>
                    <input type="date" id="add_fecha" name="precio_unitario">
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" onclick="cerrarModalAgregar()">Cancelar</button>
                    <button type="submit" class="btn btn-save">Agregar Material</button>
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
                <p>¿Está seguro que desea eliminar al material?</p>
                <div class="material-info" id="material-eliminar"></div>
                <p><strong>Esta acción no se puede deshacer.</strong></p>
                
                <form method="POST" action="">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id_material" id="delete_id">
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-cancel" onclick="cerrarModalEliminar()">Cancelar</button>
                        <button type="submit" class="btn btn-delete">Eliminar Material</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editarMaterial(id, nombre, descripcion, unidad, stock, precio_unitario) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_descripcion').value = descripcion;
            document.getElementById('edit_unidad').value = unidad;
            document.getElementById('edit_stock').value = stock;
            document.getElementById('edit_fecha').value = precio_unitario;

            document.getElementById('modalEditar').style.display = 'block';
        }
        
        function cerrarModal() {
            document.getElementById('modalEditar').style.display = 'none';
        }

        function agregarMaterial() {
            // Limpiar el formulario
            document.getElementById('add_nombre').value = '';
            document.getElementById('add_descripcion').value = '';
            document.getElementById('add_unidad').value = '';
            document.getElementById('add_stock').value = '';
            document.getElementById('add_fecha').value = '';
            
            // Mostrar el modal
            document.getElementById('modalAgregar').style.display = 'block';
        }
        
        function cerrarModalAgregar() {
            document.getElementById('modalAgregar').style.display = 'none';
        }

        function confirmarEliminar(id, nombreCompleto) {
            document.getElementById('delete_id').value = id;
            document.getElementById('material-eliminar').textContent = nombreCompleto;
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