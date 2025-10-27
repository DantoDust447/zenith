<?php
session_start();
require '../config/conexion.php'; // Incluye la conexión PDO

// 1. Verificar si el usuario está logueado
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    die("Acceso denegado. Debe iniciar sesión.");
}

// 2. Definir la carpeta de destino para guardar los videos
// ¡ASEGÚRATE DE CREAR ESTA CARPETA y darle permisos de escritura!
$carpeta_destino = "uploads/"; 
if (!is_dir($carpeta_destino)) {
    mkdir($carpeta_destino, 0777, true); // Crea la carpeta si no existe
}

// Verificar que se haya enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['video_file'])) {

    // Recoger los datos del usuario logueado
    $id_usuario = $_SESSION['id_usuario'];
    
    // Recoger y sanitizar los datos del formulario
    $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_SPECIAL_CHARS);
    
    // -----------------------------------------------------
    // 3. Manejo y Subida del Archivo de Video
    // -----------------------------------------------------
    
    $archivo_temp = $_FILES['video_file']['tmp_name'];
    $nombre_original = basename($_FILES['video_file']['name']);
    $tipo_archivo = $_FILES['video_file']['type'];
    $tamano_archivo = $_FILES['video_file']['size'];
    
    // Generar un nombre único para el archivo para evitar colisiones
    $extension = pathinfo($nombre_original, PATHINFO_EXTENSION);
    $nombre_unico = uniqid('video_', true) . "." . $extension;
    $ruta_completa_archivo = $carpeta_destino . $nombre_unico;

    // Validación básica de archivos
    if ($tamano_archivo > 104857600) { // Ejemplo: 50MB (ajusta este valor)
        die("Error: El archivo es demasiado grande.");
    }
    // Puedes añadir más validaciones de tipo de archivo si es necesario

    // Mover el archivo subido de su ubicación temporal al destino final
    if (move_uploaded_file($archivo_temp, $ruta_completa_archivo)) {
        
        // El archivo se subió con éxito al servidor.

        // -----------------------------------------------------
        // 4. Inserción de los datos del video en la Base de Datos
        // -----------------------------------------------------

        // Nota: No estamos capturando la 'duracion' en este momento,
        // la pondremos como NULL (o 0) por ahora. 
        // Capturar la duración requiere librerías adicionales de PHP.
        
        $sql = "INSERT INTO videos (titulo, descripcion, ruta_archivo, fecha_subida, id_usuario_subio) 
                VALUES (?, ?, ?, NOW(), ?)";

        try {
            $stmt = $pdo->prepare($sql);
            // Ejecutar la consulta
            $stmt->execute([$titulo, $descripcion, $ruta_completa_archivo, $id_usuario]);

            // Redirigir o mostrar éxito
            echo "¡Video subido y registrado con éxito! <br> Ruta: " . htmlspecialchars($ruta_completa_archivo);
            // header("Location: index.php"); 
            // exit();

        } catch (PDOException $e) {
            // Error en la BD, eliminar el archivo subido si la inserción falla
            unlink($ruta_completa_archivo); 
            echo "Error al registrar el video en la base de datos: " . $e->getMessage();
        }

    } else {
        // Error al mover el archivo (ej. problemas de permisos de la carpeta)
        echo "Error: Hubo un problema al subir el archivo al servidor.";
        // Debugging: echo "Error code: " . $_FILES['video_file']['error'];
    }

} else {
    // Si no es un envío POST o el archivo no está presente
    echo "Petición no válida.";
}
?>