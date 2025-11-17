<?php
session_start();
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: forms/login.php");
    exit();
}
require 'config/conexion.php'; // Incluye la conexión PDO
$id_usuario_actual = $_SESSION['id_usuario'];
// 1. Recoger el término de búsqueda, si existe
$termino_busqueda = '';
if (isset($_GET['q']) && !empty($_GET['q'])) {
    // Sanitizar el término de búsqueda
    $termino_busqueda = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_SPECIAL_CHARS);
}

// 2. Preparar la consulta SQL
if ($termino_busqueda) {
    // Si hay un término, buscamos en título y descripción
    $sql = "SELECT id_video, titulo, descripcion, ruta_archivo FROM videos 
            WHERE titulo LIKE ? OR descripcion LIKE ? 
            ORDER BY fecha_subida DESC";
    $parametro_busqueda = "%" . $termino_busqueda . "%";
    $parametros = [$parametro_busqueda, $parametro_busqueda];
} else {
    // Si no hay término, mostramos todos los videos ordenados por fecha de subida
    $sql = "SELECT id_video, titulo, descripcion, ruta_archivo FROM videos 
            ORDER BY fecha_subida DESC";
    $parametros = [];
}

// 3. Ejecutar la consulta
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($parametros);
    $videos = $stmt->fetchAll(); // Obtener todos los resultados
} catch (PDOException $e) {
    die("Error al consultar videos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <title>Zenith</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <header>
        <nav>
            <div class="principal-link">Zenith</div>
            <form action="index.php" method="GET" class="search-bar">
                <input type="text" name="q" value="<?php echo htmlspecialchars($termino_busqueda); ?>"
                    class="search-bar-input">
                <button type="submit" class="search-bar-button">Buscar</button>
                <?php if ($termino_busqueda): ?>
                <a href="index.php" class="cleaner">Limpiar Búsqueda</a>
                <?php endif; ?>
            </form>
                <?php if (isset($_SESSION['logueado'])): ?>
                <a class="header-links" href="forms/dashboard.php"><i class="bi bi-file-person">
                <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></i></a>
                <?php else: ?>
                <a class="header-links" href="forms/registro.php">Registrarse</a>
                <?php endif; ?>
        </nav>
    </header>
    <main style="display: flex;">
        <div class="card-container" style="width: 75%; display: block;">

            <?php if (count($videos) > 0): ?>

            <?php foreach ($videos as $video): ?>

            <a href="forms/ver_video.php?id=<?php echo $video['id_video']; ?>" class="card-video">
                <video class="video-preview" autoplay muted loop>
                    <source src="<?php echo htmlspecialchars('procesos/'.$video['ruta_archivo']); ?>" type="video/mp4">
                    Tu navegador no soporta el elemento de video.
                </video>
                <div class="descripcion">
                    <div class="video-title">
                        <?php echo htmlspecialchars($video['titulo']); ?>
                    </div>
                    <div class="detalles-video">
                        <?php echo htmlspecialchars($video['descripcion']); ?>
                    </div>
                </div>

            </a>

            <?php endforeach; ?>

            <?php else: ?>
            <p>No se encontraron videos
                <?php echo $termino_busqueda ? "para el término '$termino_busqueda'" : "disponibles."; ?></p>
            <?php endif; ?>

        </div>
        <aside style="width:25%; border-left:1px solid #00a703; align-text:center; padding-left:10px;">
            <br>
            <a href="forms/subir_video.php" class="header-links">
                <i class="bi bi-fast-forward-circle-fill"> Subir video</i>
            </a>
            <a href="procesos/logout.php" class="header-links">
                <i class="bi bi-person-bounding-box"> Cerrar Sesión</i>
            </a>
            <br><br>
            <a class="header-links" href="../index.php">
                <i class="bi bi-house"> Ir al inicio</i>
            </a>
            <br><br><br>
        </aside>
    </main>

</body>

</html>