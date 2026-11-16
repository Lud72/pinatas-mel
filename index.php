<?php
include __DIR__ . '/plantilla/header-publico.php';
?>

<div class="container my-4">

    <!-- HERO -->
    <div class="row g-3 mb-5" style="min-height: 500px;">
        <div class="col-lg-8 d-flex">
            <img src="<?= BASE_URL ?>/imagenes/fiesta.png" alt="Fiesta"
                 class="img-fluid rounded-4 w-100" style="object-fit:cover;">
        </div>
        <div class="col-lg-4 d-flex">
            <div class="rounded-4 p-4 d-flex align-items-center justify-content-center text-center w-100"
                 style="background-color:#95CBBD; font-size:1.6rem;">
                Encuentra la compañera perfecta para la fiesta de tus sueños
            </div>
        </div>
    </div>

    <!-- FAVORITOS -->
    <div class="text-center mb-5 p-3 rounded-3" style="background-color:#EDB74B;">
        <h2 class="fs-3 fw-bold m-0">Los favoritos de la fiesta</h2>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4 mb-4 text-center">
        <div class="col">
            <img src="<?= BASE_URL ?>/imagenes/ceni.png" alt="Cenicienta"
                 class="img-fluid mb-2" style="max-height:200px; object-fit:contain;">
            <p class="fw-semibold mb-0">Princesas</p>
        </div>
        <div class="col">
            <img src="<?= BASE_URL ?>/imagenes/super.png" alt="Superman"
                 class="img-fluid mb-2" style="max-height:200px; object-fit:contain;">
            <p class="fw-semibold mb-0">Superheroes</p>
        </div>
        <div class="col">
            <img src="<?= BASE_URL ?>/imagenes/dragon.png" alt="Dragón"
                 class="img-fluid mb-2" style="max-height:200px; object-fit:contain;">
            <p class="fw-semibold mb-0">Dinosaurios</p>
        </div>
    </div>

    <hr class="my-5" style="border:0;height:10px;background-color:#12b488;opacity:1;width:80%;margin-left:auto;margin-right:auto;">

    <!-- INSPIRACIÓN -->
    <div class="text-center mb-5">
        <h2 class="fs-3 fw-bold">Inspiración piñatera</h2>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4 text-center">

        <div class="col">
            <div class="rounded-4 d-flex align-items-center justify-content-center p-3 mb-3"
                 style="background-color:#95CBBD; min-height:300px;">
                <img src="<?= BASE_URL ?>/imagenes/mami.png" alt="Mamila"
                     class="img-fluid" style="max-height:280px; object-fit:contain;">
            </div>
            <p class="small">
                ¡La dulzura más esperada! Dale el toque más tierno y divertido a la llegada de tu bebé
                con nuestra piñata en forma de mamila, perfecta para consentir a los futuros papás.
            </p>
        </div>

        <div class="col">
            <div class="rounded-4 d-flex align-items-center justify-content-center p-3 mb-3"
                 style="background-color:#95CBBD; min-height:300px;">
                <img src="<?= BASE_URL ?>/imagenes/jiraf.png" alt="Jirafa"
                     class="img-fluid" style="max-height:280px; object-fit:contain;">
            </div>
            <p class="small">
                ¡Una aventura salvaje y llena de color! Transporta a tus invitados directo al safari
                con esta jirafa piñatera, ideal para rugir de emoción en su día especial.
            </p>
        </div>

        <div class="col">
            <div class="rounded-4 d-flex align-items-center justify-content-center p-3 mb-3"
                 style="background-color:#95CBBD; min-height:300px;">
                <img src="<?= BASE_URL ?>/imagenes/carro.png" alt="Carro"
                     class="img-fluid" style="max-height:280px; object-fit:contain;">
            </div>
            <p class="small">
                ¡A toda velocidad hacia la diversión! Enciende los motores de la fiesta con una piñata
                sobre ruedas que dejará a todos listos para la línea de meta.
            </p>
        </div>

    </div>

</div>

<?php include __DIR__ . '/plantilla/footer.php'; ?>