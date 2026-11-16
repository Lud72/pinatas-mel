<?php 
include __DIR__ . '/../../plantilla/header-publico.php'; 

$categorias = [
    ['estrella.png',    'Piñatas de estrellas',   'Tradicionales de Estrellas', BASE_URL . '/paginas/Usuario/categoria.php?tipo=estrellas'],
    ['bob.png',         'Piñatas de personajes',  'Personajes y Cultura Pop',   BASE_URL . '/paginas/Usuario/categoria.php?tipo=personajes'],
    ['numero1.png',     'Piñatas de números',     'Números y Letras',           BASE_URL . '/paginas/Usuario/categoria.php?tipo=numeros'],
    ['caballo.png',     'Piñatas de animales',    'Animales',                   BASE_URL . '/paginas/Usuario/categoria.php?tipo=animales'],
    ['espejo.png',      'Piñatas geométricas',    'Formas Geométricas',         BASE_URL . '/paginas/Usuario/categoria.php?tipo=geometricas'],
    ['hamburguesa.png', 'Piñatas de comida',      'Objetos, Comida',            BASE_URL . '/paginas/Usuario/categoria.php?tipo=comida'],
    ['stitch.png',      'Piñatas de jalón',       'Piñatas de "Jalón"',         BASE_URL . '/paginas/Usuario/categoria.php?tipo=jalon'],
    ['perro.png',       'Piñatas humorísticas',   'Humorísticas',               BASE_URL . '/paginas/Usuario/categoria.php?tipo=humoristicas'],
];
?>

<div class="container my-5">

    <div class="text-center mb-5">
        <img src="<?= BASE_URL ?>/imagenes/hero-principal.png" 
             class="img-fluid rounded-4 shadow-sm w-100 banner-hero" 
             alt="Banner principal de Piñatas Mel" 
             loading="eager">
    </div>

    <div class="text-center mb-5">
        <h2 class="titulo-galeria">Galería de Piñatas</h2>
    </div>

    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php foreach ($categorias as [$img, $alt, $titulo, $link]): ?>
            <div class="col">
                <a href="<?= htmlspecialchars($link) ?>" class="text-decoration-none">
                    <div class="card h-100 card-categoria">
                        <div class="card-categoria-img">
                            <img src="<?= BASE_URL ?>/imagenes/<?= htmlspecialchars($img) ?>" 
                                 class="img-fluid" 
                                 alt="<?= htmlspecialchars($alt) ?>"
                                 loading="lazy">
                        </div>
                        <div class="card-body text-center bg-white card-categoria-body">
                            <h6 class="card-title fw-bold text-dark mb-0"><?= htmlspecialchars($titulo) ?></h6>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="row mt-5 g-4">

        <div class="col-md-6">
            <div class="card banner-amarillo h-100">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <div>
                        <h4 class="fw-bold text-dark mb-3">¿Tienes una idea en mente? ¡La hacemos piñata!</h4>
                        <a href="<?= BASE_URL ?>/paginas/Usuario/personalizado.php" 
                           class="btn rounded-pill px-4 fw-bold btn-primario">
                            Clic aquí
                        </a>
                    </div>
                    <img src="<?= BASE_URL ?>/imagenes/palomitas.png" 
                         class="img-fluid d-none d-sm-block banner-icono" 
                         alt="Piñata personalizada"
                         loading="lazy">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card banner-amarillo h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <h1 class="descuento-numero fw-bold me-3">15%</h1>
                    <div>
                        <p class="mb-0 text-dark fw-bold">
                            De descuento en cualquier piñata para todas las personas 
                            que cumplan años en el mes en curso.
                        </p>
                        <small class="text-muted">(presentando identificación o comprobante)</small>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php 
include __DIR__ . '/../../plantilla/footer.php'; 
?>