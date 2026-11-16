<?php
// /pinatas-mel/paginas/Usuario/catalogo.php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../../funciones/carrito.php';

// CSRF para el botón "Añadir al carrito" rápido (opcional en esta vista)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

// Traer categorías activas con conteo de productos activos
$stmt = $pdo->prepare(
    "SELECT c.*, COUNT(p.id) AS total_productos
       FROM categorias c
  LEFT JOIN productos p ON p.categoria_id = c.id AND p.activo = 1
      WHERE c.activa = 1
   GROUP BY c.id
   ORDER BY c.orden ASC"
);
$stmt->execute();
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo - Piñatas-Mel</title>
    <link rel="stylesheet" href="../../estilos/catalogo.css">
</head>
<body>

<?php require __DIR__ . '/../../plantilla/header-publico.php'; ?>

<main class="catalogo-container">

    <header class="catalogo-hero">
        <h1>Nuestro Catálogo</h1>
        <p>Elige una categoría y encuentra la piñata perfecta.</p>
    </header>

    <?php if (empty($categorias)): ?>
        <p class="catalogo-vacio">Aún no hay categorías disponibles.</p>
    <?php else: ?>
        <section class="catalogo-grid">
            <?php foreach ($categorias as $cat): ?>
                <a href="categoria.php?slug=<?= urlencode($cat['slug']) ?>" class="catalogo-card">
                    <div class="catalogo-card-img">
                        <img src="../../imagenes/iconos/<?= htmlspecialchars($cat['icono'], ENT_QUOTES, 'UTF-8') ?>"
                             alt="<?= htmlspecialchars($cat['titulo'], ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="catalogo-card-body">
                        <h2><?= htmlspecialchars($cat['titulo'], ENT_QUOTES, 'UTF-8') ?></h2>
                        <span class="catalogo-card-count">
                            <?= (int)$cat['total_productos'] ?> producto<?= $cat['total_productos'] == 1 ? '' : 's' ?>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

</main>

<?php require __DIR__ . '/../../plantilla/footer.php'; ?>
</body>
</html>