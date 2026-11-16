<?php
/**
 * Conexión PDO a la base de datos pinatas_mel.
 * Se incluye desde los modelos o desde páginas que lo necesiten.
 *
 * TODO: mover credenciales a variables de entorno cuando se suba a producción.
 */

$host    = 'localhost';
$db      = 'pinatas_mel';
$user    = 'root';
$pass    = '';                 // XAMPP por defecto no tiene contraseña
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$opciones = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $opciones);
} catch (PDOException $e) {
    // En desarrollo mostramos el error. En producción se loguea y se muestra mensaje genérico.
    die('Error de conexión a la BD: ' . htmlspecialchars($e->getMessage()));
}