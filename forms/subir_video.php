<?php
// ¡IMPORTANTE! Inicia la sesión y verifica que el usuario esté logueado
session_start();
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: login.php");
    exit();
}
// Puedes incluir aquí el archivo 'conexion.php' si lo necesitas en el HTML,
// pero es más crítico en el script de procesamiento.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Video</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>

    <h2>Subir Nuevo Video</h2>
    
    <form action="../procesos/procesar_subida.php" method="POST" enctype="multipart/form-data">
        
        <div>
            <label for="video_file">Selecciona el Video:</label>
            <input type="file" id="video_file" name="video_file" accept="video/*" required>
        </div>
        <br>
        
        <div>
            <label for="titulo">Título del Video:</label>
            <input type="text" id="titulo" name="titulo" required maxlength="255">
        </div>
        <br>
        
        <div>
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="4"></textarea>
        </div>
        <br>
        
        <button type="submit">Subir Video</button>
    </form>

</body>
</html>