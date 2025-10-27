<!DOCTYPE html>
<html lang="es">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
</head>
<body>
    <h1><a href="../index.php">Inicio</a></h1>
    <h2>Iniciar Sesión</h2>

    <form action="../procesos/verificar_login.php" method="POST">
        <div>
            <label for="nombre_usuario">Nombre de Usuario o Email:</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" required>
        </div>
        <br>
        <div>
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>
        </div>
        <br>
        <button type="submit">Entrar</button>
    </form>

</body>
</html>