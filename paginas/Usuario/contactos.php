<?php 
include __DIR__ . '/../../plantilla/header-publico.php'; 
?>

<div class="container my-5">

    <!-- Fila superior: imágenes + cajas de info -->
    <div class="row text-center mb-5 g-4">

        <!-- Stitch -->
        <div class="col-md-6 d-flex align-items-center justify-content-center">
            <img src="<?= BASE_URL ?>/imagenes/stich.png" alt="Stitch" class="img-fluid" style="max-height:200px;">
        </div>

        <!-- Ubicación -->
        <div class="col-md-6 d-flex align-items-center justify-content-center">
            <div class="card border-0 rounded-4 p-4 w-100" style="border: 2px solid #8fb8a8 !important; max-width:280px; min-height:250px;">
                <img src="<?= BASE_URL ?>/imagenes/ubicacion.png" alt="Ubicación"
                     class="mx-auto mb-3" style="width:70px;height:auto;">
                <p class="mb-0 small">
                    Av. San Jonathan<br>
                    Fraccionamiento H2O, Col. Morelos<br>
                    C.P. 50120<br>
                    Toluca, Estado de México.
                </p>
            </div>
        </div>

        <!-- Teléfono -->
        <div class="col-md-6 d-flex align-items-center justify-content-center">
            <div class="card border-0 rounded-4 p-4 w-100" style="border: 2px solid #8fb8a8 !important; max-width:280px; min-height:250px;">
                <img src="<?= BASE_URL ?>/imagenes/whatsapp.png" alt="Teléfono"
                     class="mx-auto mb-3" style="width:70px;height:auto;">
                <p class="mb-0 small">
                    Número de teléfono<br>
                    <strong>729 120 6121</strong>
                </p>
            </div>
        </div>

        <!-- Alicia -->
        <div class="col-md-6 d-flex align-items-center justify-content-center">
            <img src="<?= BASE_URL ?>/imagenes/alicia.png" alt="Alicia" class="img-fluid" style="max-height:200px;">
        </div>
    </div>

    <!-- Horario -->
    <div class="text-center mb-5 p-3 rounded-3" style="background-color:#d8b06a;">
        <h4 class="m-0 fw-bold">Horario de atención</h4>
        <p class="m-0">Lunes a Sábado de 9:00 a.m. a 6:00 p.m.</p>
    </div>

    <!-- Formulario -->
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 rounded-4 p-4" style="border: 3px solid #8fb8a8 !important;">
                <h3 class="text-center mb-4 fs-4 fw-bold">Contáctanos</h3>

                <form>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nombre</label>
                        <input type="text" class="form-control" placeholder="Ingresa tu Nombre">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Correo</label>
                        <input type="email" class="form-control" placeholder="Ingresa una dirección de correo">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Mensaje</label>
                        <textarea class="form-control" rows="4" placeholder="Mensaje"></textarea>
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-primario rounded-pill px-4">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../../plantilla/footer.php'; ?>