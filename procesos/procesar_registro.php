<?php
// 1. Incluir el archivo de conexión
require '../config/conexion.php'; // Asegúrate que 'conexion.php' esté en la misma carpeta

// 2. Verificar que el formulario se haya enviado por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 3. Recoger y sanitizar los datos del formulario
    $nombre_usuario = filter_input(INPUT_POST, 'nombre_usuario', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $contrasena = $_POST['contrasena']; // La contraseña se recoge sin sanitizar para el hash

    // --- Validación básica (puedes añadir más validaciones aquí) ---
    if (empty($nombre_usuario) || empty($email) || empty($contrasena)) {
        die("Error: Todos los campos son obligatorios.");
    }
    // -----------------------------------------------------------------

    // 4. Hashing de la contraseña para seguridad (¡Esencial!)
    // PASSWORD_DEFAULT utiliza el algoritmo de hashing más fuerte soportado por PHP.
    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
    
    // 5. Preparar la consulta SQL para la inserción
    // Usamos Prepared Statements (marcadores ?) para prevenir inyección SQL.
    $sql = "INSERT INTO usuarios (nombre_usuario, email, contrasena_hash, fecha_registro) 
            VALUES (?, ?, ?, NOW())"; // NOW() inserta la fecha y hora actual de la DB

    try {
        // 6. Preparar y ejecutar la sentencia
        $stmt = $pdo->prepare($sql);
        
        // Ejecutar la consulta, pasando los valores como un array
        $stmt->execute([$nombre_usuario, $email, $contrasena_hash]);

        // 7. Redirigir o mostrar mensaje de éxito
        echo "¡Registro exitoso! Ya puedes iniciar sesión.";
        // header("Location: inicio_sesion.php"); // Mejor opción: redirigir
        // exit();

    } catch (PDOException $e) {
        // 8. Manejo de errores (ej. si el nombre de usuario o email ya existen)
        if ($e->getCode() == '23000') { // 23000 es el código para "Integrity constraint violation" (duplicado)
            echo "Error: El nombre de usuario o email ya están registrados.";
        } else {
            // Error de conexión o SQL
            echo "Error al registrar: " . $e->getMessage();
        }
    }
    
} else {
    // Si alguien intenta acceder al script directamente sin enviar el formulario
    echo "Acceso denegado.";
}
?>