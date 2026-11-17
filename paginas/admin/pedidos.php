<?php 
include __DIR__ . '/../../plantilla/header-admin.php'; 

require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../../modelos/pedidos.php';

$mensaje = '';
$error   = '';

// Cambiar estado
if (($_POST['accion'] ?? '') === 'cambiar_estado' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int)($_POST['id'] ?? 0);
    $estado = $_POST['estado'] ?? '';
    try {
        cambiarEstadoPedido($pdo, $id, $estado);
        $mensaje = 'Estado actualizado.';
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

$filtro  = $_GET['filtro'] ?? 'pendiente';
$pedidos = ($filtro === 'todos') ? obtenerPedidos($pdo) : obtenerPedidos($pdo, $filtro);
?>

<div class="container my-5">

    <div class="p-3 mb-4 text-center rounded" style="background-color: #EBB648;">
        <h2 class="fw-bold text-dark m-0">Pedidos</h2>
    </div>

    <?php if ($mensaje): ?>
        <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Filtros -->
    <div class="d-flex gap-2 mb-4 flex-wrap">
        <?php foreach (['pendiente','pagado','enviado','entregado','cancelado','todos'] as $f): ?>
            <a href="pedidos.php?filtro=<?= $f ?>"
               class="btn btn-sm rounded-pill <?= $filtro === $f ? 'btn-primario' : 'btn-outline-secondary' ?>">
                <?= ucfirst($f) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="p-4 rounded-4" style="border: 3px solid #EBB648;">
        <?php if (empty($pedidos)): ?>
            <div class="text-center py-4 text-muted fw-semibold">Sin pedidos en este estado.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Cliente</th>
                            <th>Teléfono</th>
                            <th>Total</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $p): ?>
                            <tr>
                                <td><code><?= htmlspecialchars($p['folio']) ?></code></td>
                                <td><?= htmlspecialchars($p['cliente_nombre']) ?></td>
                                <td><?= htmlspecialchars($p['cliente_telefono']) ?></td>
                                <td class="fw-bold">$<?= number_format((float)$p['total'], 2) ?></td>
                                <td class="small"><?= date('d/m/Y H:i', strtotime($p['creado_en'])) ?></td>
                                <td>
                                    <form method="POST" action="pedidos.php?filtro=<?= urlencode($filtro) ?>" class="d-flex gap-1 m-0">
                                        <input type="hidden" name="accion" value="cambiar_estado">
                                        <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                                        <select name="estado" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <?php foreach (['pendiente','pagado','enviado','entregado','cancelado'] as $e): ?>
                                                <option value="<?= $e ?>" <?= $p['estado'] === $e ? 'selected' : '' ?>>
                                                    <?= ucfirst($e) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php include __DIR__ . '/../../plantilla/footer.php'; ?>