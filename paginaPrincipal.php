<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"])) {
    // Guardar en la cookie (estará disponible en la próxima carga)
    setcookie("username", $_POST["username"], time() + (86400 * 3), "/");

    // Redirigir para limpiar el POST y el input
    header("Location: " . $_SERVER['PHP_SELF'] . "?limpio=1");
    exit();
}

// Mostrar el valor de la cookie solo si no acabamos de hacer submit
if (!isset($_GET["limpio"])) {
    $username = isset($_COOKIE["username"]) ? $_COOKIE["username"] : "";
} else {
    $username = ""; // campo vacío tras enviar
}
?>


<!DOCTYPE html>
<html lang="en">

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
                    <form method="post" action="" id="form" class="mb-3" >
                        <div class="d-flex align-items-start gap-2">
                            <div class="d-flex flex-column w-100">
                                <input type="text" name="username" class="form-control mb-2" placeholder="Usuario" value="<?php echo htmlspecialchars($username); ?>">
                                <input type="text" name="password" class="form-control mb-2" placeholder="Contraseña">
                            </div>
                            <input id="submitBtn" type="submit" value="Enviar" class="btn btn-secondary h-100"/>
                        </div>
                    </form>
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
                            <li class="nav-item"><a href="paginaPrincipal.html" class="nav-link">Página Principal</a>
                            </li>
                            <li class="nav-item"><a href="galeria.html" class="nav-link">Galería</a></li>
                            <li class="nav-item"><a href="servicios.html" class="nav-link">Servicios</a></li>
                            <li class="nav-item"><a href="#" class="nav-link">Contacto</a></li>
                            <li class="nav-item"><a href="presupuesto.html" class="nav-link">Haz tu presupuesto</a></li>                            
                            <div>
                                <li class="separadorNav">Zona de empresa</li>
                                <li class="nav-item"><a href="materiales.php" class="nav-link">Materiales</a></li>
                                <li class="nav-item"><a href="empleados.php" class="nav-link">Empleados</a></li>
                                <li class="nav-item"><a href="trabajos.php" class="nav-link">Trabajos</a></li>
                                <li class="nav-item"><a href="clientes.php" class="nav-link">Clientes</a></li>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-lg-10 d-md-block">
                <br>
                <div class="imagenesGaleria">
                    <img src="./imagenes/imagenPrincipal.jpg">
                </div>
                <span class="textoImagenesGal">LA EMPRESA</span>
                <p class="parrafoPrincipal">
                    Soe Pro Services :
                    Somos una empresa de marcado carácter profesional, con una dilatada trayectoria en la ciudad de La Coruña.
                    Fundada hace más de 50 años, ofrecemos a nuestros clientes un servicio completo en materia de fontanería, calefacción, gas, pintura y albañilería.
                    Ponemos a su disposición un trabajo personalizado, serio y de calidad que nos hace ser un referente en el sector.</p>
            </div>
        </div>
    </div>
</body>

</html>
