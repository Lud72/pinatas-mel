<?php
// TODO: cuando exista BD, guardar el pedido y el pago reales.

$pago_exitoso = false;
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $direccion = trim($_POST['direccion'] ?? '');
    $telefono  = trim($_POST['tel-personal'] ?? '');
    $titular   = trim($_POST['nombre-titular'] ?? '');
    $tarjeta   = trim($_POST['num-tarjeta'] ?? '');
    $cvv       = trim($_POST['cvv'] ?? '');

    if ($direccion === '') $errores[] = 'La dirección es obligatoria.';
    if ($telefono  === '') $errores[] = 'El teléfono es obligatorio.';
    if ($titular   === '') $errores[] = 'El nombre del titular es obligatorio.';
    if ($tarjeta   === '') $errores[] = 'El número de tarjeta es obligatorio.';
    if ($cvv       === '') $errores[] = 'El código de seguridad es obligatorio.';

    if (empty($errores)) {
        $pago_exitoso = true;
        // TODO: aquí irá el INSERT del pedido en BD.
    }
}

include __DIR__ . '/../../plantilla/header-publico.php';
?>

<div class="container my-5" style="max-width: 850px;">

    <!-- Título -->
    <div class="text-center mb-4 p-3 rounded-3" style="background-color: #EDB74B;">
        <h1 class="fs-3 fw-bold mb-0">Mis Compras</h1>
    </div>

    <!-- Lista de productos (mock hasta tener BD) -->
    <div class="card border-0 rounded-4 p-4 mb-4" style="border: 4px solid #EDB74B !important;">
        <?php
        // TODO: reemplazar por productos reales del carrito (sesión / BD)
        $items = [
            ['img' => 'el_fredo.jpg',        'nombre' => 'Piñata El Fredo',        'precio' => 500],
            ['img' => 'spiderman.jpg',       'nombre' => 'Piñata Spiderman',       'precio' => 500],
            ['img' => 'Capitan_America.jpg', 'nombre' => 'Piñata Capitán América', 'precio' => 500],
            ['img' => 'Mexa_cactus.jpg',     'nombre' => 'Piñata Mexa Cactus',     'precio' => 500],
            ['img' => 'Dino.jpg',            'nombre' => 'Piñata Dino',            'precio' => 500],
        ];
        $total = array_sum(array_column($items, 'precio'));
        ?>

        <?php foreach ($items as $item): ?>
            <div class="d-flex justify-content-between align-items-center border rounded-3 p-3 mb-3">
                <div class="d-flex align-items-center gap-3">
                    <img src="<?= BASE_URL ?>/imagenes/<?= htmlspecialchars($item['img']) ?>"
                         alt="<?= htmlspecialchars($item['nombre']) ?>"
                         style="width:45px;height:45px;object-fit:contain;">
                    <span class="fw-medium"><?= htmlspecialchars($item['nombre']) ?></span>
                </div>
                <div class="d-flex align-items-center gap-4">
                    <span class="fw-bold">$<?= $item['precio'] ?></span>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-light border fw-bold" onclick="updateQty(this, -1)">−</button>
                        <input type="text" class="form-control form-control-sm text-center" value="1" readonly style="width:45px;">
                        <button type="button" class="btn btn-sm btn-light border fw-bold" onclick="updateQty(this, 1)">+</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="text-end mt-2">
            <span class="badge rounded-pill fs-6 px-4 py-2" style="background-color:#EDB74B;color:#333;">
                TOTAL: $<?= $total ?>
            </span>
        </div>
    </div>

    <!-- Errores de validación -->
    <?php if (!empty($errores)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errores as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Botón pagar -->
    <div id="btn-pagar-container" class="text-center mb-4">
        <button type="button" class="btn btn-primario rounded-pill px-5 py-2 fs-5" onclick="goToStep('personal')">Pagar</button>
    </div>

    <!-- Formulario checkout -->
    <form id="checkout-form" action="Carrito.php" method="POST">

        <!-- Paso 1 -->
        <div id="step-personal" class="card border-0 rounded-4 p-4 mb-4 d-none" style="border: 4px solid #A3D8C5 !important;">
            <h2 class="fs-4 text-center mb-4">Datos Personales</h2>

            <div class="mb-3">
                <label for="direccion" class="form-label fw-semibold">Dirección</label>
                <input type="text" id="direccion" name="direccion" class="form-control" placeholder="Ingresa una dirección">
            </div>
            <div class="mb-3">
                <label for="tel-personal" class="form-label fw-semibold">Teléfono</label>
                <input type="tel" id="tel-personal" name="tel-personal" class="form-control" placeholder="Ingresa un número de teléfono">
            </div>

            <div class="text-end">
                <button type="button" class="btn btn-primario rounded-pill px-4" onclick="validatePersonal()">Siguiente</button>
            </div>
        </div>

        <!-- Paso 2 -->
        <div id="step-payment" class="card border-0 rounded-4 p-4 mb-4 d-none" style="border: 4px solid #A3D8C5 !important;">
            <h2 class="fs-4 text-center mb-4">Datos de Pago</h2>

            <div class="row g-3">
                <div class="col-md-8">
                    <label for="nombre-titular" class="form-label fw-semibold">Nombre del Titular</label>
                    <input type="text" id="nombre-titular" name="nombre-titular" class="form-control" placeholder="Ingresa un Nombre">
                </div>
                <div class="col-md-4">
                    <label for="cvv" class="form-label fw-semibold">Código de Seguridad</label>
                    <input type="text" id="cvv" name="cvv" class="form-control" placeholder="123">
                </div>
            </div>

            <div class="mb-3 mt-3">
                <label for="num-tarjeta" class="form-label fw-semibold">Número de Tarjeta</label>
                <input type="text" id="num-tarjeta" name="num-tarjeta" class="form-control" placeholder="Ingresa el número de tu tarjeta">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Fecha de expiración</label>
                <div class="row g-2">
                    <div class="col-6">
                        <select name="mes_exp" class="form-select">
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>"><?= str_pad($m, 2, '0', STR_PAD_LEFT) ?> - <?= date('F', mktime(0,0,0,$m,1)) ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <select name="anio_exp" class="form-select">
                            <?php for ($a = date('Y'); $a <= date('Y') + 10; $a++): ?>
                                <option value="<?= $a ?>"><?= $a ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" onclick="goToStep('personal')">Regresar</button>
                <button type="submit" class="btn btn-primario rounded-pill px-4">Pagar</button>
            </div>
        </div>

    </form>

    <!-- Éxito -->
    <?php if ($pago_exitoso): ?>
        <div class="alert alert-success text-center fs-5 fw-bold rounded-4 p-4">
            ¡Pago Exitoso!
        </div>
    <?php endif; ?>

</div>

<script>
    function goToStep(step) {
        document.getElementById('btn-pagar-container').classList.add('d-none');
        document.getElementById('step-personal').classList.add('d-none');
        document.getElementById('step-payment').classList.add('d-none');

        if (step === 'personal') {
            document.getElementById('step-personal').classList.remove('d-none');
        } else if (step === 'payment') {
            document.getElementById('step-payment').classList.remove('d-none');
        }
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }

    function validatePersonal() {
        const dir = document.getElementById('direccion').value.trim();
        const tel = document.getElementById('tel-personal').value.trim();
        if (dir === '' || tel === '') {
            alert('Por favor completa los datos personales.');
            return;
        }
        goToStep('payment');
    }

    function updateQty(btn, change) {
        const input = btn.parentElement.querySelector('input');
        let val = parseInt(input.value) + change;
        if (val >= 1) input.value = val;
    }
</script>

<?php include __DIR__ . '/../../plantilla/footer.php'; ?>