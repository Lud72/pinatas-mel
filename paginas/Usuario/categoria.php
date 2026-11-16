<?php 
include __DIR__ . '/../../plantilla/header-publico.php';

require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../../modelos/categorias.php';
require_once __DIR__ . '/../../modelos/productos.php';

$tipo         = $_GET['tipo'] ?? '';
$productoSlug = $_GET['producto'] ?? '';

// Validar que la categoría exista
$catActual = obtenerCategoriaPorSlug($pdo, $tipo);
if ($catActual === null) {
    header('Location: catalogo.php');
    exit;
}

// Productos de esta categoría
$productosFiltros = obtenerProductos($pdo, (int)$catActual['id']);

// Producto seleccionado (si hay uno en la URL)
$productoSel = null;
if ($productoSlug !== '') {
    $temp = obtenerProductoPorSlug($pdo, $productoSlug);
    // Solo mostrar si pertenece a esta categoría
    if ($temp && (int)$temp['categoria_id'] === (int)$catActual['id']) {
        $productoSel = $temp;
    }
}
?>

<div class="container my-5">

    <div class="banner-categoria mb-4 container">
        <img src="<?= BASE_URL ?>/imagenes/<?= htmlspecialchars($catActual['banner']) ?>"
             alt="<?= htmlspecialchars($catActual['titulo']) ?>"
             class="img-fluid w-100 banner-categoria-img"
             loading="eager">
        <div class="banner-categoria-overlay">
            <h1 class="banner-categoria-titulo"><?= htmlspecialchars($catActual['titulo']) ?></h1>
        </div>
    </div>

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/paginas/Usuario/catalogo.php">Catálogo</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($catActual['titulo']) ?></li>
        </ol>
    </nav>

    <h4 class="mb-3 fw-bold">Productos en esta categoría</h4>

    <?php if (empty($productosFiltros)): ?>
        <div class="alert alert-info text-center">
            Aún no hay productos en esta categoría. ¡Pronto tendremos novedades!
        </div>
    <?php else: ?>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
            <?php foreach ($productosFiltros as $p): ?>
                <div class="col">
                    <a href="categoria.php?tipo=<?= urlencode($tipo) ?>&producto=<?= urlencode($p['slug']) ?>"
                       class="text-decoration-none">
                        <div class="card card-producto h-100 <?= ($productoSel && $productoSel['slug'] === $p['slug']) ? 'border-seleccionado' : '' ?>">
                            <div class="card-categoria-img">
                                <img src="<?= BASE_URL ?>/imagenes/<?= htmlspecialchars($p['imagen']) ?>"
                                     alt="<?= htmlspecialchars($p['nombre']) ?>"
                                     loading="lazy">
                            </div>
                            <div class="card-body text-center">
                                <h6 class="text-dark fw-bold mb-1"><?= htmlspecialchars($p['nombre']) ?></h6>
                                <p class="text-muted mb-0">$<?= htmlspecialchars($p['precio']) ?> MXN</p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($productoSel): ?>
        <div class="card card-producto p-4" id="desglose-producto">
            <div class="row">
                <div class="col-md-5 text-center mb-3 mb-md-0">
                    <img src="<?= BASE_URL ?>/imagenes/<?= htmlspecialchars($productoSel['imagen']) ?>"
                         alt="<?= htmlspecialchars($productoSel['nombre']) ?>"
                         class="img-fluid" style="max-height: 350px;">
                </div>
                <div class="col-md-7">
                    <h2 class="mb-3"><?= htmlspecialchars($productoSel['nombre']) ?></h2>

                    <p class="fs-4 fw-bold" style="color:#D4005B;">
                        $<?= htmlspecialchars($productoSel['precio']) ?> MXN
                    </p>

                    <p><strong>Capacidad de dulces y sorpresa:</strong><br>
                        <?= htmlspecialchars($productoSel['capacidad'] ?? '') ?></p>

                    <p><strong>Rendimiento:</strong> <?= htmlspecialchars($productoSel['rendimiento'] ?? '') ?></p>

                    <p><strong>Materiales de fabricación:</strong><br>
                        <?= htmlspecialchars($productoSel['estructura'] ?? '') ?></p>

                    <p><strong>Decoración:</strong><br>
                        <?= htmlspecialchars($productoSel['decoracion'] ?? '') ?></p>

                    <div class="d-flex gap-2 mt-3">
                        <a href="<?= BASE_URL ?>/paginas/Usuario/Carrito.php?add=<?= urlencode($productoSel['slug']) ?>"
                           class="btn btn-primario rounded-pill px-4 py-2">
                            Añadir al carrito
                        </a>
                        <a href="categoria.php?tipo=<?= urlencode($tipo) ?>"
                           class="btn btn-outline-secondary rounded-pill px-4 py-2">
                            Cerrar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('desglose-producto')
                .scrollIntoView({ behavior: 'smooth', block: 'start' });
        </script>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/../../plantilla/footer.php'; ?>