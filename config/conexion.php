<?php
// Detalles de la conexión a la base de datos
$host = 'localhost'; // Normalmente 'localhost'
$db   = 'zenith'; // ¡Cambia esto!
$user = 'root'; // ¡Cambia esto!
$pass = ''; // ¡Cambia esto!
$charset = 'utf8mb4';

// Configuración de la DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanzar excepciones en caso de error
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Obtener resultados como arrays asociativos por defecto
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Desactivar la emulación de prepared statements (más seguridad)
];

try {
     // Intenta crear la conexión
     $pdo = new PDO($dsn, $user, $pass, $options);
     //echo "Conexión exitosa."; // Puedes descomentar esto para probar la conexión
} catch (\PDOException $e) {
     // Si falla, termina el script y muestra el error
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// La variable $pdo contiene el objeto de conexión que usaremos para todas las consultas.
?>