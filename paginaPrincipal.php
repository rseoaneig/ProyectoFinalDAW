<?php
// Iniciar la sesión PHP para poder usar variables de sesión
session_start();

$mostrarZonaEmpleado = false; 
$username = "";           
$mensajeError = "";
$usuarioLogueado = false;


// Verificar si existe la variable de sesión 'usuario' (significa que está logueado)
if (isset($_SESSION["usuario"])) {
    // Marcar que el usuario está autenticado
    $usuarioLogueado = true;
    
    // Obtener el nombre de usuario de la sesión para mostrarlo
    $username = $_SESSION["usuario"];
    
    // Verificar si el usuario es de tipo 'empleado'
    if ($_SESSION["tipo"] === "empleado") {
        // Si es empleado, permitir mostrar la zona restringida
        $mostrarZonaEmpleado = true;
    }
}

// Verificar si se envió el formulario por POST y el usuario no está autenticado
if ($_SERVER["REQUEST_METHOD"] === "POST" && !$usuarioLogueado) {
    
    // Verificar que se enviaron los campos 'username' y 'password'
    if (isset($_POST["username"], $_POST["password"])) {
        
        // Obtener los datos del formulario y eliminar espacios en blanco
        $usuario = trim($_POST["username"]);
        $clave = trim($_POST["password"]);

        // Verificar que ambos campos no estén vacíos
        if (!empty($usuario) && !empty($clave)) {
            
            // Establecer conexión con la base de datos
            require_once 'BD/Database.php';
            $con = Database::connect();

            // Preparar consulta SQL para buscar usuario con credenciales exactas
            $stmt = $con->prepare("SELECT * FROM usuarios WHERE username = :username AND password = :password");
            
            // Ejecutar la consulta con los parámetros proporcionados
            $stmt->execute([
                ':username' => $usuario, //parámetro para la consulta preparada
                ':password' => $clave    //parámetro para la consulta preparada
            ]);

            // Obtener todos los resultados de la consulta
            $resultado = $stmt->fetchAll();

            // Verificar si se encontró al menos un usuario con esas credenciales
            if (count($resultado) > 0) {
                
                // Crear variables de sesión para el usuario autenticado
                $_SESSION["usuario"] = $usuario;                    // Guardar nombre de usuario
                $_SESSION["tipo"] = $resultado[0]['tipo'];          // Guardar tipo de usuario (empleado, cliente, etc.)
                
                // Crear cookie que durará 3 días para recordar el username
                setcookie("username", $usuario, time() + (86400 * 3), "/", "", false, true);
                
                // Redirigir a la misma página para evitar reenvío del formulario
                header("Location: " . $_SERVER['PHP_SELF']);
                
                // Detener ejecución después de la redirección
                exit();
                
            } else {
                // Si no se encontró el usuario, establecer mensaje de error
                $mensajeError = "Usuario o contraseña incorrectos";
                
                // Mantener el username en el formulario para que el usuario no tenga que escribirlo de nuevo
                $username = htmlspecialchars($usuario); 
            }

            // Cerrar la consulta preparada
            $stmt = null;
            
            // Cerrar la conexión a la base de datos
            $con = null;
            
        } else {
            // Si algún campo está vacío, mostrar mensaje de error
            $mensajeError = "Por favor, complete todos los campos";
        }
    }
}

// Si el usuario no está logueado y existe una cookie y el username está vacío
if (!$usuarioLogueado && isset($_COOKIE["username"]) && empty($username)) {
    
    // Cargar el username desde la cookie para pre-llenar el formulario
    $username = htmlspecialchars($_COOKIE["username"]);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soe Pro Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css">
</head>

<body class="fondo">
    <header>
        <div class="container-fluid py-3 cabecera">
            <div class="row">
                <div class="col-md-3 col-lg-2 d-md-block login">
                    <?php if (!$mostrarZonaEmpleado): ?>
                        <form method="post" action="" id="form" class="mb-3">
                            <div class="d-flex align-items-start gap-2">
                                <div class="d-flex flex-column w-100">
                                    <input type="text" name="username" class="form-control mb-2" placeholder="Usuario" value="<?php echo $username; ?>" required>
                                    <input type="password" name="password" class="form-control mb-2" placeholder="Contraseña" required>
                                    <?php if (!empty($mensajeError)): ?>    
                                        <dialog id="dialogo">
                                            <p><?php echo htmlspecialchars($mensajeError); ?></p>
                                            <button class="btn btn-primary" id="cerrar">Cerrar</button>
                                        </dialog>
                                        <script>
                                            document.getElementById("dialogo").showModal();
                                            document.getElementById("cerrar").addEventListener("click", function () {
                                                document.getElementById("dialogo").close();
                                            });
                                        </script>
                                    <?php endif; ?>
                                </div>
                                <input id="submitBtn" type="submit" value="Enviar" class="btn btn-secondary h-100"/>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="d-flex flex-column">
                            <div class="text-wrap" style="font-size: 40px">
                                Bienvenido <?php echo htmlspecialchars($_SESSION["usuario"]); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <span>Soe Pro Services</span>
            </div>
        </div>
    </header>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 d-md-block">
                <div class="sticky-top">
                    <div class="sidebar">
                        <ul class="nav flex-column">
                            <li class="nav-item"><a href="paginaPrincipal.php" class="nav-link">Página Principal</a></li>
                            <li class="nav-item"><a href="galeria.html" class="nav-link">Galería</a></li>
                            <li class="nav-item"><a href="servicios.html" class="nav-link">Servicios</a></li>
                            <li class="nav-item"><a href="presupuesto.html" class="nav-link">Haz tu presupuesto</a></li>    
                            
                            <?php if ($mostrarZonaEmpleado): ?>                  
                            <div class="mt-3">
                                <li class="separadorNav">Zona de empresa</li>
                                <li class="nav-item"><a href="materiales.php" class="nav-link">Materiales</a></li>
                                <li class="nav-item"><a href="empleados.php" class="nav-link">Empleados</a></li>
                                <li class="nav-item"><a href="trabajos.php" class="nav-link">Trabajos</a></li>
                                <li class="nav-item"><a href="clientes.php" class="nav-link">Clientes</a></li>
                            </div>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-9 col-lg-10 d-md-block">
                <br>
                <div class="imagenesGaleria">
                    <img src="./imagenes/imagenPrincipal.jpg" alt="Imagen principal de Soe Pro Services">
                </div>
                <span class="textoImagenesGal">LA EMPRESA</span>
                <div class="contenedorParrafo">
                    <p class="parrafoPrincipal">
                    Soe Pro Services: 
                    Somos una empresa de marcado carácter profesional, con una dilatada trayectoria en la ciudad de La Coruña.
                    Fundada hace más de 50 años, ofrecemos a nuestros clientes un servicio completo en materia de fontanería, calefacción, gas, pintura y albañilería.
                    Ponemos a su disposición un trabajo personalizado, serio y de calidad que nos hace ser un referente en el sector.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


