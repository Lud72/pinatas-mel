<?php 
include_once __DIR__ . '/../../conexion.php'; // Conectamos a la BD
include __DIR__ . '/../../plantilla/header-publico.php'; 

// Traemos las categorías activas desde MySQL
try {
    $stmt = $pdo->query("SELECT slug, titulo, icono FROM categorias WHERE activa = 1 ORDER BY id ASC");
    $categorias = $stmt->fetchAll();
} catch (PDOException $e) {
    $categorias = []; // Si falla, mostramos vacío
}
?>

<div class="container my-5">

    <div class="text-center mb-5">
        <img src="/pinatas-mel/imagenes/hero-principal.png" 
             class="img-fluid rounded-4 shadow-sm w-100 banner-hero" 
             alt="Banner principal de Piñatas Mel" 
             loading="eager">
    </div>

    <div class="text-center mb-5">
        <h2 class="titulo-galeria">Galería de Piñatas</h2>
    </div>

    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php foreach ($categorias as $cat): ?>
    <div class="col">
        <a href="/pinatas-mel/paginas/Usuario/categoria.php?tipo=<?= urlencode($cat['slug']) ?>" class="text-decoration-none">
            <div class="card h-100 card-categoria">
                <div class="card-categoria-img">
                    <img src="/pinatas-mel/imagenes/<?= htmlspecialchars($cat['icono']) ?>" 
                         class="img-fluid" 
                         alt="<?= htmlspecialchars($cat['titulo']) ?>"
                         loading="lazy">
                </div>
                <div class="card-body text-center bg-white card-categoria-body">
                    <h6 class="card-title fw-bold text-dark mb-0"><?= htmlspecialchars($cat['titulo']) ?></h6>
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
                        <a href="/pinatas-mel/paginas/Usuario/personalizado.php" 
                           class="btn rounded-pill px-4 fw-bold btn-primario">
                            Clic aquí
                        </a>
                    </div>
                    <img src="/pinatas-mel/imagenes/palomitas.png" 
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