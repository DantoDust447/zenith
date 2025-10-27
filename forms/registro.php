<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Registro de Usuarios</title>
</head>
<body>
    
        <header>
        <nav>
            <a href="../index.php" class="principal-link">Zenith</a>
        </nav>
    </header>
    <main>
    <form class="login" action="../procesos/procesar_registro.php" method="POST">
            <input type="text" id="nombre_usuario" name="nombre_usuario" required class="input-login" placeholder="Crea un nombre de usuario">
            <br><br>
            <input type="email" id="email" name="email" required class="input-login" placeholder="Correo">
            <br><br>
            <input type="password" id="contrasena" name="contrasena" required class="input-login" placeholder="Crea una contraseña">
        <br><br>
        <button type="submit" class="login-button">Registrarme</button>
    </form>
</main>
</body>
</html>