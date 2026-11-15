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

    
    <?php if (isset($pedido_seleccionado)) { ?>
        <div class="p-4 mb-4 rounded-4" style="background-color: #E6F2F7;">
            <h4 class="fw-bold text-secondary mb-4"><?php echo $pedido_seleccionado['nombre_producto']; ?></h4>
            
            <div class="row align-items-center">
                <div class="col-md-4 text-center mb-3 mb-md-0">
                    <img src="<?php echo $pedido_seleccionado['imagen']; ?>" class="img-fluid rounded" style="max-height: 220px;">
                </div>

                <div class="col-md-8">
                    <p class="mb-1"><strong>Nombre:</strong> <?php echo $pedido_seleccionado['cliente']; ?></p>
                    <p class="mb-1"><strong>Teléfono:</strong> <?php echo $pedido_seleccionado['telefono']; ?></p>
                    <p class="mb-1"><strong>Personaje:</strong> <?php echo $pedido_seleccionado['personaje']; ?></p>
                    <p class="mb-1"><strong>Nombre Personalizado:</strong> <?php echo $pedido_seleccionado['nombre_personalizado']; ?></p>
                    <p class="mb-1"><strong>Tamaño(cm):</strong> <?php echo $pedido_seleccionado['tamano']; ?></p>
                    <p class="mb-1"><strong>Tipo de apertura:</strong> <?php echo $pedido_seleccionado['tipo_apertura']; ?></p>
                </div>
            </div>

            
            <div class="d-flex justify-content-end align-items-center gap-2 mt-4">
                <button class="btn text-white rounded-pill px-4" style="background-color: #8BD2C2;">Realizada</button>
                <button class="btn btn-secondary rounded-circle">↺</button>
            </div>
        </div>
    <?php } ?>

</div>

<?php include __DIR__ . '/../../plantilla/footer.php'; ?>