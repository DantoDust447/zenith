<?php
// ¡IMPORTANTE! Inicia la sesión y verifica que el usuario esté logueado
session_start();
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: login.php");
    exit();
}
// Puedes incluir aquí el archivo 'conexion.php' si lo necesitas en el HTML,
// pero es más crítico en el script de procesamiento.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Video</title>
    <link href="../styles/style.css" rel="stylesheet">
</head>
<body>
        <header>

        <nav>
            <h1 class="principal-link">Hola, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></h1>
            <div class="links-container">
                <a class="header-links" href="dashboard.php">← Volver al panel</a>
                <a class="header-links" href="../procesos/logout.php">Cerrar Sesión</a>
            </div>

        </nav>
    </header>

    <h2>Subir Nuevo Video</h2>
    
    <form action="../procesos/procesar_subida.php" method="POST" enctype="multipart/form-data" class="login">
            <input type="file" id="video_file" name="video_file" accept="video/*" required class="input-login"><br><br>
            <input type="text" id="titulo" name="titulo" required maxlength="255" class="input-login"><br><br>
            <textarea id="descripcion" name="descripcion" rows="4" class="input-login"></textarea><br><br>
        <button type="submit" class="login-button">Subir Video</button>
    </form>

</body>
</html>