<?php
// 1. INICIAR LA SESIÓN. Esto debe ser la primera línea.
session_start();

// 2. Incluir el archivo de conexión a la base de datos
require '../config/conexion.php'; 

// 3. Verificar que el formulario se haya enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recoger y sanitizar los datos
    $identificador = filter_input(INPUT_POST, 'nombre_usuario', FILTER_SANITIZE_SPECIAL_CHARS);
    $contrasena_ingresada = $_POST['contrasena'];

    // 4. Preparar la consulta SQL
    // Buscamos al usuario tanto por nombre_usuario como por email
    $sql = "SELECT id_usuario, nombre_usuario, contrasena_hash FROM usuarios WHERE nombre_usuario = ? OR email = ?";
    
    try {
        // Preparar y ejecutar la sentencia
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$identificador, $identificador]);

        // Obtener el resultado
        $usuario = $stmt->fetch();

        // 5. Verificar si se encontró al usuario
        if ($usuario) {
            // 6. Verificar la contraseña
            // password_verify compara la contraseña plana con el hash almacenado
            if (password_verify($contrasena_ingresada, $usuario['contrasena_hash'])) {
                
                // ¡Credenciales correctas!

                // 7. CREAR LA SESIÓN
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
                $_SESSION['logueado'] = true;

                // 8. Redirigir al usuario a la página principal de videos
                header("Location: ../index.php"); // Crearás 'dashboard.php' más adelante
                exit();

            } else {
                // Contraseña incorrecta
                echo "Error: Contraseña incorrecta.";
            }
        } else {
            // Usuario no encontrado
            echo "Error: Usuario no encontrado.";
        }

    } catch (PDOException $e) {
        // Error de la base de datos
        echo "Error de conexión: " . $e->getMessage();
    }
    
} else {
    // Si acceden al script directamente
    header("Location: login.php");
    exit();
}
?>