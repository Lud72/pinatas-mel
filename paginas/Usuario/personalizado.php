<?php
// TODO: cuando exista BD, guardar el pedido personalizado y la imagen subida.

$mensaje_exito = false;
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = trim($_POST['nombre'] ?? '');
    $telefono  = trim($_POST['telefono'] ?? '');
    $personaje = trim($_POST['personaje'] ?? '');
    $texto     = trim($_POST['texto-personalizado'] ?? '');
    $tamano    = trim($_POST['tamano'] ?? '');
    $aperturas = $_POST['apertura'] ?? [];

    if ($nombre   === '') $errores[] = 'El nombre es obligatorio.';
    if ($telefono === '') $errores[] = 'El teléfono es obligatorio.';
    if ($personaje === '') $errores[] = 'Describe qué personaje, forma u objeto quieres.';
    if ($tamano   === '') $errores[] = 'El tamaño es obligatorio.';

    // Validación opcional de la imagen
    $imagen_ok = true;
    if (isset($_FILES['referencia']) && $_FILES['referencia']['error'] === UPLOAD_ERR_OK) {
        $tipos = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($_FILES['referencia']['type'], $tipos, true)) {
            $errores[] = 'La imagen debe ser JPG, PNG o WEBP.';
            $imagen_ok = false;
        }
    }

    if (empty($errores) && $imagen_ok) {
        $mensaje_exito = true;
        // TODO: mover imagen a /uploads y guardar el pedido en BD.
    }
}

include __DIR__ . '/../../plantilla/header-publico.php';
?>

<div class="container my-5" style="max-width: 900px;">

    <!-- Hero -->
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-4 mb-4">
        <img src="<?= BASE_URL ?>/imagenes/Pinata_per_crea.png" alt="Zona de creación" style="max-width:220px;">
        <div class="text-center text-md-start">
            <h1 class="fs-3 fw-bold mb-2">¡Llegaste a la zona de creación!</h1>
            <p class="fs-5 mb-0">Platícanos tu idea y hazla realidad</p>
        </div>
    </div>

    <!-- Errores -->
    <?php if (!empty($errores)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errores as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Formulario -->
    <div class="card border-0 rounded-4 p-4" style="border: 3px solid #A3D8C5 !important;">
        <form id="customization-form" action="personalizado.php" method="POST" enctype="multipart/form-data">

            <!-- Paso 1 -->
            <div id="step-1" class="form-step">
                <h2 class="fs-4 text-center mb-4">Datos de Contacto</h2>

                <div class="mb-3">
                    <label for="nombre" class="form-label fw-semibold">Nombre Completo</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ingresa un Nombre" required>
                </div>

                <div class="mb-3">
                    <label for="telefono" class="form-label fw-semibold">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" class="form-control" placeholder="Ingresa el número de teléfono" required>
                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-primario rounded-pill px-4" onclick="nextStep(2)">Siguiente</button>
                </div>
            </div>

            <!-- Paso 2 -->
            <div id="step-2" class="form-step d-none">
                <h2 class="fs-4 text-center mb-4">Detalles del Diseño</h2>

                <div class="mb-3">
                    <label for="personaje" class="form-label fw-semibold">¿Qué personaje, forma u objeto quieres?</label>
                    <input type="text" id="personaje" name="personaje" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="referencia" class="form-label fw-semibold">Sube una imagen de referencia / foto</label>
                    <input type="file" id="referencia" name="referencia" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label for="texto-personalizado" class="form-label fw-semibold">Texto o Nombre personalizado en la piñata</label>
                    <input type="text" id="texto-personalizado" name="texto-personalizado" class="form-control">
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" onclick="nextStep(1)">Regresar</button>
                    <button type="button" class="btn btn-primario rounded-pill px-4" onclick="nextStep(3)">Siguiente</button>
                </div>
            </div>

            <!-- Paso 3 -->
            <div id="step-3" class="form-step d-none">
                <h2 class="fs-4 text-center mb-4">Detalles de Tamaño</h2>

                <div class="mb-3">
                    <label for="tamano" class="form-label fw-semibold">Tamaño deseado (cm)</label>
                    <input type="number" id="tamano" name="tamano" class="form-control" min="1">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold d-block">Tipo de apertura</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="apertura[]" value="tradicional" id="ap-trad">
                        <label class="form-check-label" for="ap-trad">De Palo / Tradicional</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="apertura[]" value="cintas" id="ap-cintas">
                        <label class="form-check-label" for="ap-cintas">De Cintas / Jalón</label>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" onclick="nextStep(2)">Regresar</button>
                    <button type="submit" class="btn btn-primario rounded-pill px-4">Añadir al Carrito</button>
                </div>
            </div>

        </form>
    </div>

    <!-- Éxito -->
    <?php if ($mensaje_exito): ?>
        <div class="alert alert-success text-center fs-5 fw-bold rounded-4 p-4 mt-4">
            Se agregó exitosamente
        </div>
    <?php endif; ?>

</div>

<script>
    function nextStep(stepNumber) {
        document.querySelectorAll('.form-step').forEach(step => step.classList.add('d-none'));
        document.getElementById('step-' + stepNumber).classList.remove('d-none');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>

<?php include __DIR__ . '/../../plantilla/footer.php'; ?>