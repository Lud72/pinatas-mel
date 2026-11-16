<?php
// /pinatas-mel/paginas/Usuario/categoria.php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../../funciones/carrito.php';

// CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

// --- Acción POST: añadir al carrito desde esta vista ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'agregar') {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['errores_catalogo'] = ['Token inválido. Recarga la página.'];
        header('Location: categoria.php?slug=' . urlencode($_POST['slug_actual'] ?? ''));
        exit;
    }
    $productoId = (int)($_POST['producto_id'] ?? 0);
    $cantidad   = max(1, (int)($_POST['cantidad'] ?? 1));

    try {
        if ($productoId > 0) {
            agregarProductoAlCarrito($pdo, $productoId, $cantidad);
            $_SESSION['exito_catalogo'] = 'Producto añadido al carrito.';
        }
    } catch (PDOException $e) {
        error_log('[categoria] ' . $e->getMessage(), 3, BASE_PATH . '/logs/errores.log');
        $_SESSION['errores_catalogo'] = ['No pudimos añadir el producto. Intenta de nuevo.'];
    }
    header('Location: categoria.php?slug=' . urlencode($_POST['slug_actual'] ?? ''));
    exit;
}

$slug = $_GET['slug'] ?? '';
if ($slug === '') {
    header('Location: catalogo.php');
    exit;
}

// Categoría
$stmt = $pdo->prepare("SELECT * FROM categorias WHERE slug = :slug AND activa = 1 LIMIT 1");
$stmt->execute([':slug' => $slug]);
$categoria = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$categoria) {
    http_response_code(404);
    $noEncontrada = true;
} else {
    $noEncontrada = false;
    $stmt = $pdo->prepare(
        "SELECT * FROM productos
          WHERE categoria_id = :cid AND activo = 1
       ORDER BY creado_en DESC"
    );
    $stmt->execute([':cid' => $categoria['id']]);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$errores = $_SESSION['errores_catalogo'] ?? [];
$exito   = $_SESSION['exito_catalogo']   ?? null;
unset($_SESSION['errores_catalogo'], $_SESSION['exito_catalogo']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $noEncontrada ? 'Categoría no encontrada' : htmlspecialchars($categoria['titulo'], ENT_QUOTES, 'UTF-8') ?> - Piñatas-Mel</title>
    <link rel="stylesheet" href="../../estilos/catalogo.css">
</head>
<body>

<?php require __DIR__ . '/../../plantilla/header-publico.php'; ?>

<main class="catalogo-container">

    <nav class="breadcrumb" aria-label="Ruta">
        <a href="../../index.php">Inicio</a> ›
        <a href="catalogo.php">Catálogo</a>
        <?php if (!$noEncontrada): ?>
            › <span><?= htmlspecialchars($categoria['titulo'], ENT_QUOTES, 'UTF-8') ?></span>
        <?php endif; ?>
    </nav>

    <?php if ($noEncontrada): ?>
        <h1>Categoría no encontrada</h1>
        <p><a href="catalogo.php">Volver al catálogo</a></p>
    <?php else: ?>

        <header class="categoria-hero">
            <?php if (!empty($categoria['banner'])): ?>
                <img src="../../imagenes/banners/<?= htmlspecialchars($categoria['banner'], ENT_QUOTES, 'UTF-8') ?>"
                     alt="<?= htmlspecialchars($categoria['titulo'], ENT_QUOTES, 'UTF-8') ?>"
                     class="categoria-banner">
            <?php endif; ?>
            <h1><?= htmlspecialchars($categoria['titulo'], ENT_QUOTES, 'UTF-8') ?></h1>
        </header>

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

        <?php if (empty($productos)): ?>
            <p class="catalogo-vacio">Aún no hay productos en esta categoría.</p>
        <?php else: ?>
            <section class="productos-grid">
                <?php foreach ($productos as $p): ?>
                    <article class="producto-card">
                        <a href="detalle.php?slug=<?= urlencode($p['slug']) ?>" class="producto-card-link">
                            <div class="producto-card-img">
                                <img src="../../imagenes/productos/<?= htmlspecialchars($p['imagen'], ENT_QUOTES, 'UTF-8') ?>"
                                     alt="<?= htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            <div class="producto-card-body">
                                <h2><?= htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8') ?></h2>
                                <?php if (!empty($p['descripcion_corta'])): ?>
                                    <p class="producto-card-desc"><?= htmlspecialchars($p['descripcion_corta'], ENT_QUOTES, 'UTF-8') ?></p>
                                <?php endif; ?>
                                <p class="producto-card-precio">$<?= number_format((float)$p['precio'], 2) ?></p>
                            </div>
                        </a>
                        <form method="POST" action="categoria.php" class="producto-card-acciones">
                            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                            <input type="hidden" name="accion" value="agregar">
                            <input type="hidden" name="producto_id" value="<?= (int)$p['id'] ?>">
                            <input type="hidden" name="slug_actual" value="<?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?>">
                            <button type="submit" class="btn btn-primary">Añadir al carrito</button>
                        </form>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>

    <?php endif; ?>
</main>

<?php require __DIR__ . '/../../plantilla/footer.php'; ?>
</body>
</html>