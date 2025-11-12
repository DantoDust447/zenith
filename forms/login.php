<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="../styles/style.css">
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
</head>
<body>
        <header>
        <nav>
            <a href="../index.php" class="principal-link">Zenith</a>
        </nav>
    </header>
    <main>
    <h2>Iniciar Sesión</h2>
        <form action="../procesos/verificar_login.php" method="POST" class="login">
            <input class="input-login" type="text" id="nombre_usuario" name="nombre_usuario" placeholder="Usuario o correo" required>
        <br><br>
            <input class="input-login" type="password" id="contrasena" name="contrasena" placeholder="Contraseña" required>
        <br><br>
        <button type="submit" class="login-button">Entrar</button>
    </form>
    </main>
</body>
</html>