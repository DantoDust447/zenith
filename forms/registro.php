<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Registro de Usuarios</title>
</head>

<body>

    <header>
        <nav>
            <a href="../index.php" class="principal-link">Ruzzer</a>
        </nav>
    </header>
    <main>
        <form class="login" action="../procesos/procesar_registro.php" method="POST">
            <input type="text" id="nombre_usuario" name="nombre_usuario" required class="input-login"
                placeholder="Crea un nombre de usuario">
            <br><br>
            <input type="email" id="email" name="email" required class="input-login" placeholder="Correo">
            <br><br>
            <input type="password" id="contrasena" name="contrasena" required class="input-login"
                placeholder="Crea una contraseña">
            <br><br>
            <a class="login-button" href="login.php">
                <i class="bi bi-box-arrow-in-right"> Login</i>
            </a>
            <button type="submit" class="login-button"><i class="bi bi-pencil-square"> Registrarme</i></button>
        </form>
    </main>
</body>

</html>