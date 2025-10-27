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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Usuario</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        .video-list {
            list-style: none;
            padding: 0;
        }
        .video-item-dash {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        .video-details {
            margin-left: 20px;
        }
        .video-details p {
            margin: 0;
            font-size: 0.9em;
            color: #555;
        }
    </style>
</head>
<body>
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>!</h1>
    
    <p>
        <a href="subir_video.php">Subir Nuevo Video</a>
    </p>
    <p><a href="../index.php">Ir al Inicio</a></p>
    <p><a href="../procesos/logout.php">Cerrar Sesión</a></p>

    <hr>
    
    <h2>Mis Videos Subidos</h2>
    
    <?php if (isset($error_db)): ?>
        <p style="color: red;"><?php echo $error_db; ?></p>
    <?php endif; ?>

    <?php if (count($mis_videos) > 0): ?>
        
        <div class="video-list">
            
            <?php foreach ($mis_videos as $video): ?>
                
                <div class="video-item-dash">
                    
                    <video controls style="width: 150px; height: 100px;">
                        <source src="<?php echo htmlspecialchars('../procesos/'.$video['ruta_archivo']); ?>" type="video/mp4">
                        Video
                    </video>

                    <div class="video-details">
                        <h3>
                            <a href="../ver_video.php?id=<?php echo $video['id_video']; ?>">
                                <?php echo htmlspecialchars($video['titulo']); ?>
                            </a>
                        </h3>
                        <p>Subido el: <?php echo date("d/m/Y", strtotime($video['fecha_subida'])); ?></p>
                        <p><a href="ver_video.php?id=<?php echo $video['id_video']; ?>">Ver comentarios</a></p>
                    </div>

                </div>

            <?php endforeach; ?>
            
        </div>
        
    <?php else: ?>
        <p>Aún no has subido ningún video.</p>
        <p><a href="../forms/subir_video.php">¡Sube tu primer video ahora!</a></p>
    <?php endif; ?>

</body>
</html>