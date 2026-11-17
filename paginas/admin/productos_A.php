<?php 
include __DIR__ . '/../../plantilla/header-admin.php'; 

require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../../modelos/categorias.php';
require_once __DIR__ . '/../../modelos/productos.php';
require_once __DIR__ . '/../../helpers/upload.php';

$catSlug = $_GET['categoria'] ?? 'comida';
$categoria = obtenerCategoriaPorSlug($pdo, $catSlug);
if ($categoria === null) {
    header('Location: catalogo_A.php');
    exit;
}

$mensaje = '';
$error   = '';
$accion  = $_POST['accion'] ?? '';

// CREAR
if ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nombre = trim($_POST['nombre'] ?? '');
        $precio = (float)($_POST['precio'] ?? 0);
        if ($nombre === '') throw new RuntimeException('El nombre es obligatorio.');
        if ($precio <= 0) throw new RuntimeException('El precio debe ser mayor a 0.');

        $slug = generarSlug($nombre);
        $existe = $pdo->prepare("SELECT id FROM productos WHERE slug = :slug");
        $existe->execute(['slug' => $slug]);
        if ($existe->fetch()) throw new RuntimeException('Ya existe un producto con ese nombre.');

        $imagen = subirImagen($_FILES['imagen'] ?? [], 'prod');
        if ($imagen === null) throw new RuntimeException('La imagen es obligatoria.');

        crearProducto($pdo, [
            'categoria_id'      => (int)$categoria['id'],
            'slug'              => $slug,
            'nombre'            => $nombre,
            'imagen'            => $imagen,
            'precio'            => $precio,
            'descripcion_corta' => trim($_POST['descripcion_corta'] ?? ''),
            'capacidad'         => trim($_POST['capacidad'] ?? ''),
            'rendimiento'       => trim($_POST['rendimiento'] ?? ''),
            'estructura'        => trim($_POST['estructura'] ?? ''),
            'decoracion'        => trim($_POST['decoracion'] ?? ''),
        ]);
        $mensaje = 'Producto creado exitosamente.';
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

// ACTUALIZAR
if ($accion === 'actualizar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    try {
        $nombre = trim($_POST['nombre'] ?? '');
        $precio = (float)($_POST['precio'] ?? 0);
        if ($id <= 0 || $nombre === '') throw new RuntimeException('Datos incompletos.');
        if ($precio <= 0) throw new RuntimeException('El precio debe ser mayor a 0.');

        $stmt = $pdo->prepare("SELECT imagen FROM productos WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $actual = $stmt->fetch();
        if (!$actual) throw new RuntimeException('Producto no encontrado.');

        $imagenNueva = subirImagen($_FILES['imagen'] ?? [], 'prod') ?? $actual['imagen'];

        actualizarProducto($pdo, $id, [
            'nombre'            => $nombre,
            'imagen'            => $imagenNueva,
            'precio'            => $precio,
            'descripcion_corta' => trim($_POST['descripcion_corta'] ?? ''),
            'capacidad'         => trim($_POST['capacidad'] ?? ''),
            'rendimiento'       => trim($_POST['rendimiento'] ?? ''),
            'estructura'        => trim($_POST['estructura'] ?? ''),
            'decoracion'        => trim($_POST['decoracion'] ?? ''),
            'activo'            => isset($_POST['activo']) ? 1 : 0,
        ]);

        if ($imagenNueva !== $actual['imagen']) eliminarImagen($actual['imagen']);
        $mensaje = 'Producto actualizado.';
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

// ELIMINAR / DESACTIVAR
if ($accion === 'eliminar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    try {
        $enPedidos = productoEstaEnPedidos($pdo, $id);
        $check = $pdo->prepare("SELECT COUNT(*) FROM pedido_items WHERE producto_id = :id");
        $check->execute(['id' => $id]);
        $enPedidos = $enPedidos || ((int)$check->fetchColumn() > 0);

        if ($enPedidos) {
            $stmt = $pdo->prepare("UPDATE productos SET activo = 0 WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $mensaje = 'El producto tiene pedidos asociados; se desactivó en lugar de eliminarlo.';
        } else {
            $stmt = $pdo->prepare("SELECT imagen FROM productos WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $prod = $stmt->fetch();
            try {
                eliminarProducto($pdo, $id);
                if ($prod) eliminarImagen($prod['imagen']);
                $mensaje = 'Producto eliminado.';
            } catch (PDOException $e) {
                $stmt = $pdo->prepare("UPDATE productos SET activo = 0 WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $mensaje = 'El producto tiene pedidos asociados; se desactivó en lugar de eliminarlo.';
            }
        }
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

// REACTIVAR
if ($accion === 'reactivar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    try {
        $stmt = $pdo->prepare("UPDATE productos SET activo = 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $mensaje = 'Producto reactivado.';
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

$catTitulo           = $categoria['titulo'];
$productosExistentes = obtenerProductos($pdo, (int)$categoria['id'], false);
?>

<style>
    .seccion-1-banner { background-color: #D4005B; color: white; border-radius: 15px; overflow: hidden; min-height: 250px; }
    .seccion-1-texto { padding: 3rem; display: flex; flex-direction: column; justify-content: center; }
    .seccion-1-imagen { background-color: white; display: flex; align-items: center; justify-content: center; padding: 2rem; overflow: hidden; }
    .seccion-1-imagen img { max-width: 100%; max-height: 200px; object-fit: contain; }
    .seccion-2-form { border: 3px solid #333; border-radius: 15px; padding: 2rem; background: #fff; }
    .recuadro-form { border: 2px solid #A3D8C5; border-radius: 10px; padding: 1.5rem; background: #fdfdfd; height: 100%; }
    .recuadro-form h6 { color: #D4005B; font-weight: bold; border-bottom: 2px solid #eee; padding-bottom: 0.5rem; margin-bottom: 1rem; }
    .seccion-3-carrusel { background-color: #EDB74B; padding: 2rem; border-radius: 15px; }
    .card-carrusel { border: none; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: transform 0.2s; }
    .card-carrusel:hover { transform: translateY(-5px); }
    .card-carrusel-img { background-color: #A3D8C5; height: 150px; border-radius: 10px 10px 0 0; display: flex; align-items: center; justify-content: center; padding: 1rem; }
    .card-carrusel-img img { max-height: 100%; object-fit: contain; }
    .btn-agregar-carrusel { width: 50px; height: 50px; border-radius: 50%; background: white; color: #333; font-size: 2rem; display: flex; align-items: center; justify-content: center; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin: 0 auto; }
</style>

<div class="container my-5">

    <div class="row seccion-1-banner mb-5 shadow-sm">
        <div class="col-md-7 seccion-1-texto">
            <h1 class="fw-bold mb-2"><?= htmlspecialchars($catTitulo) ?></h1>
            <p class="fs-5 mb-0 text-white-50">Administra los productos de esta categoría. Agrega, edita o elimina los diseños disponibles.</p>
        </div>
        <div class="col-md-5 seccion-1-imagen">
            <img src="<?= BASE_URL ?>/imagenes/hamburguesa.png" alt="Imagen de categoría">
        </div>
    </div>

    <?php if ($mensaje): ?>
        <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="seccion-2-form mb-5 shadow-sm" id="formNuevoProducto">
        <h4 class="mb-4 text-center">Agregar Nuevo Producto</h4>
        <form method="POST" action="productos_A.php?categoria=<?= urlencode($catSlug) ?>" enctype="multipart/form-data">
            <input type="hidden" name="accion" value="crear">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="recuadro-form">
                        <h6>1. Datos Básicos</h6>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Nombre del Producto</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Precio (MXN)</label>
                            <input type="number" name="precio" step="0.01" min="1" class="form-control" required>
                        </div>
                        <div>
                            <label class="form-label small text-muted">Descripción Corta</label>
                            <textarea name="descripcion_corta" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="recuadro-form">
                        <h6>2. Especificaciones</h6>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Capacidad de dulces</label>
                            <input type="text" name="capacidad" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Rendimiento (Niños)</label>
                            <input type="text" name="rendimiento" class="form-control">
                        </div>
                        <div>
                            <label class="form-label small text-muted">Materiales</label>
                            <input type="text" name="estructura" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="recuadro-form">
                        <h6>3. Detalles y Foto</h6>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Decoración</label>
                            <textarea name="decoracion" class="form-control" rows="2"></textarea>
                        </div>
                        <div>
                            <label class="form-label small text-muted">Fotografía del Producto *</label>
                            <input type="file" name="imagen" class="form-control" accept="image/*" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primario rounded-pill px-5 fw-bold">Guardar Producto</button>
            </div>
        </form>
    </div>

    <div class="seccion-3-carrusel mb-5 shadow-sm">
        <h4 class="mb-4 fw-bold text-dark">Productos en <?= htmlspecialchars($catTitulo) ?></h4>
        
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4 align-items-center">
            
            <?php foreach ($productosExistentes as $p): ?>
            <div class="col">
                <div class="card card-carrusel h-100 <?= !$p['activo'] ? 'opacity-75' : '' ?>">
                    <div class="card-carrusel-img">
                        <img src="<?= BASE_URL ?>/imagenes/<?= htmlspecialchars($p['imagen']) ?>" 
                            alt="<?= htmlspecialchars($p['nombre']) ?>">
                    </div>
                    <div class="card-body p-3 text-center">
                        <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($p['nombre']) ?></h6>
                        <?php if (!$p['activo']): ?>
                            <span class="badge bg-warning text-dark mb-1">Inactivo</span>
                        <?php endif; ?>
                        <p class="text-muted small mb-1"><?= htmlspecialchars($p['descripcion_corta'] ?? '') ?></p>
                        <p class="fw-bold small mb-2">$<?= number_format((float)$p['precio'], 2) ?></p>
                        <div class="d-flex justify-content-center gap-1 flex-wrap">
                            <button class="btn btn-sm btn-outline-primary" title="Editar"
                                    onclick="editarProducto(<?= htmlspecialchars(json_encode($p)) ?>)">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <?php if (!$p['activo']): ?>
                                <button class="btn btn-sm btn-outline-success" title="Reactivar"
                                        onclick="reactivarProducto(<?= (int)$p['id'] ?>, '<?= htmlspecialchars(addslashes($p['nombre'])) ?>')">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </button>
                            <?php else: ?>
                                <button class="btn btn-sm btn-outline-danger" title="Eliminar/Desactivar"
                                        onclick="confirmarEliminarProducto(<?= (int)$p['id'] ?>, '<?= htmlspecialchars(addslashes($p['nombre'])) ?>')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <div class="col text-center">
                <button class="btn-agregar-carrusel" title="Agregar nuevo producto" onclick="document.getElementById('formNuevoProducto').scrollIntoView({behavior: 'smooth'})">
                    <i class="bi bi-plus"></i>
                </button>
                <small class="d-block mt-2 fw-bold text-dark">Nuevo</small>
            </div>

        </div>
    </div>

</div>

<!-- Modal Editar Producto -->
<div class="modal fade" id="modalEditarProducto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border: 3px solid #A3D8C5; border-radius: 15px;">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Editar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="productos_A.php?categoria=<?= urlencode($catSlug) ?>" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="actualizar">
                <input type="hidden" name="id" id="edit-id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nombre</label>
                            <input type="text" name="nombre" id="edit-nombre" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Precio</label>
                            <input type="number" name="precio" id="edit-precio" step="0.01" min="1" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Descripción Corta</label>
                            <input type="text" name="descripcion_corta" id="edit-descripcion" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Capacidad</label>
                            <input type="text" name="capacidad" id="edit-capacidad" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Rendimiento</label>
                            <input type="text" name="rendimiento" id="edit-rendimiento" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Materiales</label>
                            <input type="text" name="estructura" id="edit-estructura" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Decoración</label>
                            <textarea name="decoracion" id="edit-decoracion" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nueva Imagen (opcional)</label>
                            <input type="file" name="imagen" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check">
                                <input type="checkbox" name="activo" id="edit-activo" class="form-check-input">
                                <label for="edit-activo" class="form-check-label">Activo</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primario rounded-pill px-4">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar Producto -->
<div class="modal fade" id="modalEliminarProducto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border: 3px solid #D4005B; border-radius: 15px;">
            <div class="modal-body p-4 text-center">
                <h5 class="fw-bold mb-3">¿Eliminar producto?</h5>
                <p class="text-muted small mb-3" id="eliminar-prod-nombre"></p>
                <form method="POST" action="productos_A.php?categoria=<?= urlencode($catSlug) ?>">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id" id="eliminar-prod-id">
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
function editarProducto(p) {
    document.getElementById('edit-id').value = p.id;
    document.getElementById('edit-nombre').value = p.nombre;
    document.getElementById('edit-precio').value = p.precio;
    document.getElementById('edit-descripcion').value = p.descripcion_corta ?? '';
    document.getElementById('edit-capacidad').value = p.capacidad ?? '';
    document.getElementById('edit-rendimiento').value = p.rendimiento ?? '';
    document.getElementById('edit-estructura').value = p.estructura ?? '';
    document.getElementById('edit-decoracion').value = p.decoracion ?? '';
    document.getElementById('edit-activo').checked = p.activo == 1;
    new bootstrap.Modal(document.getElementById('modalEditarProducto')).show();
}

function confirmarEliminarProducto(id, nombre) {
    document.getElementById('eliminar-prod-id').value = id;
    document.getElementById('eliminar-prod-nombre').innerText = '«' + nombre + '»';
    new bootstrap.Modal(document.getElementById('modalEliminarProducto')).show();
}

function reactivarProducto(id, nombre) {
    if (!confirm('¿Reactivar «' + nombre + '»?')) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'productos_A.php?categoria=<?= urlencode($catSlug) ?>';
    
    const accion = document.createElement('input');
    accion.type = 'hidden';
    accion.name = 'accion';
    accion.value = 'reactivar';
    form.appendChild(accion);
    
    const idInput = document.createElement('input');
    idInput.type = 'hidden';
    idInput.name = 'id';
    idInput.value = id;
    form.appendChild(idInput);
    
    document.body.appendChild(form);
    form.submit();
}

</script>

<?php include __DIR__ . '/../../plantilla/footer.php'; ?>