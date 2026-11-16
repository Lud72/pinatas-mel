<?php 
include __DIR__ . '/../../plantilla/header-admin.php'; 

$pedidos = array(); 
?>

<div class="container my-5">

    <div class="p-3 mb-4 text-center rounded" style="background-color: #EBB648;">
        <h2 class="fw-bold text-dark m-0">Pedidos Pendientes</h2>
    </div>

    <div class="p-4 mb-4 rounded-4" style="border: 3px solid #EBB648;">
        <div class="d-flex flex-column gap-3">
            <?php if (empty($pedidos)) { ?>
                <div class="text-center py-4 text-muted fw-semibold">
                    Sin pedidos pendientes por ahora.
                </div>
            <?php } else { ?>
                <?php foreach ($pedidos as $pedido) { ?>
                    <div class="d-flex align-items-center justify-content-between p-3 bg-white rounded-pill border border-2" style="border-color: #8BD2C2 !important;">
                        <div class="d-flex align-items-center gap-3">
                            <img src="<?php echo $pedido['imagen']; ?>" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            <span class="fw-semibold text-dark"><?php echo $pedido['nombre_producto']; ?> / <?php echo $pedido['cliente']; ?></span>
                        </div>
                        <span class="border border-secondary px-3 py-1 rounded text-muted fw-bold"><?php echo $pedido['cantidad']; ?></span>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../../plantilla/footer.php'; ?>