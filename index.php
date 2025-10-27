<?php
session_start();
require 'config/conexion.php'; // Incluye la conexión PDO

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
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Tu Streaming Simple | Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <header>
        <nav>
            <h1>Bienvenido a Zenith!!</h1>

            <form action="index.php" method="GET" style="margin-bottom: 20px;">
                <input type="text" name="q" placeholder="Buscar videos por título o descripción..."
                    value="<?php echo htmlspecialchars($termino_busqueda); ?>" size="50">
                <button type="submit">Buscar</button>
                <?php if ($termino_busqueda): ?>
                <a href="index.php">Limpiar Búsqueda</a>
                <?php endif; ?>
            </form>
        </nav>
    </header>


    <p>
        <?php if (isset($_SESSION['logueado'])): ?>
        <a href="forms/subir_video.php">Subir Video</a> |
        <a href="procesos/logout.php">Cerrar Sesión</a> |
        <a href="forms/dashboard.php">Dashboard</a>
        <?php else: ?>
        <a href="forms/login.php">Iniciar Sesión</a> |
        <a href="forms/registro.php">Registrarse</a>
        <?php endif; ?>
    </p>

    <hr>
    <div class="">

        <?php if (count($videos) > 0): ?>

        <?php foreach ($videos as $video): ?>

        <div class="">
            <video class="">
                <source src="<?php echo htmlspecialchars('procesos/'.$video['ruta_archivo']); ?>" type="video/mp4">
                Tu navegador no soporta el elemento de video.
            </video>

            <a href="forms/ver_video.php?id=<?php echo $video['id_video']; ?>">
                <?php echo htmlspecialchars($video['titulo']); ?>
            </a>
            <p><?php echo htmlspecialchars(substr($video['descripcion'], 0, 100)) . '...'; ?></p>
        </div>

        <?php endforeach; ?>

        <?php else: ?>
        <p>No se encontraron videos
            <?php echo $termino_busqueda ? "para el término '$termino_busqueda'" : "disponibles."; ?></p>
        <?php endif; ?>

    </div>

</body>

</html>