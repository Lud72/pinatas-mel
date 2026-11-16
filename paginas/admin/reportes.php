<?php 
include __DIR__ . '/../../plantilla/header-admin.php'; 

require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../../modelos/reportes.php';

// Sin filtro de fechas por ahora (Fase 5.7 puede agregar date picker)
$ventas = obtenerReporteVentas($pdo);
$total  = totalVentas($pdo);
?>

<div class="container my-5">

    <div class="text-center mb-5 p-3 rounded-3" style="background-color:#d8b06a;">
        <h2 class="m-0 fs-3 fw-bold">Reporte de Ventas</h2>
    </div>

    <div class="row justify-content-center mb-4">
        <div class="col-md-10">
            <div class="card border-0 rounded-4 p-4" style="border: 3px solid #d8b06a !important;">
                <?php if (empty($ventas)): ?>
                    <div class="text-center py-4 text-muted fw-semibold">
                        Sin ventas registradas por ahora.
                    </div>
                <?php else: ?>
                    <?php foreach ($ventas as $v): ?>
                        <div class="d-flex justify-content-between align-items-center border rounded-3 p-3 mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="<?= BASE_URL ?>/imagenes/<?= htmlspecialchars($v['producto_imagen']) ?>"
                                     alt="<?= htmlspecialchars($v['producto_nombre']) ?>"
                                     style="width:40px;height:40px;object-fit:contain;">
                                <div>
                                    <div class="small fw-semibold">
                                        <?= htmlspecialchars($v['producto_nombre']) ?>
                                    </div>
                                    <small class="text-muted">
                                        <?= htmlspecialchars($v['cliente_nombre']) ?>
                                        · <?= htmlspecialchars($v['folio']) ?>
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-4">
                                <input type="text" class="form-control form-control-sm text-center"
                                       value="<?= (int)$v['cantidad'] ?>" readonly style="width:50px;">
                                <span class="fw-bold" style="min-width:70px;text-align:right;">
                                    $<?= number_format((float)$v['subtotal'], 2) ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10 text-end">
            <span class="d-inline-block px-4 py-2 rounded-pill fw-bold fs-5" style="background-color:#d8b06a;">
                TOTAL DE VENTAS: $<?= number_format($total, 2) ?>
            </span>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../../plantilla/footer.php'; ?>