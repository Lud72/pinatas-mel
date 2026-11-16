<?php
include __DIR__ . '/../../plantilla/header-publico.php';
?>

<div class="container my-5" style="max-width: 1000px;">

    <!-- Título -->
    <div class="text-center my-4">
        <h2 class="fs-2 fw-bold">Sobre Nosotros</h2>
    </div>

    <!-- Fila superior: caja verde + texto / imagen libre -->
    <div class="row g-4 align-items-start mb-5">

        <div class="col-md-6">
            <div class="rounded-4 d-flex align-items-center justify-content-center p-3"
                 style="background-color:#95CBBD; height:250px;">
                <img src="<?= BASE_URL ?>/imagenes/zoot.png" alt="Zootopia"
                     class="img-fluid" style="max-height:100%; object-fit:contain;">
            </div>
            <p class="mt-4 text-justify small">
                En Piñatas-Mel, nos apasiona mantener viva la magia y la alegría de las celebraciones mexicanas.
                Lo que comenzó como un trabajo artesanal desde casa, hecho con dedicación y amor por el detalle,
                se ha transformado en un proyecto comprometido con llevar la mejor calidad a cada fiesta,
                posada y cumpleaños.
            </p>
        </div>

        <div class="col-md-6 d-flex align-items-center justify-content-center" style="min-height:450px;">
            <img src="<?= BASE_URL ?>/imagenes/creep.png" alt="Creeper"
                 class="img-fluid rounded-3" style="max-height:450px; object-fit:contain;">
        </div>
    </div>

    <!-- Textos -->
    <div class="row">
        <div class="col-12">
            <p class="text-justify">
                Creemos que cada piñata es única. Por eso, combinamos las técnicas tradicionales de la
                cartonería y el papel con diseños personalizados que se adaptan a lo que cada cliente imagina.
                Desde los clásicos de picos hasta personajes modernos y temáticas especiales, elaboramos cada
                pieza cuidando los materiales para que el momento de romperla sea inolvidable.
            </p>

            <p class="text-justify">
                Hoy en día, evolucionamos para estar más cerca de ti. A través de este espacio digital,
                buscamos hacer más fácil y rápida la consulta de nuestro catálogo y la creación de tus
                pedidos especiales, combinando el corazón de un taller artesanal con la tecnología de hoy.
            </p>

            <p class="text-center fw-semibold">
                ¡Gracias por dejarnos ser parte de tus mejores momentos y celebrar contigo!
            </p>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../../plantilla/footer.php'; ?>