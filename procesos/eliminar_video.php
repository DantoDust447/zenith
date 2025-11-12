<?php
session_start();
require '../config/conexion.php'; // Incluye la conexión PDO

// 1. Verificar si el usuario está logueado
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: ../users/login.php");
    exit();
}

// 2. Verificar y obtener el ID del video a eliminar
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../users/dashboard.php?error=no_id");
    exit();
}

$id_video_a_eliminar = (int)$_GET['id'];
$id_usuario_actual = $_SESSION['id_usuario'];

try {
    // 3. Obtener la ruta del archivo y verificar que el video pertenece al usuario
    $sql = "SELECT ruta_archivo FROM videos WHERE id_video = ? AND id_usuario_subio = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_video_a_eliminar, $id_usuario_actual]);
    $video = $stmt->fetch();

    if (!$video) {
        // El video no existe o no pertenece al usuario actual
        header("Location: ../users/dashboard.php?error=permiso_denegado");
        exit();
    }

    $ruta_web_archivo = $video['ruta_archivo'];

    // 4. Determinar la ruta física en el servidor
    // La ruta en la DB es 'procesos/uploads/nombre.mp4'. 
    // Como este script está en 'procesos/', necesitamos la ruta relativa: 'uploads/nombre.mp4'.
    $ruta_fisica_archivo = str_replace('procesos/', '', $ruta_web_archivo);

    // 5. Eliminar el archivo físico del servidor
    if (file_exists($ruta_fisica_archivo)) {
        if (!unlink($ruta_fisica_archivo)) {
            // Manejo de error si no se puede borrar el archivo (permisos)
            header("Location: ../forms/dashboard.php?error=no_se_borro_archivo");
            exit();
        }
    }
    // Si el archivo no existe (error previo), la lógica continúa para limpiar la DB.


    // 6. Eliminar el registro de la base de datos
    $sql_delete = "DELETE FROM videos WHERE id_video = ? AND id_usuario_subio = ?";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute([$id_video_a_eliminar, $id_usuario_actual]);

    // 7. Redirigir al dashboard con mensaje de éxito
    header("Location: ../forms/dashboard.php?exito=video_eliminado");
    exit();

} catch (PDOException $e) {
    // Error de base de datos
    header("Location: ../forms/dashboard.php?error=db_error");
    // Opcional: Loggear el error $e->getMessage() para debug
    exit();
}
?>