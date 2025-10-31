<?php
session_start();
require '../config/conexion.php'; // Asegúrate de que la ruta sea correcta desde la raíz

// 1. Verificar y obtener el ID del video
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Si no hay ID o no es un número, redirigir a la página principal
    header("Location: index.php");
    exit();
}

$id_video = (int)$_GET['id'];

// 2. Preparar la consulta SQL (también podemos traer el nombre del usuario que lo subió)
$sql = "SELECT v.titulo, v.descripcion, v.ruta_archivo, v.fecha_subida, u.nombre_usuario 
        FROM videos v
        JOIN usuarios u ON v.id_usuario_subio = u.id_usuario
        WHERE v.id_video = ?";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_video]);
    $video = $stmt->fetch(); // Solo esperamos un resultado

    

    // 3. Verificar si el video existe
    if (!$video) {
        die("Error 404: Video no encontrado.");
    }
    
    // Si el video existe, la variable $video contiene todos sus datos.

} catch (PDOException $e) {
    die("Error al consultar el video: " . $e->getMessage());
}
// ... (código anterior en ver_video.php, después de obtener el video)

// 4. Obtener todos los comentarios para este video
$sql_comentarios = "SELECT c.texto, c.fecha_comentario, u.nombre_usuario 
                    FROM comentarios c
                    JOIN usuarios u ON c.id_usuario = u.id_usuario
                    WHERE c.id_video = ?
                    ORDER BY c.fecha_comentario DESC"; // Comentarios más nuevos primero

try {
    $stmt_comentarios = $pdo->prepare($sql_comentarios);
    $stmt_comentarios->execute([$id_video]);
    $comentarios = $stmt_comentarios->fetchAll();
} catch (PDOException $e) {
    // Manejo de error de comentarios, si es necesario
    $comentarios = []; 
}

// ... (El HTML comienza aquí)
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="stylesheet" href="../styles/style.css">
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($video['titulo']); ?></title>
</head>

<body>
    <header>
        <nav><a class="header-links" href="../index.php">← Volver a la página principal</a></nav>
    </header>
    <main>
            <video autoplay controls class="video-player">
                <source src="<?php echo htmlspecialchars('../procesos/'.$video['ruta_archivo']); ?>" type="video/mp4">
                Tu navegador no soporta el elemento de video.
            </video>
        
        <div class="video-info">
            <h1><?php echo htmlspecialchars($video['titulo']); ?></h1>
            <p><strong>Subido por:</strong> <?php echo htmlspecialchars($video['nombre_usuario']); ?></p>
            <p><strong>Fecha:</strong> <?php echo date("d/m/Y", strtotime($video['fecha_subida'])); ?></p>

            <h2>Descripción</h2>
            <p><?php echo nl2br(htmlspecialchars($video['descripcion'])); ?></p>
        </div>
        <h3>Comentarios (<?php echo count($comentarios); ?>)</h3>

        <?php if (isset($_SESSION['logueado'])): ?>
        <div style="margin-bottom: 20px;">
            <h4>Deja tu comentario:</h4>

            <form action="../procesos/procesar_comentario.php" method="POST" class="form-comentario">

                <input type="hidden" name="id_video" value="<?php echo $id_video; ?>">

                <textarea name="comentario" rows="3" cols="60" required
                    placeholder="Escribe tu comentario aquí..." class="input-comentario"></textarea>
                <br>
                <button type="submit" class="button-comentario">Publicar Comentario</button>
            </form>
        </div>
        <?php else: ?>
        <p>Debes <a href="login.php">iniciar sesión</a> para dejar un comentario.</p>
        <?php endif; ?>

        <div class="lista-comentarios">
            <?php if (count($comentarios) > 0): ?>
            <?php foreach ($comentarios as $comentario): ?>
            <div class="comentario">
                <p>
                    <strong><?php echo htmlspecialchars($comentario['nombre_usuario']); ?></strong>
                    <small>el <?php echo date("d/m/Y H:i", strtotime($comentario['fecha_comentario'])); ?></small>
                </p>
                <p><?php echo nl2br(htmlspecialchars($comentario['texto'])); ?></p>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <p>Sé el primero en comentar este video.</p>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>

</body>

</html>