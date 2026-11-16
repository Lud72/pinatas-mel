<?php 
include __DIR__ . '/../../plantilla/header-publico.php';

$productos = [
    'hamburguesa' => [
        'nombre'      => 'Piñata de Hamburguesa',
        'imagen'      => 'hamburguesa.png',
        'precio'      => 500,
        'categoria'   => 'comida',
        'capacidad'   => 'Capacidad estimada: 3 a 5 kg de dulces surtidos (o juguetes pequeños y confeti).',
        'rendimiento' => '5 a 25 niños',
        'estructura'  => 'Estructura: Cartón de alta resistencia y papel periódico/cartonería tradicional.',
        'decoracion'  => 'Decoración: Papel china y crepé picado/plisado en capas de colores vivos con detalles en foamy/goma EVA.',
    ],
    'fresa' => [
        'nombre'      => 'Piñata de Fresa',
        'imagen'      => 'fresa.png',
        'precio'      => 450,
        'categoria'   => 'comida',
        'capacidad'   => 'Capacidad estimada: 2 a 4 kg de dulces surtidos.',
        'rendimiento' => '5 a 20 niños',
        'estructura'  => 'Estructura: Cartón y papel periódico.',
        'decoracion'  => 'Decoración: Papel china rojo brillante con detalles verdes para las hojas.',
    ],
    'buble-tea' => [
        'nombre'      => 'Piñata de Buble Tea',
        'imagen'      => 'buble-tea.png',
        'precio'      => 480,
        'categoria'   => 'comida',
        'capacidad'   => 'Capacidad estimada: 3 kg de dulces.',
        'rendimiento' => '5 a 20 niños',
        'estructura'  => 'Estructura: Cartón y papel periódico.',
        'decoracion'  => 'Decoración: Representa el famoso vaso transparente con detalles en foamy.',
    ],
    'palomitas' => [
        'nombre'      => 'Piñata de Palomitas',
        'imagen'      => 'palomitas.png',
        'precio'      => 400,
        'categoria'   => 'comida',
        'capacidad'   => 'Capacidad estimada: 2 a 4 kg de dulces.',
        'rendimiento' => '5 a 15 niños',
        'estructura'  => 'Estructura: Cartón y papel periódico.',
        'decoracion'  => 'Decoración: Papel china en tonos rosa, blanco y amarillo.',
    ],
    'pozole' => [
        'nombre'      => 'Piñata de Pozole',
        'imagen'      => 'pozole.png',
        'precio'      => 520,
        'categoria'   => 'comida',
        'capacidad'   => 'Capacidad estimada: 3 a 5 kg de dulces.',
        'rendimiento' => '5 a 25 niños',
        'estructura'  => 'Estructura: Cartón y papel periódico.',
        'decoracion'  => 'Decoración: Detalles de lechuga, rábanos y maíz en papel china.',
    ],
    'taco' => [
        'nombre'      => 'Piñata de Taco',
        'imagen'      => 'taco.png',
        'precio'      => 450,
        'categoria'   => 'comida',
        'capacidad'   => 'Capacidad estimada: 2 a 4 kg de dulces.',
        'rendimiento' => '5 a 20 niños',
        'estructura'  => 'Estructura: Cartón y papel periódico.',
        'decoracion'  => 'Decoración: Papel china simulando tortilla, carne y verduras.',
    ],
];

$categorias = [
    'estrellas'    => ['titulo' => 'Tradicionales de Estrellas', 'banner' => 'franja.png', 'icono' => 'estrella.png'],
    'personajes'   => ['titulo' => 'Personajes y Cultura Pop',   'banner' => 'franja.png', 'icono' => 'bob.png'],
    'numeros'      => ['titulo' => 'Números y Letras',           'banner' => 'franja.png', 'icono' => 'numero1.png'],
    'animales'     => ['titulo' => 'Animales',                   'banner' => 'franja.png', 'icono' => 'caballo.png'],
    'geometricas'  => ['titulo' => 'Formas Geométricas',         'banner' => 'franja.png', 'icono' => 'espejo.png'],
    'comida'       => ['titulo' => 'Objetos, Comida',            'banner' => 'franja.png', 'icono' => 'hamburguesa.png'],
    'jalon'        => ['titulo' => 'Piñatas de "Jalón"',         'banner' => 'franja.png', 'icono' => 'stitch.png'],
    'humoristicas' => ['titulo' => 'Humorísticas',               'banner' => 'franja.png', 'icono' => 'perro.png'],
];

$tipo           = $_GET['tipo'] ?? '';
$productoSlug   = $_GET['producto'] ?? '';

if (!isset($categorias[$tipo])) {
    header('Location: catalogo.php');
    exit;
}

$catActual        = $categorias[$tipo];
$productosFiltros = array_filter($productos, fn($p) => $p['categoria'] === $tipo);
$productoSel      = ($productoSlug && isset($productos[$productoSlug])) ? $productos[$productoSlug] : null;
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

    <!-- Breadcrumb -->
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
            <?php foreach ($productosFiltros as $slug => $p): ?>
                <div class="col">
                    <a href="categoria.php?tipo=<?= urlencode($tipo) ?>&producto=<?= urlencode($slug) ?>"
                       class="text-decoration-none">
                        <div class="card card-producto h-100 <?= ($productoSlug === $slug) ? 'border-seleccionado' : '' ?>">
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
                        <?= htmlspecialchars($productoSel['capacidad']) ?></p>

                    <p><strong>Rendimiento:</strong> <?= htmlspecialchars($productoSel['rendimiento']) ?></p>

                    <p><strong>Materiales de fabricación:</strong><br>
                        <?= htmlspecialchars($productoSel['estructura']) ?></p>

                    <p><strong>Decoración:</strong><br>
                        <?= htmlspecialchars($productoSel['decoracion']) ?></p>

                    <div class="d-flex gap-2 mt-3">
                        <a href="<?= BASE_URL ?>/paginas/Usuario/Carrito.php?add=<?= urlencode($productoSlug) ?>"
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