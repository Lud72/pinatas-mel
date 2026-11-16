<?php 
include __DIR__ . '/../../plantilla/header-admin.php'; 

$categorias = [
    ['id' => 1, 'slug' => 'estrellas',    'titulo' => 'Tradicionales de Estrellas', 'icono' => 'estrella.png',    'activa' => 1, 'productos' => 12],
    ['id' => 2, 'slug' => 'personajes',   'titulo' => 'Personajes y Cultura Pop',   'icono' => 'bob.png',         'activa' => 1, 'productos' => 8],
    ['id' => 3, 'slug' => 'numeros',      'titulo' => 'Números y Letras',           'icono' => 'numero1.png',     'activa' => 1, 'productos' => 6],
    ['id' => 4, 'slug' => 'animales',     'titulo' => 'Animales',                   'icono' => 'caballo.png',     'activa' => 1, 'productos' => 5],
    ['id' => 5, 'slug' => 'geometricas',  'titulo' => 'Formas Geométricas',         'icono' => 'espejo.png',      'activa' => 1, 'productos' => 4],
    ['id' => 6, 'slug' => 'comida',       'titulo' => 'Objetos, Comida',            'icono' => 'hamburguesa.png', 'activa' => 1, 'productos' => 6],
    ['id' => 7, 'slug' => 'jalon',        'titulo' => 'Piñatas de "Jalón"',         'icono' => 'stitch.png',      'activa' => 1, 'productos' => 3],
    ['id' => 8, 'slug' => 'humoristicas', 'titulo' => 'Humorísticas',               'icono' => 'perro.png',       'activa' => 1, 'productos' => 2],
];
?>

<div class="container my-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Catálogo - Administrador</h2>
            <p class="text-muted mb-0">Selecciona una categoría para gestionar sus productos</p>
        </div>
        <button onclick="mostrarFormulario()" 
                class="btn fw-bold rounded-pill px-4 shadow-sm text-dark" 
                style="background-color: #F2B134; border: none;">
            Nueva Categoría <i class="bi bi-plus-lg ms-1"></i>
        </button>
    </div>

    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
        <?php foreach ($categorias as $c): ?>
            <div class="col">
                <a href="productos_A.php?categoria=<?= urlencode($c['slug']) ?>" class="text-decoration-none">
                    <div class="card h-100 card-categoria shadow-sm">
                        <div class="card-categoria-img">
                            <img src="<?= BASE_URL ?>/imagenes/<?= htmlspecialchars($c['icono']) ?>" 
                                 alt="<?= htmlspecialchars($c['titulo']) ?>"
                                 loading="lazy">
                        </div>
                        <div class="card-body text-center bg-white card-categoria-body">
                            <h6 class="card-title fw-bold text-dark mb-1"><?= htmlspecialchars($c['titulo']) ?></h6>
                            <span class="badge bg-secondary rounded-pill"><?= $c['productos'] ?> productos</span>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="row justify-content-center mt-4 d-none" id="contenedor-formulario">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow border-0" style="border: 3px solid #A3D8C5 !important; border-radius: 15px;">
                <div class="card-header bg-white text-center pt-4 border-0 d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold mb-0 w-100">Agregar Nueva Categoría</h4>
                    <button type="button" class="btn-close" onclick="ocultarFormulario()" aria-label="Cerrar"></button>
                </div>

                <div class="card-body p-4">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Nombre</label>
                            <input type="text" name="titulo" class="form-control" 
                                   placeholder="Nombre de la Nueva Categoría" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">Fotografía</label>
                            <div class="upload-area p-4 text-center rounded" style="border: 2px dashed #A3D8C5; cursor: pointer;" onclick="document.getElementById('fileInput').click()">
                                <i class="bi bi-image text-muted fs-3 mb-2 d-block"></i>
                                <span class="text-muted small" id="fileName">Agregar una foto</span>
                                <input type="file" name="imagen" id="fileInput" class="d-none" accept="image/*" required onchange="document.getElementById('fileName').innerText = this.files[0].name">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end align-items-center gap-3 mt-4">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" onclick="ocultarFormulario()">Cancelar</button>
                            <button type="submit" class="btn btn-primario rounded-pill px-5 fw-bold">Crear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function mostrarFormulario() {
    const contenedor = document.getElementById('contenedor-formulario');
    contenedor.classList.remove('d-none');
    setTimeout(() => {
        contenedor.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, 50);
}

function ocultarFormulario() {
    const contenedor = document.getElementById('contenedor-formulario');
    contenedor.classList.add('d-none');
    window.scrollTo({ top: 0, behavior: 'smooth' });
    document.querySelector('#contenedor-formulario form').reset();
    document.getElementById('fileName').innerText = 'Agregar una foto';
}
</script>

<?php include __DIR__ . '/../../plantilla/footer.php'; ?>