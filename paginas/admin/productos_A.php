<?php 
include __DIR__ . '/../../plantilla/header-admin.php'; 

$catSlug = $_GET['categoria'] ?? 'comida';
$catTitulo = ucfirst($catSlug); 

$productosExistentes = [
    [
        'id' => 1,
        'nombre' => 'Piñata de Fresa',
        'imagen' => 'fresa.png',
        'precio' => 450,
        'descripcion_corta' => 'Diseñada con un rojo brillante de papel china',
        'capacidad' => 'Capacidad estimada: 2 a 4 kg de dulces surtidos.',
        'rendimiento' => '5 a 20 niños',
        'estructura' => 'Estructura: Cartón y papel periódico.',
        'decoracion' => 'Decoración: Papel china rojo brillante con detalles verdes para las hojas.'
    ],
    [
        'id' => 2,
        'nombre' => 'Piñata de Hamburguesa',
        'imagen' => 'hamburguesa.png',
        'precio' => 500,
        'descripcion_corta' => 'Hamburguesa clásica con queso y lechuga',
        'capacidad' => 'Capacidad estimada: 3 a 5 kg de dulces surtidos (o juguetes pequeños y confeti).',
        'rendimiento' => '5 a 25 niños',
        'estructura' => 'Estructura: Cartón de alta resistencia y papel periódico/cartonería tradicional.',
        'decoracion' => 'Decoración: Papel china y crepé picado/plisado en capas de colores vivos.'
    ]
];
?>

<style>
    .seccion-1-banner {
        background-color: #D4005B;
        color: white;
        border-radius: 15px;
        overflow: hidden;
        min-height: 250px;
    }
    .seccion-1-texto {
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .seccion-1-imagen {
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        overflow: hidden; 
    }
    .seccion-1-imagen img {
        max-width: 100%; 
        max-height: 200px;
        object-fit: contain; 
    }

    .seccion-2-form {
        border: 3px solid #333;
        border-radius: 15px;
        padding: 2rem;
        background: #fff;
    }
    .recuadro-form {
        border: 2px solid #A3D8C5;
        border-radius: 10px;
        padding: 1.5rem;
        background: #fdfdfd;
        height: 100%;
    }
    .recuadro-form h6 {
        color: #D4005B;
        font-weight: bold;
        border-bottom: 2px solid #eee;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }

    .seccion-3-carrusel {
        background-color: #EDB74B;
        padding: 2rem;
        border-radius: 15px;
    }
    .card-carrusel {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    .card-carrusel:hover {
        transform: translateY(-5px);
    }
    .card-carrusel-img {
        background-color: #A3D8C5;
        height: 150px;
        border-radius: 10px 10px 0 0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .card-carrusel-img img {
        max-height: 100%;
        object-fit: contain;
    }
    .btn-agregar-carrusel {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: white;
        color: #333;
        font-size: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin: 0 auto;
    }

    .seccion-4-desglose {
        border: 3px solid #D4005B;
        border-radius: 15px;
        padding: 2rem;
        background: #fff;
    }
    .lista-caracteristicas {
        list-style: none;
        padding-left: 0;
    }
    .lista-caracteristicas li {
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    .lista-caracteristicas strong {
        color: #333;
    }

    .mensaje-flotante {
        display: none;
        padding: 1rem;
        border-radius: 10px;
        text-align: center;
        font-weight: bold;
        margin-top: 1rem;
    }
    .msg-exito { background-color: #d1e7dd; color: #0f5132; border: 2px solid #badbcc; }
    .msg-peligro { background-color: #f8d7da; color: #842029; border: 2px solid #f5c2c7; }
</style>

<div class="container my-5">

    <div class="row seccion-1-banner mb-5 shadow-sm">
        <div class="col-md-7 seccion-1-texto">
            <h1 class="fw-bold mb-2"><?= htmlspecialchars($catTitulo) ?></h1>
            <p class="fs-5 mb-0 text-white-50">Administra los productos de esta categoría. Agrega, edita o elimina los diseños disponibles.</p>
        </div>
        <div class="col-md-5 seccion-1-imagen">
            <img src="/pinatas-mel/imagenes/hamburguesa.png" alt="Imagen de categoría">
        </div>
    </div>

    <div class="seccion-2-form mb-5 shadow-sm">
        <h4 class="mb-4 text-center">Agregar Nuevo Producto</h4>
        <form id="formNuevoProducto" onsubmit="event.preventDefault(); mostrarMensaje('msg-seccion2', '¡Producto guardado en borrador!');">
            <div class="row g-4">
                
                <div class="col-md-4">
                    <div class="recuadro-form">
                        <h6>1. Datos Básicos</h6>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Nombre del Producto</label>
                            <input type="text" class="form-control" placeholder="Ej. Piñata de Fresa" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Precio (MXN)</label>
                            <input type="number" class="form-control" placeholder="450" required>
                        </div>
                        <div>
                            <label class="form-label small text-muted">Descripción Corta (Carrusel)</label>
                            <textarea class="form-control" rows="2" placeholder="Diseñada con papel china..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="recuadro-form">
                        <h6>2. Especificaciones</h6>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Capacidad de dulces</label>
                            <input type="text" class="form-control" placeholder="2 a 4 kg de dulces">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Rendimiento (Niños)</label>
                            <input type="text" class="form-control" placeholder="5 a 20 niños">
                        </div>
                        <div>
                            <label class="form-label small text-muted">Materiales</label>
                            <input type="text" class="form-control" placeholder="Cartón y papel periódico">
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="recuadro-form">
                        <h6>3. Detalles y Foto</h6>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Decoración</label>
                            <textarea class="form-control" rows="2" placeholder="Papel china rojo brillante..."></textarea>
                        </div>
                        <div>
                            <label class="form-label small text-muted">Fotografía del Producto</label>
                            <input type="file" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>

            </div>
            
            <div id="msg-seccion2" class="mensaje-flotante msg-exito"></div>
        </form>
    </div>

    <div class="seccion-3-carrusel mb-5 shadow-sm">
        <h4 class="mb-4 fw-bold text-dark">Productos en <?= htmlspecialchars($catTitulo) ?></h4>
        
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4 align-items-center">
            
            <?php foreach ($productosExistentes as $p): ?>
            <div class="col">
                <div class="card card-carrusel h-100">
                    <div class="card-carrusel-img">
                        <img src="/pinatas-mel/imagenes/<?= $p['imagen'] ?>" alt="<?= $p['nombre'] ?>">
                    </div>
                    <div class="card-body p-3 text-center">
                        <h6 class="fw-bold text-dark mb-1"><?= $p['nombre'] ?></h6>
                        <p class="text-muted small mb-0"><?= $p['descripcion_corta'] ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <div class="col text-center">
                <button class="btn-agregar-carrusel" title="Agregar nuevo producto" onclick="document.getElementById('formNuevoProducto').scrollIntoView({behavior: 'smooth'});">
                    <i class="bi bi-plus"></i>
                </button>
                <small class="d-block mt-2 fw-bold text-dark">Nuevo</small>
            </div>

        </div>
    </div>

    <div class="seccion-4-desglose shadow-sm">
        <div class="row">
            <!-- Imagen del producto -->
            <div class="col-md-4 text-center mb-4 mb-md-0">
                <img src="/pinatas-mel/imagenes/hamburguesa.png" alt="Piñata de Hamburguesa" class="img-fluid" style="max-height: 250px; object-fit: contain;">
            </div>
            
            <div class="col-md-8">
                <h3 class="fw-bold mb-3 text-dark">Piñata de Hamburguesa</h3>
                <p class="fw-bold text-danger fs-5 mb-3">$500.00 MXN</p>
                
                <ul class="lista-caracteristicas text-muted">
                    <li><strong>Capacidad:</strong> 3 a 5 kg de dulces surtidos (o juguetes pequeños y confeti).</li>
                    <li><strong>Rendimiento:</strong> 5 a 25 niños</li>
                    <li><strong>Materiales:</strong> Cartón de alta resistencia y papel periódico/cartonería tradicional.</li>
                    <li><strong>Decoración:</strong> Papel china y crepé picado/plisado en capas de colores vivos (pan, carne, queso, lechuga y jitomate) con detalles en foamy/goma EVA.</li>
                </ul>

                <div class="d-flex gap-3 mt-4">
                    <button type="button" class="btn btn-primario rounded-pill px-5 fw-bold" onclick="document.getElementById('btnAccionesExito').scrollIntoView({behavior: 'smooth'}); mostrarMensaje('msg-seccion4', '¡Producto subido exitosamente!', 'exito');">
                        Subir Producto
                    </button>
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold" onclick="mostrarModalEditar()">
                        Editar
                    </button>
                    <button type="button" class="btn btn-outline-danger rounded-pill px-4 fw-bold" onclick="mostrarModalEliminar()">
                        Eliminar
                    </button>
                </div>

                <!-- Mensaje de éxito Sección 4 -->
                <div id="msg-seccion4" class="mensaje-flotante msg-exito"></div>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content text-center" style="border: 3px solid #D4005B; border-radius: 15px;">
            <div class="modal-body p-4">
                <h5 class="fw-bold mb-3 text-dark">¿Desea Eliminar?</h5>
                <p class="text-muted small mb-4">Esta acción no se puede deshacer.</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger rounded-pill px-4" onclick="confirmarEliminar()" data-bs-dismiss="modal">Sí</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: 3px solid #A3D8C5; border-radius: 15px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Editar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nombre</label>
                    <input type="text" class="form-control" value="Piñata de Hamburguesa">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Precio</label>
                    <input type="number" class="form-control" value="500">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-primario rounded-pill px-4" data-bs-dismiss="modal" onclick="mostrarMensaje('msg-seccion4', '¡Producto editado exitosamente!', 'exito')">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<script>
    function mostrarMensaje(idElemento, texto, tipo = 'exito') {
        const el = document.getElementById(idElemento);
        el.className = `mensaje-flotante ${tipo === 'exito' ? 'msg-exito' : 'msg-peligro'}`;
        el.innerText = texto;
        el.style.display = 'block';
        
        setTimeout(() => {
            el.style.display = 'none';
        }, 4000);
    }

    function mostrarModalEliminar() {
        new bootstrap.Modal(document.getElementById('modalEliminar')).show();
    }
    
    function mostrarModalEditar() {
        new bootstrap.Modal(document.getElementById('modalEditar')).show();
    }

    function confirmarEliminar() {
        mostrarMensaje('msg-seccion4', '¡Se eliminó exitosamente!', 'exito');
    }
</script>

<?php include __DIR__ . '/../../plantilla/footer.php'; ?>