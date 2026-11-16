<?php 
include __DIR__ . '/../../plantilla/header-admin.php'; 

// TODO: reemplazar por consulta SQL cuando exista BD.
$reportes = [
    ['img' => 'caballo.png',   'nombre' => 'Piñata Caballo',      'cliente' => 'Maria López',   'cantidad' => 1, 'precio' => 250],
    ['img' => 'palomitas.png', 'nombre' => 'Piñata Palomitas',    'cliente' => 'Maria López',   'cantidad' => 1, 'precio' => 350],
    ['img' => 'bob.png',       'nombre' => 'Piñata Bob Esponja',  'cliente' => 'Maria López',   'cantidad' => 1, 'precio' => 250],
    ['img' => 'numero1.png',   'nombre' => 'Piñata Número 1',     'cliente' => 'Jorge Dolores', 'cantidad' => 1, 'precio' => 350],
];
$totalVentas = array_sum(array_map(fn($r) => $r['precio'] * $r['cantidad'], $reportes));
?>

<div class="container my-5">

    <div class="text-center mb-5 p-3 rounded-3" style="background-color:#d8b06a;">
        <h2 class="m-0 fs-3 fw-bold">Reporte de Ventas</h2>
    </div>

    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <div class="card border-0 rounded-4 p-4" style="border: 3px solid #d8b06a !important;">
                <?php foreach ($reportes as $r): ?>
                    <div class="d-flex justify-content-between align-items-center border rounded-3 p-3 mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="<?= BASE_URL ?>/imagenes/<?= htmlspecialchars($r['img']) ?>"
                                 alt="<?= htmlspecialchars($r['nombre']) ?>"
                                 style="width:40px;height:40px;object-fit:contain;">
                            <span class="small">
                                <?= htmlspecialchars($r['nombre']) ?> / <?= htmlspecialchars($r['cliente']) ?>
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-4">
                            <input type="text" class="form-control form-control-sm text-center"
                                   value="<?= $r['cantidad'] ?>" readonly style="width:50px;">
                            <span class="fw-bold" style="min-width:50px;text-align:right;">
                                $<?= $r['precio'] ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 text-end">
            <span class="d-inline-block px-4 py-2 rounded-pill fw-bold fs-5" style="background-color:#d8b06a;">
                TOTAL DE VENTAS: $<?= $totalVentas ?>
            </span>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../../plantilla/footer.php'; ?>