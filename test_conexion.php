<?php
require_once __DIR__ . '/conexion.php';

echo "<h1>Test de conexión</h1>";

$categorias = $pdo->query("SELECT id, slug, titulo FROM categorias ORDER BY id")->fetchAll();
echo "<h2>Categorías (" . count($categorias) . ")</h2>";
echo "<ul>";
foreach ($categorias as $c) {
    echo "<li>{$c['id']} - {$c['slug']} - {$c['titulo']}</li>";
}
echo "</ul>";

$productos = $pdo->query("SELECT id, slug, nombre, precio FROM productos ORDER BY id")->fetchAll();
echo "<h2>Productos (" . count($productos) . ")</h2>";
echo "<ul>";
foreach ($productos as $p) {
    echo "<li>{$p['id']} - {$p['slug']} - {$p['nombre']} - \${$p['precio']}</li>";
}
echo "</ul>";

$admins = $pdo->query("SELECT id, usuario, nombre FROM usuarios_admin")->fetchAll();
echo "<h2>Admins (" . count($admins) . ")</h2>";
echo "<ul>";
foreach ($admins as $a) {
    echo "<li>{$a['id']} - {$a['usuario']} - {$a['nombre']}</li>";
}
echo "</ul>";