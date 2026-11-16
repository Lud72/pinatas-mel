<?php
// /pinatas-mel/paginas/Usuario/Carrito.php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../../funciones/carrito.php';

// --- Acciones POST (actualizar cantidad / eliminar / vaciar) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
        $_SESSION['errores_carrito'] = ['Token inválido. Recarga la página.'];
        header('Location: Carrito.php');
        exit;
    }

    $accion = $_POST['accion'] ?? '';

    try {
        if ($accion === 'actualizar' && isset($_POST['item_id'], $_POST['cantidad'])) {
            actualizarCantidad($pdo, (int)$_POST['item_id'], (int)$_POST['cantidad']);
        } elseif ($accion === 'eliminar' && isset($_POST['item_id'])) {
            eliminarItem($pdo, (int)$_POST['item_id']);
        } elseif ($accion === 'vaciar') {
            vaciarCarrito($pdo);
        }
    } catch (PDOException $e) {
        error_log('[carrito] ' . $e->getMessage(), 3, BASE_PATH . '/logs/errores.log');
        $_SESSION['errores_carrito'] = ['No pudimos actualizar el carrito. Intenta de nuevo.'];
    }

    header('Location: Carrito.php');
    exit;
}

// --- CSRF ---
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

// --- Datos ---
$items    = listarCarrito($pdo);
$totales  = calcularTotales($items);
$errores  = $_SESSION['errores_carrito'] ?? [];
$exito    = $_SESSION['exito_carrito']   ?? false;
unset($_SESSION['errores_carrito'], $_SESSION['exito_carrito']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito - Piñatas-Mel</title>
    <link rel="stylesheet" href="../../estilos/carrito.css">
</head>
<body>

<?php require __DIR__ . '/../../plantilla/header-publico.php'; ?>

<main class="carrito-container">
    <h1 class="carrito-titulo">Tu Carrito</h1>

    <?php if ($exito): ?>
        <div class="alert alert-success">Carrito actualizado.</div>
    <?php endif; ?>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errores as $e): ?>
                    <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (empty($items)): ?>
        <div class="carrito-vacio">
            <p>Tu carrito está vacío.</p>
            <a href="catalogo.php" class="btn btn-primary">Ver catálogo</a>
            <a href="personalizado.php" class="btn btn-secondary">Crear piñata personalizada</a>
        </div>
    <?php else: ?>
        <div class="carrito-grid">
            <section class="carrito-items">
                <?php foreach ($items as $it):
                    $esPersonalizado = !empty($it['pedido_personalizado_id']);
                    $precio = (float)$it['precio_unitario'];
                    $porCotizar = $precio <= 0;
                    $imagen = $esPersonalizado
                        ? ($it['referencia_imagen'] ? UPLOADS_REF_URL . $it['referencia_imagen'] : '../../imagenes/placeholder.png')
                        : '../../imagenes/productos/' . $it['producto_imagen'];
                ?>
                <article class="carrito-item">
                    <img src="<?= htmlspecialchars($imagen, ENT_QUOTES, 'UTF-8') ?>"
                         alt="<?= htmlspecialchars($it['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                         class="carrito-item-img">

                    <div class="carrito-item-info">
                        <h3><?= htmlspecialchars($it['nombre'], ENT_QUOTES, 'UTF-8') ?></h3>

                        <?php if ($esPersonalizado): ?>
                            <p class="carrito-item-detalle">
                                <span class="badge">Personalizada</span>
                                <?php if ($it['tamano_cm']): ?> · <?= (int)$it['tamano_cm'] ?> cm<?php endif; ?>
                                <?php if ($it['tipo_apertura']): ?> · <?= htmlspecialchars($it['tipo_apertura'], ENT_QUOTES, 'UTF-8') ?><?php endif; ?>
                            </p>
                            <p class="carrito-item-detalle">
                                <strong>Estado:</strong>
                                <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $it['pp_estado'] ?? 'pendiente')), ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        <?php endif; ?>

                        <p class="carrito-item-precio">
                            <?php if ($porCotizar): ?>
                                <em>Precio por cotizar</em>
                            <?php else: ?>
                                $<?= number_format($precio, 2) ?> c/u
                            <?php endif; ?>
                        </p>
                    </div>

                    <div class="carrito-item-cantidad">
                        <form method="POST" action="Carrito.php" class="cantidad-form">
                            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                            <input type="hidden" name="accion" value="actualizar">
                            <input type="hidden" name="item_id" value="<?= (int)$it['id'] ?>">
                            <button type="submit" name="cantidad" value="<?= (int)$it['cantidad'] - 1 ?>" class="btn-cant">−</button>
                            <span class="cantidad-valor"><?= (int)$it['cantidad'] ?></span>
                            <button type="submit" name="cantidad" value="<?= (int)$it['cantidad'] + 1 ?>" class="btn-cant">+</button>
                        </form>
                    </div>

                    <div class="carrito-item-subtotal">
                        <?php if ($porCotizar): ?>
                            —
                        <?php else: ?>
                            $<?= number_format($precio * (int)$it['cantidad'], 2) ?>
                        <?php endif; ?>
                    </div>

                    <form method="POST" action="Carrito.php" class="carrito-item-eliminar">
                        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                        <input type="hidden" name="accion" value="eliminar">
                        <input type="hidden" name="item_id" value="<?= (int)$it['id'] ?>">
                        <button type="submit" class="btn-eliminar" aria-label="Eliminar">✕</button>
                    </form>
                </article>
                <?php endforeach; ?>

                <form method="POST" action="Carrito.php" class="carrito-vaciar">
                    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                    <input type="hidden" name="accion" value="vaciar">
                    <button type="submit" class="btn btn-link">Vaciar carrito</button>
                </form>
            </section>

            <aside class="carrito-resumen">
                <h2>Resumen</h2>
                <p><span>Unidades:</span> <strong><?= $totales['unidades'] ?></strong></p>
                <p><span>Subtotal:</span> <strong>$<?= number_format($totales['subtotal'], 2) ?></strong></p>

                <?php if ($totales['hay_por_cotizar']): ?>
                    <p class="aviso-cotizar">
                        Tienes piñatas personalizadas que se cotizarán antes de confirmar el pedido.
                    </p>
                <?php endif; ?>

                <a href="checkout.php" class="btn btn-primary btn-block">Continuar con el pedido</a>
                <a href="catalogo.php" class="btn btn-secondary btn-block">Seguir comprando</a>
            </aside>
        </div>
    <?php endif; ?>
</main>

<?php require __DIR__ . '/../../plantilla/footer.php'; ?>
</body>
</html>