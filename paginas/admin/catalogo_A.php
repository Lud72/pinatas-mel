<?php 
include __DIR__ . '/../../plantilla/header-admin.php'; 

require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../../modelos/categorias.php';
require_once __DIR__ . '/../../modelos/productos.php';
require_once __DIR__ . '/../../helpers/upload.php';

$mensaje = '';
$error   = '';

// ---- Acciones ----
$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';

// CREAR
if ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    try {
        if ($titulo === '') {
            throw new RuntimeException('El nombre es obligatorio.');
        }
        $slug = generarSlug($titulo);

        // Verificar que el slug no exista
        $existe = $pdo->prepare("SELECT id FROM categorias WHERE slug = :slug");
        $existe->execute(['slug' => $slug]);
        if ($existe->fetch()) {
            throw new RuntimeException('Ya existe una categoría con ese nombre.');
        }

        $icono = subirImagen($_FILES['imagen'] ?? [], 'cat');
        if ($icono === null) {
            throw new RuntimeException('La imagen es obligatoria.');
        }

        crearCategoria($pdo, [
            'slug'   => $slug,
            'titulo' => $titulo,
            'icono'  => $icono,
            'banner' => 'franja.png',
            'activa' => 1,
        ]);
        $mensaje = 'Categoría creada exitosamente.';
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

// ACTUALIZAR
if ($accion === 'actualizar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int)($_POST['id'] ?? 0);
    $titulo = trim($_POST['titulo'] ?? '');
    try {
        if ($id <= 0 || $titulo === '') {
            throw new RuntimeException('Datos incompletos.');
        }

        $stmt = $pdo->prepare("SELECT icono FROM categorias WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $actual = $stmt->fetch();
        if (!$actual) {
            throw new RuntimeException('Categoría no encontrada.');
        }

        $iconoNuevo = subirImagen($_FILES['imagen'] ?? [], 'cat') ?? $actual['icono'];

        actualizarCategoria($pdo, $id, [
            'titulo' => $titulo,
            'icono'  => $iconoNuevo,
            'activa' => isset($_POST['activa']) ? 1 : 0,
        ]);

        if ($iconoNuevo !== $actual['icono']) {
            eliminarImagen($actual['icono']);
        }
        $mensaje = 'Categoría actualizada.';
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

// ELIMINAR
if ($accion === 'eliminar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    try {
        $conteos = contarProductosPorCategoria($pdo);
        if (!empty($conteos[$id])) {
            throw new RuntimeException('No se puede eliminar: la categoría tiene productos asociados. Desactívala en su lugar.');
        }
        eliminarCategoria($pdo, $id);
        $mensaje = 'Categoría eliminada.';
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

// ---- Cargar datos ----
$categorias = obtenerCategorias($pdo, false);
$conteoProductos = contarProductosPorCategoria($pdo);
?>

<div class="container my-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Catálogo - Administrador</h2>
            <p class="text-muted mb-0">Gestiona categorías y sus productos</p>
        </div>
        <button onclick="mostrarFormulario()" 
                class="btn fw-bold rounded-pill px-4 shadow-sm text-dark" 
                style="background-color: #F2B134; border: none;">
            Nueva Categoría <i class="bi bi-plus-lg ms-1"></i>
        </button>
    </div>

    <?php if ($mensaje): ?>
        <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
        <?php foreach ($categorias as $c): ?>
            <div class="col">
                <div class="card h-100 card-categoria shadow-sm position-relative">
                    <a href="productos_A.php?categoria=<?= urlencode($c['slug']) ?>" class="text-decoration-none">
                        <div class="card-categoria-img">
                            <img src="<?= BASE_URL ?>/imagenes/<?= htmlspecialchars($c['icono']) ?>" 
                                 alt="<?= htmlspecialchars($c['titulo']) ?>"
                                 loading="lazy">
                        </div>
                    </a>
                    <div class="card-body text-center bg-white card-categoria-body">
                        <h6 class="card-title fw-bold text-dark mb-1"><?= htmlspecialchars($c['titulo']) ?></h6>
                        <span class="badge bg-secondary rounded-pill">
                            <?= $conteoProductos[$c['id']] ?? 0 ?> productos
                        </span>
                        <?php if (!$c['activa']): ?>
                            <span class="badge bg-warning text-dark rounded-pill ms-1">Inactiva</span>
                        <?php endif; ?>

                        <div class="d-flex justify-content-center gap-1 mt-2">
                            <button class="btn btn-sm btn-outline-primary" title="Editar"
                                    onclick="editarCategoria(<?= htmlspecialchars(json_encode($c)) ?>)">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" title="Eliminar"
                                    onclick="confirmarEliminar(<?= (int)$c['id'] ?>, '<?= htmlspecialchars(addslashes($c['titulo'])) ?>')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Formulario Nueva Categoría -->
    <div class="row justify-content-center mt-4 d-none" id="contenedor-formulario">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow border-0" style="border: 3px solid #A3D8C5 !important; border-radius: 15px;">
                <div class="card-header bg-white text-center pt-4 border-0 d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold mb-0 w-100" id="form-titulo">Agregar Nueva Categoría</h4>
                    <button type="button" class="btn-close" onclick="ocultarFormulario()"></button>
                </div>
                <div class="card-body p-4">
                    <form action="catalogo_A.php" method="POST" enctype="multipart/form-data" id="form-categoria">
                        <input type="hidden" name="accion" value="crear" id="form-accion">
                        <input type="hidden" name="id" value="" id="form-id">

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Nombre</label>
                            <input type="text" name="titulo" id="form-nombre" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Imagen</label>
                            <input type="file" name="imagen" id="form-imagen" class="form-control" accept="image/*">
                            <small class="text-muted" id="form-imagen-nota"></small>
                        </div>

                        <div class="mb-3 form-check" id="form-activa-wrap" style="display:none;">
                            <input type="checkbox" name="activa" id="form-activa" class="form-check-input" checked>
                            <label for="form-activa" class="form-check-label">Activa</label>
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-4">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" onclick="ocultarFormulario()">Cancelar</button>
                            <button type="submit" class="btn btn-primario rounded-pill px-5 fw-bold">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal Eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border: 3px solid #D4005B; border-radius: 15px;">
            <div class="modal-body p-4 text-center">
                <h5 class="fw-bold mb-3">¿Eliminar categoría?</h5>
                <p class="text-muted small mb-3" id="eliminar-nombre"></p>
                <form method="POST" action="catalogo_A.php">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id" id="eliminar-id">
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4">Sí</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function mostrarFormulario() {
    const c = document.getElementById('contenedor-formulario');
    document.getElementById('form-titulo').innerText = 'Agregar Nueva Categoría';
    document.getElementById('form-accion').value = 'crear';
    document.getElementById('form-id').value = '';
    document.getElementById('form-nombre').value = '';
    document.getElementById('form-imagen').required = true;
    document.getElementById('form-imagen-nota').innerText = 'Obligatoria';
    document.getElementById('form-activa-wrap').style.display = 'none';

    c.classList.remove('d-none');
    setTimeout(() => c.scrollIntoView({ behavior: 'smooth', block: 'center' }), 50);
}

function editarCategoria(cat) {
    const c = document.getElementById('contenedor-formulario');
    document.getElementById('form-titulo').innerText = 'Editar Categoría';
    document.getElementById('form-accion').value = 'actualizar';
    document.getElementById('form-id').value = cat.id;
    document.getElementById('form-nombre').value = cat.titulo;
    document.getElementById('form-imagen').required = false;
    document.getElementById('form-imagen-nota').innerText = 'Déjala vacía para conservar la actual';
    document.getElementById('form-activa-wrap').style.display = 'block';
    document.getElementById('form-activa').checked = cat.activa == 1;

    c.classList.remove('d-none');
    setTimeout(() => c.scrollIntoView({ behavior: 'smooth', block: 'center' }), 50);
}

function ocultarFormulario() {
    document.getElementById('contenedor-formulario').classList.add('d-none');
    document.getElementById('form-categoria').reset();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function confirmarEliminar(id, nombre) {
    document.getElementById('eliminar-id').value = id;
    document.getElementById('eliminar-nombre').innerText = '«' + nombre + '»';
    new bootstrap.Modal(document.getElementById('modalEliminar')).show();
}
</script>

<?php include __DIR__ . '/../../plantilla/footer.php'; ?>