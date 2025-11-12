<?php
session_start();
// Asegúrate de que la ruta a 'conexion.php' es correcta. 
// Si dashboard.php está en 'users/', y conexion.php está en 'procesos/', 
// la ruta correcta es '../procesos/conexion.php'.
require '../config/conexion.php'; 

// Si la sesión NO está activa, redirigir al login
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: login.php");
    exit();
}

// 1. Obtener el ID del usuario logueado
$id_usuario_actual = $_SESSION['id_usuario'];

// 2. Consultar los videos subidos por este usuario
$sql = "SELECT id_video, titulo, descripcion, ruta_archivo, fecha_subida 
        FROM videos 
        WHERE id_usuario_subio = ? 
        ORDER BY fecha_subida DESC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_usuario_actual]);
    $mis_videos = $stmt->fetchAll(); // Obtener todos los videos del usuario
} catch (PDOException $e) {
    // En caso de error de BD, inicializamos el array vacío
    $mis_videos = [];
    $error_db = "Error al cargar tus videos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <title>Panel de Usuario</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body>
    <header>

        <nav>
            <h1 class="principal-link">Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>!</h1>
            <div class="links-container">
                <a class="header-links" href="../index.php">← Volver a la página principal</a>
                <a class="header-links" href="../index.php">Ir al Inicio</a>
            </div>

        </nav>
    </header>
    <hr>
    <main style="display: flex;">
        <div class="main-body" style="width: 75%; display: block;">
            <h2>Mis videos</h2>
            <?php if (isset($error_db)): ?>
            <p style="color: red;"><?php echo $error_db; ?></p>
            <?php endif; ?>

            <?php if (count($mis_videos) > 0): ?>


            <div class="dashboard-video-list">

                <?php foreach ($mis_videos as $video): ?>

                <div class="video-dashboard-item" href="">
                    <a href="ver_video.php?id=<?php echo $video['id_video']; ?>">
                        <video autoplay muted class="dashboard-video-preview">
                            <source src="<?php echo htmlspecialchars('../procesos/'.$video['ruta_archivo']); ?>"
                                type="video/mp4">
                            Video
                        </video>
                    </a>

                    <div class="video-title"><?php echo htmlspecialchars($video['titulo']); ?></div>
                    <p class="detalles-video">Subido el: <?php echo date("d/m/Y", strtotime($video['fecha_subida'])); ?>
                    </p>
                    <a href="../procesos/eliminar_video.php?id=<?php echo $video['id_video']; ?>"
                        class="header-links"><i class="bi bi-trash3-fill"></i> Borrar el video</a>
                </div>

                <?php endforeach; ?>

            </div>

            <?php else: ?>
            <p>Aún no has subido ningún video.</p>
            <p><a href="../forms/subir_video.php">¡Sube tu primer video ahora!</a></p>
            <?php endif; ?>
        </div>
        <aside style="width:25%; border-left:1px solid #00a703; align-text:center; padding-left:10px;">
            <a href="../forms/subir_video.php" class="header-links">
                <i class="bi bi-plus-circle-fill"> Nuevo video</i>
            </a>
            <br><br>
            <a href="../procesos/logout.php" class="header-links">
                <i class="bi bi-person-bounding-box"> Cerrar Sesión</i>
            </a>
            <br><br>
            <p class="info-alerts">
                ALERTA: <br>
                Para eliminar un video, tendrás que hacer dos clicks. <br>
                esto se debe a medidas de seguridad para evitar eliminaciones accidentales.
            </p>
        </aside>
    </main>


</body>

</html>