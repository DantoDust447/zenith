<?php
session_start();
require '../config/conexion.php'; // Incluye la conexión PDO

// 1. Verificar si el usuario está logueado
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    // Si no está logueado, redirigir al login
    header("Location: ../users/login.php");
    exit();
}

// 2. Verificar que la petición sea POST y que los campos necesarios existan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_video']) && isset($_POST['comentario'])) {

    // Recoger y sanitizar datos
    $id_video = filter_input(INPUT_POST, 'id_video', FILTER_VALIDATE_INT);
    $comentario = filter_input(INPUT_POST, 'comentario', FILTER_SANITIZE_SPECIAL_CHARS);
    $id_usuario = $_SESSION['id_usuario']; // ID del usuario logueado

    // 3. Validación básica
    if (!$id_video || empty($comentario)) {
        header("Location: ../ver_video.php?id=" . $id_video . "&error=campos_vacios");
        exit();
    }

    // 4. Inserción segura en la base de datos
    $sql = "INSERT INTO comentarios (id_video, id_usuario, texto, fecha_comentario) 
            VALUES (?, ?, ?, NOW())";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_video, $id_usuario, $comentario]);

        // 5. Redirigir de vuelta a la página del video para ver el comentario
        header("Location: ../forms/ver_video.php?id=" . $id_video);
        exit();

    } catch (PDOException $e) {
        // En caso de error de base de datos
        header("Location: ../ver_video.php?id=" . $id_video . "&error=db_fail");
        exit();
    }
} else {
    // Si acceden directamente al script
    header("Location: ../index.php");
    exit();
}
?>