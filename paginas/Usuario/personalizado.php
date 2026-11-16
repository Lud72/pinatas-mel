<?php
require_once __DIR__ . '/../../config.php';

// CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

// Mensajes flash
$errores = $_SESSION['errores_personalizado'] ?? [];
$exito   = $_SESSION['exito_personalizado'] ?? false;
$old     = $_SESSION['old_personalizado'] ?? [];
unset($_SESSION['errores_personalizado'], $_SESSION['exito_personalizado'], $_SESSION['old_personalizado']);

$val = fn($k) => htmlspecialchars($old[$k] ?? '', ENT_QUOTES, 'UTF-8');
$oldAperturas = $old['apertura'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personalización - Piñatas-Mel</title>
    <link rel="stylesheet" href="../../estilos/personalizado.css">
</head>
<body>

<?php require __DIR__ . '/../../plantilla/header-publico.php'; ?>

<main class="creation-zone">
    <div class="hero-content">
        <div class="hero-image-container">
            <img src="../../imagenes/Pinata_per_crea.png" alt="Zona de creación" class="hero-img">
        </div>
        <div class="hero-text">
            <h1>¡Llegaste a la zona de creación!</h1>
            <p>Platícanos tu idea y hazla realidad</p>
        </div>
    </div>

    <?php if ($exito): ?>
        <div class="success-banner"><p>Se agregó exitosamente al carrito.</p></div>
    <?php endif; ?>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-danger">
            <strong>Hubo errores:</strong>
            <ul class="mb-0">
                <?php foreach ($errores as $e): ?>
                    <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <section class="form-card">
        <form id="customization-form" action="procesar_personalizado.php" method="POST" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
            <input type="hidden" name="step" id="step-actual" value="<?= (int)($old['step'] ?? 1) ?>">

            <!-- Paso 1 -->
            <div id="step-1" class="form-step <?= ($old['step'] ?? 1) == 1 ? 'active' : '' ?>">
                <h2>Datos de Contacto</h2>
                <div class="form-group">
                    <label for="nombre">Nombre Completo</label>
                    <input type="text" id="nombre" name="nombre" maxlength="100" required
                           value="<?= $val('nombre') ?>" placeholder="Ingresa un Nombre">
                    <small class="error-msg" data-for="nombre"></small>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" maxlength="20" required
                           pattern="[0-9+\-\s()]{7,20}" value="<?= $val('telefono') ?>"
                           placeholder="Ingresa el número de teléfono">
                    <small class="error-msg" data-for="telefono"></small>
                </div>
                <div class="button-group single-right">
                    <button type="button" class="btn btn-primary" onclick="nextStep(2)">Siguiente</button>
                </div>
            </div>

            <!-- Paso 2 -->
            <div id="step-2" class="form-step <?= ($old['step'] ?? 1) == 2 ? 'active' : '' ?>">
                <h2>Detalles del Diseño</h2>
                <div class="form-group">
                    <label for="personaje">¿Qué personaje, forma u objeto quieres?</label>
                    <input type="text" id="personaje" name="personaje" maxlength="255" required
                           value="<?= $val('personaje') ?>">
                    <small class="error-msg" data-for="personaje"></small>
                </div>
                <div class="form-group">
                    <label for="referencia">Sube hasta 5 imágenes de referencia / fotos</label>
                    <div class="file-upload-box">
                        <input type="file" id="referencia" name="referencia[]" accept="image/*" multiple>
                    </div>
                    <small class="error-msg" data-for="referencia"></small>
                </div>
                <div class="form-group">
                    <label for="texto_personalizado">Texto o Nombre personalizado en la piñata</label>
                    <input type="text" id="texto_personalizado" name="texto_personalizado" maxlength="100"
                           value="<?= $val('texto_personalizado') ?>">
                </div>
                <div class="button-group">
                    <button type="button" class="btn btn-secondary" onclick="nextStep(1)">Regresar</button>
                    <button type="button" class="btn btn-primary" onclick="nextStep(3)">Siguiente</button>
                </div>
            </div>

            <!-- Paso 3 -->
            <div id="step-3" class="form-step <?= ($old['step'] ?? 1) == 3 ? 'active' : '' ?>">
                <h2>Detalles de Tamaño</h2>
                <div class="form-group">
                    <label for="tamano">Tamaño deseado (cm) — entre <?= TAMANO_MIN ?> y <?= TAMANO_MAX ?></label>
                    <input type="number" id="tamano" name="tamano" min="<?= TAMANO_MIN ?>" max="<?= TAMANO_MAX ?>" required
                           value="<?= $val('tamano') ?>">
                    <small class="error-msg" data-for="tamano"></small>
                </div>
                <div class="form-group">
                    <label class="label-title">Tipo de apertura (al menos una)</label>
                    <label class="checkbox-container">
                        <input type="checkbox" name="apertura[]" value="tradicional"
                            <?= in_array('tradicional', $oldAperturas, true) ? 'checked' : '' ?>>
                        <span class="checkmark"></span> De Palo / Tradicional
                    </label>
                    <label class="checkbox-container">
                        <input type="checkbox" name="apertura[]" value="cintas"
                            <?= in_array('cintas', $oldAperturas, true) ? 'checked' : '' ?>>
                        <span class="checkmark"></span> De Cintas / Jalón
                    </label>
                    <small class="error-msg" data-for="apertura"></small>
                </div>
                <div class="button-group">
                    <button type="button" class="btn btn-secondary" onclick="nextStep(2)">Regresar</button>
                    <button type="submit" class="btn btn-primary">Añadir al Carrito</button>
                </div>
            </div>
        </form>
    </section>
</main>

<?php require __DIR__ . '/../../plantilla/footer.php'; ?>

<script>
function mostrarError(id, msg) {
    const el = document.querySelector(`.error-msg[data-for="${id}"]`);
    if (el) el.textContent = msg;
}

function limpiarErroresPaso(step) {
    document.querySelectorAll(`#step-${step} .error-msg`).forEach(e => e.textContent = '');
}

function validarPaso(step) {
    limpiarErroresPaso(step);
    let ok = true;

    if (step === 1) {
        const nombre = document.getElementById('nombre');
        const tel = document.getElementById('telefono');
        if (!nombre.value.trim() || nombre.value.length > 100) {
            mostrarError('nombre', 'El nombre es obligatorio (máx. 100).'); ok = false;
        }
        if (!/^[0-9+\-\s()]{7,20}$/.test(tel.value.trim())) {
            mostrarError('telefono', 'Teléfono inválido (7–20 caracteres).'); ok = false;
        }
    }
    if (step === 2) {
        const personaje = document.getElementById('personaje');
        if (!personaje.value.trim()) {
            mostrarError('personaje', 'Describe el personaje.'); ok = false;
        }
        const files = document.getElementById('referencia').files;
        if (files.length > 5) {
            mostrarError('referencia', 'Máximo 5 imágenes.'); ok = false;
        }
        for (const f of files) {
            if (f.size > 5 * 1024 * 1024) {
                mostrarError('referencia', 'Cada imagen debe pesar menos de 5 MB.'); ok = false; break;
            }
        }
    }
    if (step === 3) {
        const t = parseInt(document.getElementById('tamano').value, 10);
        if (isNaN(t) || t < 30 || t > 200) {
            mostrarError('tamano', 'Tamaño entre 30 y 200 cm.'); ok = false;
        }
        const ap = document.querySelectorAll('input[name="apertura[]"]:checked');
        if (ap.length === 0) {
            mostrarError('apertura', 'Selecciona al menos una apertura.'); ok = false;
        }
    }
    return ok;
}

function nextStep(stepNumber) {
    const actual = parseInt(document.getElementById('step-actual').value, 10);
    // Validar el paso actual antes de avanzar
    if (stepNumber > actual && !validarPaso(actual)) return;

    document.querySelectorAll('.form-step').forEach(s => s.classList.remove('active'));
    document.getElementById('step-' + stepNumber).classList.add('active');
    document.getElementById('step-actual').value = stepNumber;
    document.getElementById('customization-form').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Validación final antes de submit
document.getElementById('customization-form').addEventListener('submit', function (e) {
    if (!validarPaso(1) || !validarPaso(2) || !validarPaso(3)) {
        e.preventDefault();
        // Volver al primer paso con error
        for (let i = 1; i <= 3; i++) {
            if (document.querySelector(`#step-${i} .error-msg:not(:empty)`)) {
                document.querySelectorAll('.form-step').forEach(s => s.classList.remove('active'));
                document.getElementById('step-' + i).classList.add('active');
                document.getElementById('step-actual').value = i;
                break;
            }
        }
    }
});

// Restaurar paso activo del servidor al cargar
document.getElementById('step-actual').value = <?= (int)($old['step'] ?? 1) ?>;
</script>
</body>
</html>