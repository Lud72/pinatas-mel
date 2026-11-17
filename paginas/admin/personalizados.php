<?php 
include __DIR__ . '/../../plantilla/header-admin.php'; 

require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../../modelos/personalizados.php';

$mensaje = '';
$error   = '';

// Cambiar estado
if (($_POST['accion'] ?? '') === 'cambiar_estado' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int)($_POST['id'] ?? 0);
    $estado = $_POST['estado'] ?? '';
    try {
        cambiarEstadoPersonalizado($pdo, $id, $estado);
        $mensaje = 'Estado actualizado.';
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

$filtro  = $_GET['filtro'] ?? 'pendiente';
$items   = ($filtro === 'todos') ? obtenerPedidosPersonalizados($pdo) : obtenerPedidosPersonalizados($pdo, $filtro);
?>

<div class="container my-5">

    <div class="p-3 mb-4 text-center rounded" style="background-color: #EBB648;">
        <h2 class="fw-bold text-dark m-0">Pedidos Personalizados</h2>
    </div>

    <?php if ($mensaje): ?>
        <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Filtros -->
    <div class="d-flex gap-2 mb-4 flex-wrap">
        <?php foreach (['pendiente','en_proceso','terminado','cancelado','todos'] as $f): ?>
            <a href="personalizados.php?filtro=<?= $f ?>"
               class="btn btn-sm rounded-pill <?= $filtro === $f ? 'btn-primario' : 'btn-outline-secondary' ?>">
                <?= ucfirst(str_replace('_', ' ', $f)) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="p-4 rounded-4" style="border: 3px solid #EBB648;">
        <?php if (empty($items)): ?>
            <div class="text-center py-4 text-muted fw-semibold">Sin solicitudes en este estado.</div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($items as $item): ?>
                    <div class="col-md-6">
                        <div class="card border-0 rounded-4 p-3 h-100" style="border: 2px solid #A3D8C5 !important;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="fw-bold mb-1"><?= htmlspecialchars($item['cliente_nombre']) ?></h6>
                                    <small class="text-muted"><?= htmlspecialchars($item['cliente_telefono']) ?></small>
                                </div>
                                <span class="badge bg-secondary">
                                    <?= date('d/m/Y', strtotime($item['creado_en'])) ?>
                                </span>
                            </div>

                            <hr class="my-2">

                            <p class="mb-1 small"><strong>Personaje:</strong> <?= htmlspecialchars($item['personaje']) ?></p>
                            <?php if ($item['texto_personalizado']): ?>
                                <p class="mb-1 small"><strong>Texto:</strong> <?= htmlspecialchars($item['texto_personalizado']) ?></p>
                            <?php endif; ?>
                            <?php if ($item['tamano_cm']): ?>
                                <p class="mb-1 small"><strong>Tamaño:</strong> <?= (int)$item['tamano_cm'] ?> cm</p>
                            <?php endif; ?>
                            <?php if ($item['tipo_apertura']): ?>
                                <p class="mb-1 small"><strong>Apertura:</strong> <?= htmlspecialchars($item['tipo_apertura']) ?></p>
                            <?php endif; ?>

                            <?php if ($item['imagen_referencia']): ?>
                                <div class="mt-2 mb-3">
                                    <a href="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($item['imagen_referencia']) ?>" target="_blank">
                                        <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($item['imagen_referencia']) ?>"
                                             alt="Referencia" class="img-fluid rounded" style="max-height:150px;">
                                    </a>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="personalizados.php?filtro=<?= urlencode($filtro) ?>" class="mt-auto">
                                <input type="hidden" name="accion" value="cambiar_estado">
                                <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
                                <select name="estado" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <?php foreach (['pendiente','en_proceso','terminado','cancelado'] as $e): ?>
                                        <option value="<?= $e ?>" <?= $item['estado'] === $e ? 'selected' : '' ?>>
                                            <?= ucfirst(str_replace('_', ' ', $e)) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php include __DIR__ . '/../../plantilla/footer.php'; ?>