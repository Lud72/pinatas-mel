<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';       // inicia sesión
require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../../modelos/carrito.php';
require_once __DIR__ . '/../../modelos/productos.php';

// ----- 1. Procesar acciones sobre el carrito -----

// Agregar producto
if (isset($_GET['add'])) {
    $slug = trim($_GET['add']);
    if ($slug !== '' && obtenerProductoPorSlug($pdo, $slug) !== null) {
        carritoAgregar($slug, 1);
    }
    header('Location: Carrito.php');
    exit;
}

// Eliminar producto
if (isset($_GET['remove'])) {
    carritoEliminar(trim($_GET['remove']));
    header('Location: Carrito.php');
    exit;
}

// Cambiar cantidad (POST desde la misma página)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'actualizar') {
    $slug     = $_POST['slug'] ?? '';
    $cantidad = max(0, (int)($_POST['cantidad'] ?? 0));
    if ($slug !== '') {
        if ($cantidad === 0) {
            carritoEliminar($slug);
        } else {
            $_SESSION['carrito'][$slug] = $cantidad;
        }
    }
    header('Location: Carrito.php');
    exit;
}

// ----- 2. Datos del carrito -----

$items = carritoDetallado($pdo);
$total = array_sum(array_column($items, 'subtotal'));

// ----- 3. Procesar checkout (POST del formulario de pago) -----

$pago_exitoso = false;
$folioPedido  = '';
$errores      = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'pagar') {
    $direccion = trim($_POST['direccion'] ?? '');
    $telefono  = trim($_POST['tel-personal'] ?? '');
    $nombre    = trim($_POST['nombre-cliente'] ?? '');
    $titular   = trim($_POST['nombre-titular'] ?? '');
    $tarjeta   = trim($_POST['num-tarjeta'] ?? '');
    $cvv       = trim($_POST['cvv'] ?? '');

    if ($nombre    === '') $errores[] = 'El nombre del cliente es obligatorio.';
    if ($direccion === '') $errores[] = 'La dirección es obligatoria.';
    if ($telefono  === '') $errores[] = 'El teléfono es obligatorio.';
    if ($titular   === '') $errores[] = 'El nombre del titular es obligatorio.';
    if ($tarjeta   === '') $errores[] = 'El número de tarjeta es obligatorio.';
    if ($cvv       === '') $errores[] = 'El código de seguridad es obligatorio.';

    if (empty($items)) {
        $errores[] = 'El carrito está vacío.';
    }

    if (empty($errores)) {
        try {
            $pdo->beginTransaction();

            // Generar folio: PM-YYYYMMDD-XXXX (4 dígitos aleatorios)
            $folio = 'PM-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));

            // Insertar pedido
            $stmt = $pdo->prepare("
                INSERT INTO pedidos
                    (folio, cliente_nombre, cliente_telefono, cliente_direccion, total, estado)
                VALUES
                    (:folio, :nombre, :telefono, :direccion, :total, 'pendiente')
            ");
            $stmt->execute([
                'folio'     => $folio,
                'nombre'    => $nombre,
                'telefono'  => $telefono,
                'direccion' => $direccion,
                'total'     => $total,
            ]);
            $pedidoId = (int)$pdo->lastInsertId();

            // Insertar items
            $stmtItem = $pdo->prepare("
                INSERT INTO pedido_items (pedido_id, producto_id, cantidad, precio_unitario)
                VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario)
            ");
            foreach ($items as $item) {
                $stmtItem->execute([
                    'pedido_id'       => $pedidoId,
                    'producto_id'     => $item['id'],
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                ]);
            }

            $pdo->commit();

            // Vaciar carrito
            carritoVaciar();

            $pago_exitoso = true;
            $folioPedido  = $folio;
        } catch (PDOException $e) {
            $pdo->rollBack();
            $errores[] = 'Error al procesar el pedido. Intenta de nuevo.';
            // En desarrollo puedes descomentar para ver el error real:
            // $errores[] = $e->getMessage();
        }
    }
}

// Recargar items (por si el pago vació el carrito)
$items = carritoDetallado($pdo);
$total = array_sum(array_column($items, 'subtotal'));

include __DIR__ . '/../../plantilla/header-publico.php';
?>

<div class="container my-5" style="max-width: 850px;">

    <!-- Título -->
    <div class="text-center mb-4 p-3 rounded-3" style="background-color: #EDB74B;">
        <h1 class="fs-3 fw-bold mb-0">Mis Compras</h1>
    </div>

    <!-- Éxito -->
    <?php if ($pago_exitoso): ?>
        <div class="alert alert-success text-center rounded-4 p-4">
            <h4 class="fw-bold mb-2">¡Pago Exitoso!</h4>
            <p class="mb-1">Tu folio de pedido es:</p>
            <p class="fs-4 fw-bold mb-0"><?= htmlspecialchars($folioPedido) ?></p>
        </div>
    <?php endif; ?>

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

    <!-- Carrito vacío -->
    <?php if (empty($items) && !$pago_exitoso): ?>
        <div class="card border-0 rounded-4 p-5 text-center" style="border: 4px solid #EDB74B !important;">
            <p class="fs-5 text-muted mb-3">Tu carrito está vacío.</p>
            <a href="<?= BASE_URL ?>/paginas/Usuario/catalogo.php" 
               class="btn btn-primario rounded-pill px-4">
                Ir al catálogo
            </a>
        </div>

    <?php elseif (!empty($items)): ?>

        <!-- Lista de items -->
        <div class="card border-0 rounded-4 p-4 mb-4" style="border: 4px solid #EDB74B !important;">
            <?php foreach ($items as $item): ?>
                <div class="d-flex justify-content-between align-items-center border rounded-3 p-3 mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <img src="<?= BASE_URL ?>/imagenes/<?= htmlspecialchars($item['imagen']) ?>"
                             alt="<?= htmlspecialchars($item['nombre']) ?>"
                             style="width:50px;height:50px;object-fit:contain;">
                        <div>
                            <div class="fw-semibold"><?= htmlspecialchars($item['nombre']) ?></div>
                            <small class="text-muted">$<?= number_format($item['precio'], 2) ?> c/u</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <!-- Cantidad -->
                        <form method="POST" action="Carrito.php" class="d-flex align-items-center gap-2 m-0">
                            <input type="hidden" name="accion" value="actualizar">
                            <input type="hidden" name="slug" value="<?= htmlspecialchars($item['slug']) ?>">
                            <input type="number" name="cantidad" value="<?= $item['cantidad'] ?>"
                                   min="0" class="form-control form-control-sm text-center" style="width:65px;">
                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Actualizar">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </form>

                        <!-- Subtotal -->
                        <span class="fw-bold" style="min-width:80px;text-align:right;">
                            $<?= number_format($item['subtotal'], 2) ?>
                        </span>

                        <!-- Eliminar -->
                        <a href="Carrito.php?remove=<?= urlencode($item['slug']) ?>"
                           class="btn btn-sm btn-outline-danger" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="text-end mt-2">
                <span class="badge rounded-pill fs-6 px-4 py-2" style="background-color:#EDB74B;color:#333;">
                    TOTAL: $<?= number_format($total, 2) ?>
                </span>
            </div>
        </div>

        <!-- Botón pagar -->
        <div id="btn-pagar-container" class="text-center mb-4">
            <button type="button" class="btn btn-primario rounded-pill px-5 py-2 fs-5" onclick="goToStep('personal')">
                Pagar
            </button>
        </div>

        <!-- Formulario de checkout -->
        <form id="checkout-form" action="Carrito.php" method="POST">
            <input type="hidden" name="accion" value="pagar">

            <!-- Paso 1 -->
            <div id="step-personal" class="card border-0 rounded-4 p-4 mb-4 d-none" style="border: 4px solid #A3D8C5 !important;">
                <h2 class="fs-4 text-center mb-4">Datos Personales</h2>

                <div class="mb-3">
                    <label for="nombre-cliente" class="form-label fw-semibold">Nombre completo</label>
                    <input type="text" id="nombre-cliente" name="nombre-cliente" class="form-control" placeholder="Tu nombre">
                </div>

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
                        <input type="text" id="nombre-titular" name="nombre-titular" class="form-control" placeholder="Nombre en la tarjeta">
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
                                    <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>">
                                        <?= str_pad($m, 2, '0', STR_PAD_LEFT) ?> - <?= date('F', mktime(0,0,0,$m,1)) ?>
                                    </option>
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
        const nombre = document.getElementById('nombre-cliente').value.trim();
        const dir    = document.getElementById('direccion').value.trim();
        const tel    = document.getElementById('tel-personal').value.trim();
        if (nombre === '' || dir === '' || tel === '') {
            alert('Por favor completa nombre, dirección y teléfono.');
            return;
        }
        goToStep('payment');
    }
</script>

<?php include __DIR__ . '/../../plantilla/footer.php'; ?>