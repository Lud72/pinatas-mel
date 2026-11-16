<?php
// /pinatas-mel/paginas/Usuario/detalle.php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../../funciones/carrito.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

// --- POST: añadir al carrito con cantidad ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'agregar') {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['errores_detalle'] = ['Token inválido. Recarga la página.'];
        header('Location: detalle.php?slug=' . urlencode($_POST['slug_actual'] ?? ''));
        exit;
    }
    $productoId = (int)($_POST['producto_id'] ?? 0);
    $cantidad   = max(1, min(50, (int)($_POST['cantidad'] ?? 1)));

    try {
        if ($productoId > 0) {
            agregarProductoAlCarrito($pdo, $productoId, $cantidad);
            $_SESSION['exito_detalle'] = 'Producto añadido al carrito.';
        }
    } catch (PDOException $e) {
        error_log('[detalle] ' . $e->getMessage(), 3, BASE_PATH . '/logs/errores.log');
        $_SESSION['errores_detalle'] = ['No pudimos añadir el producto. Intenta de nuevo.'];
    }
    header('Location: detalle.php?slug=' . urlencode($_POST['slug_actual'] ?? ''));
    exit;
}

$slug = $_GET['slug'] ?? '';
if ($slug === '') {
    header('Location: catalogo.php');
    exit;
}

$stmt = $pdo->prepare(
    "SELECT p.*, c.titulo AS categoria_titulo, c.slug AS categoria_slug
       FROM productos p
       JOIN categorias c ON c.id = p.categoria_id
      WHERE p.slug = :slug AND p.activo = 1 LIMIT 1"
);
$stmt->execute([':slug' => $slug]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    http_response_code(404);
}

$errores = $_SESSION['errores_detalle'] ?? [];
$exito   = $_SESSION['exito_detalle']   ?? null;
unset($_SESSION['errores_detalle'], $_SESSION['exito_detalle']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $producto ? htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') : 'Producto no encontrado' ?> - Piñatas-Mel</title>
    <link rel="stylesheet" href="../../estilos/detalle.css">
</head>
<body>

<?php require __DIR__ . '/../../plantilla/header-publico.php'; ?>

<main class="detalle-container">

    <nav class="breadcrumb" aria-label="Ruta">
        <a href="../../index.php">Inicio</a> ›
        <a href="catalogo.php">Catálogo</a>
        <?php if ($producto): ?>
            › <a href="categoria.php?slug=<?= urlencode($producto['categoria_slug']) ?>">
                <?= htmlspecialchars($producto['categoria_titulo'], ENT_QUOTES, 'UTF-8') ?>
            </a>
            › <span><?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?></span>
        <?php endif; ?>
    </nav>

    <?php if (!$producto): ?>
        <h1>Producto no encontrado</h1>
        <p><a href="catalogo.php">Volver al catálogo</a></p>
    <?php else: ?>

        <?php if ($exito): ?>
            <div class="alert alert-success"><?= htmlspecialchars($exito, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errores as $e): ?>
                        <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="detalle-grid">
            <div class="detalle-imagen">
                <img src="../../imagenes/productos/<?= htmlspecialchars($producto['imagen'], ENT_QUOTES, 'UTF-8') ?>"
                     alt="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="detalle-info">
                <h1><?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?></h1>
                <p class="detalle-precio">$<?= number_format((float)$producto['precio'], 2) ?></p>

                <?php if (!empty($producto['descripcion_corta'])): ?>
                    <p class="detalle-desc-corta"><?= htmlspecialchars($producto['descripcion_corta'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>

                <ul class="detalle-caracteristicas">
                    <?php if (!empty($producto['capacidad'])): ?>
                        <li><strong>Capacidad:</strong> <?= htmlspecialchars($producto['capacidad'], ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endif; ?>
                    <?php if (!empty($producto['rendimiento'])): ?>
                        <li><strong>Rendimiento:</strong> <?= htmlspecialchars($producto['rendimiento'], ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endif; ?>
                    <?php if (!empty($producto['estructura'])): ?>
                        <li><strong>Estructura:</strong> <?= htmlspecialchars($producto['estructura'], ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endif; ?>
                    <?php if (!empty($producto['decoracion'])): ?>
                        <li><strong>Decoración:</strong> <?= htmlspecialchars($producto['decoracion'], ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endif; ?>
                </ul>

                <form method="POST" action="detalle.php" class="detalle-form">
                    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                    <input type="hidden" name="accion" value="agregar">
                    <input type="hidden" name="producto_id" value="<?= (int)$producto['id'] ?>">
                    <input type="hidden" name="slug_actual" value="<?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?>">

                    <label for="cantidad">Cantidad</label>
                    <input type="number" id="cantidad" name="cantidad" value="1" min="1" max="50">

                    <button type="submit" class="btn btn-primary btn-block">Añadir al carrito</button>
                </form>

                <a href="categoria.php?slug=<?= urlencode($producto['categoria_slug']) ?>" class="btn btn-secondary btn-block">
                    Seguir viendo <?= htmlspecialchars($producto['categoria_titulo'], ENT_QUOTES, 'UTF-8') ?>
                </a>
            </div>
        </div>

    <?php endif; ?>
</main>

<?php require __DIR__ . '/../../plantilla/footer.php'; ?>
</body>
</html>