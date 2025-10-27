<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuarios</title>
</head>
<body>
    <h1><a href="../index.php">Inicio</a></h1>
    <h2>Registrarse en tu Plataforma</h2>

    <form action="../procesos/procesar_registro.php" method="POST">
        <div>
            <label for="nombre_usuario">Nombre de Usuario:</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" required>
        </div>
        <br>
        <div>
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <br>
        <div>
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>
        </div>
        <br>
        <button type="submit">Registrarme</button>
    </form>

</body>
</html>