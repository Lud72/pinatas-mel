<?php
// /pinatas-mel/paginas/Usuario/checkout.php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../../funciones/carrito.php';

// CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

// --- Cargar carrito ---
$items   = listarCarrito($pdo);
$totales = calcularTotales($items);

if (empty($items)) {
    $_SESSION['errores_checkout'] = ['Tu carrito está vacío.'];
    header('Location: Carrito.php');
    exit;
}

$errores = $_SESSION['errores_checkout'] ?? [];
$old     = $_SESSION['old_checkout']     ?? [];
unset($_SESSION['errores_checkout'], $_SESSION['old_checkout']);

$val = fn($k) => htmlspecialchars($old[$k] ?? '', ENT_QUOTES, 'UTF-8');

// --- POST: crear pedido ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errores[] = 'Token inválido. Recarga la página.';
    }

    $cliente   = trim($_POST['cliente_nombre'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $telefono  = trim($_POST['telefono'] ?? '');
    $metodo    = $_POST['metodo_pago'] ?? '';
    $cumple    = !empty($_POST['descuento_cumple']);

    if ($cliente === '' || mb_strlen($cliente) > 150)   $errores[] = 'El nombre es obligatorio (máx. 150).';
    if ($direccion === '' || mb_strlen($direccion) > 255) $errores[] = 'La dirección es obligatoria (máx. 255).';
    if (!preg_match('/^[0-9+\-\s()]{7,20}$/', $telefono)) $errores[] = 'Teléfono inválido.';
    if (!in_array($metodo, ['efectivo', 'transferencia', 'contra_entrega'], true)) {
        $errores[] = 'Selecciona un método de pago válido.';
    }

    // Si hay ítems por cotizar, no permitir pago en línea
    if ($totales['hay_por_cotizar'] && in_array($metodo, ['transferencia'], true)) {
        $errores[] = 'Tienes piñatas personalizadas por cotizar; el pago en línea se habilita tras la cotización.';
    }

    // Calcular descuento (15% cumpleaños)
    $descuentoPorcentaje = $cumple ? 15 : 0;
    $descuentoMonto      = round($totales['subtotal'] * $descuentoPorcentaje / 100, 2);
    $totalFinal          = max(0, $totales['subtotal'] - $descuentoMonto);

    if (empty($errores)) {
        try {
            $pdo->beginTransaction();

            $sqlPedido = "INSERT INTO pedidos
                (cliente_nombre, direccion, telefono, metodo_pago, descuento, total, estado)
                VALUES (:nombre, :dir, :tel, :metodo, :desc, :total, :estado)";
            $stmt = $pdo->prepare($sqlPedido);
            $stmt->execute([
                ':nombre' => $cliente,
                ':dir'    => $direccion,
                ':tel'    => $telefono,
                ':metodo' => $metodo,
                ':desc'   => $descuentoMonto,
                ':total'  => $totalFinal,
                ':estado' => $totales['hay_por_cotizar'] ? 'pendiente' : 'pendiente',
            ]);
            $pedidoId = (int)$pdo->lastInsertId();

            $stmtDetalle = $pdo->prepare(
                "INSERT INTO pedido_detalle
                 (pedido_id, producto_id, nombre_producto, cantidad, precio_unitario)
                 VALUES (:ped, :prod, :nom, :cant, :precio)"
            );

            foreach ($items as $it) {
                $stmtDetalle->execute([
                    ':ped'    => $pedidoId,
                    ':prod'   => $it['producto_id'] ?? null,
                    ':nom'    => $it['nombre'],
                    ':cant'   => (int)$it['cantidad'],
                    ':precio' => (float)$it['precio_unitario'],
                ]);
            }

            // Vaciar carrito
            vaciarCarrito($pdo);

            $pdo->commit();

            $_SESSION['pedido_confirmado'] = [
                'id'     => $pedidoId,
                'total'  => $totalFinal,
                'estado' => 'pendiente',
                'hay_por_cotizar' => $totales['hay_por_cotizar'],
            ];
            header('Location: checkout.php?ok=1');
            exit;

        } catch (PDOException $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            error_log('[checkout] ' . $e->getMessage(), 3, BASE_PATH . '/logs/errores.log');
            $errores[] = 'No pudimos procesar el pedido. Intenta de nuevo.';
            $old = $_POST;
        }
    } else {
        $old = $_POST;
    }
}

// Si viene ?ok=1 y hay pedido confirmado en sesión, mostrar pantalla de gracias
$confirmado = null;
if (isset($_GET['ok']) && !empty($_SESSION['pedido_confirmado'])) {
    $confirmado = $_SESSION['pedido_confirmado'];
    unset($_SESSION['pedido_confirmado']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar pedido - Piñatas-Mel</title>
    <link rel="stylesheet" href="../../estilos/checkout.css">
</head>
<body>

<?php require __DIR__ . '/../../plantilla/header-publico.php'; ?>

<div class="checkout-container">

<?php if ($confirmado): ?>
    <!-- Pantalla de confirmación -->
    <section class="checkout-gracias">
        <h1>¡Pedido recibido!</h1>
        <p>Tu pedido <strong>#<?= (int)$confirmado['id'] ?></strong> fue registrado correctamente.</p>
        <p>Total: <strong>$<?= number_format((float)$confirmado['total'], 2) ?></strong></p>

        <?php if (!empty($confirmado['hay_por_cotizar'])): ?>
            <div class="aviso-cotizar">
                Tienes piñatas personalizadas. Nos pondremos en contacto contigo para cotizarlas antes de continuar con el pago.
            </div>
        <?php endif; ?>

        <a href="catalogo.php" class="btn btn-primary">Seguir comprando</a>
    </section>

<?php else: ?>
    <!-- Formulario de checkout -->
    <h1 class="checkout-titulo">Finalizar pedido</h1>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errores as $e): ?>
                    <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="checkout-grid">

        <form method="POST" action="checkout.php" class="checkout-form" novalidate>
            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

            <h2>Datos de envío</h2>

            <div class="form-group">
                <label for="cliente_nombre">Nombre completo *</label>
                <input type="text" id="cliente_nombre" name="cliente_nombre" maxlength="150" required
                       value="<?= $val('cliente_nombre') ?>">
            </div>

            <div class="form-group">
                <label for="direccion">Dirección *</label>
                <input type="text" id="direccion" name="direccion" maxlength="255" required
                       value="<?= $val('direccion') ?>">
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono *</label>
                <input type="tel" id="telefono" name="telefono" maxlength="20" required
                       pattern="[0-9+\-\s()]{7,20}"
                       value="<?= $val('telefono') ?>">
            </div>

            <h2>Método de pago</h2>
            <div class="metodos-pago">
                <label class="metodo-card">
                    <input type="radio" name="metodo_pago" value="efectivo"
                        <?= (($old['metodo_pago'] ?? '') === 'efectivo') ? 'checked' : '' ?>>
                    <span>Efectivo contra entrega</span>
                </label>
                <label class="metodo-card">
                    <input type="radio" name="metodo_pago" value="transferencia"
                        <?= (($old['metodo_pago'] ?? '') === 'transferencia') ? 'checked' : '' ?>>
                    <span>Transferencia</span>
                </label>
                <label class="metodo-card">
                    <input type="radio" name="metodo_pago" value="contra_entrega"
                        <?= (($old['metodo_pago'] ?? '') === 'contra_entrega') ? 'checked' : '' ?>>
                    <span>Contra entrega</span>
                </label>
            </div>

            <h2>Descuento</h2>
            <label class="checkbox-container">
                <input type="checkbox" name="descuento_cumple" value="1"
                    <?= !empty($old['descuento_cumple']) ? 'checked' : '' ?>>
                <span class="checkmark"></span>
                Aplicar 15% de descuento por cumpleaños
            </label>

            <button type="submit" class="btn btn-primary btn-block">Confirmar pedido</button>
        </form>

        <aside class="checkout-resumen">
            <h2>Resumen</h2>
            <?php foreach ($items as $it):
                $precio = (float)$it['precio_unitario'];
                $porCotizar = $precio <= 0;
            ?>
                <div class="resumen-linea">
                    <span><?= (int)$it['cantidad'] ?>× <?= htmlspecialchars($it['nombre'], ENT_QUOTES, 'UTF-8') ?></span>
                    <span>
                        <?php if ($porCotizar): ?>
                            <em>Por cotizar</em>
                        <?php else: ?>
                            $<?= number_format($precio * (int)$it['cantidad'], 2) ?>
                        <?php endif; ?>
                    </span>
                </div>
            <?php endforeach; ?>

            <hr>
            <div class="resumen-linea">
                <span>Subtotal</span>
                <strong>$<?= number_format($totales['subtotal'], 2) ?></strong>
            </div>

            <?php if ($totales['hay_por_cotizar']): ?>
                <p class="aviso-cotizar">
                    Incluye piñatas personalizadas que se cotizarán antes de confirmar el total final.
                </p>
            <?php endif; ?>
        </aside>

    </div>
<?php endif; ?>
</div>

</main>

<?php require __DIR__ . '/../../plantilla/footer.php'; ?>
</body>
</html>