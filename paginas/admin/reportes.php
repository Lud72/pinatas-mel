<?php 
include __DIR__ . '/../../plantilla/header-admin.php'; 
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/estilos/reporteestilo.css">

<div class="container my-5">
  
    <div class="banner-reporte text-center mb-5">
        <h2>Reporte de Ventas</h2>
    </div>

    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <div class="caja-lista">
               
                <div class="item-producto">
                    <div class="info-producto">
                        <img src="<?= BASE_URL ?>/imagenes/caballo.png" alt="Caballo" class="img-mini">
                        <span class="nombre-producto">Piñata Caballo/Maria López</span>
                    </div>
                    <div class="detalles-producto">
                        <input type="text" class="input-cantidad" value="1" readonly>
                        <span class="precio">$250</span>
                    </div>
                </div>

                <div class="item-producto">
                    <div class="info-producto">
                        <img src="<?= BASE_URL ?>/imagenes/palomitas.png" alt="Palomitas" class="img-mini">
                        <span class="nombre-producto">Piñata Palomitas/Maria López</span>
                    </div>
                    <div class="detalles-producto">
                        <input type="text" class="input-cantidad" value="1" readonly>
                        <span class="precio">$350</span>
                    </div>
                </div>

                <div class="item-producto">
                    <div class="info-producto">
                        <img src="<?= BASE_URL ?>/imagenes/bob.png" alt="Bob" class="img-mini">
                        <span class="nombre-producto">Piñata Bob Esponja/Maria López</span>
                    </div>
                    <div class="detalles-producto">
                        <input type="text" class="input-cantidad" value="1" readonly>
                        <span class="precio">$250</span>
                    </div>
                </div>

                <div class="item-producto">
                    <div class="info-producto">
                        <img src="<?= BASE_URL ?>/imagenes/numero1.png" alt="Número 1" class="img-mini">
                        <span class="nombre-producto">Piñata Número 1/Jorge Dolores</span>
                    </div>
                    <div class="detalles-producto">
                        <input type="text" class="input-cantidad" value="1" readonly>
                        <span class="precio">$350</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 text-end">
            <div class="etiqueta-total">
                TOTAL DE VENTAS: <span>$1500</span>
            </div>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../../plantilla/footer.php'; ?>