<?php
include __DIR__ . '/../../plantilla/header-publico.php';
?>

<link rel="stylesheet" type="text/css" href="../../estilos/nosotros.css">

<!-- TÍTULO -->
<div class="container my-4">
    <h2 class="cardtitulo text-center mb-0">Sobre Nosotros</h2>
</div>

<div class="container mb-5">
    <!-- FILA SUPERIOR: imagen verde + texto + imagen libre -->
    <div class="row g-4 align-items-start mb-4">

        <!-- Columna izquierda: tarjeta verde + párrafo -->
        <div class="col-12 col-lg-6">
            <div class="verde rounded-4 mb-3">
                <img src="../../imagenes/zoot.png" alt="zootopia" class="img-fluid">
            </div>
            <p class="texto-justificado">
                En Piñatas-Mel, nos apasiona mantener viva la magia y la alegría de las
                celebraciones mexicanas. Lo que comenzó como un trabajo artesanal desde
                casa, hecho con dedicación y amor por el detalle, se ha transformado en
                un proyecto comprometido con llevar la mejor calidad a cada fiesta,
                posada y cumpleaños.
            </p>
        </div>

        <!-- Columna derecha: imagen libre -->
        <div class="col-12 col-lg-6">
            <div class="imagen-libre">
                <img src="../../imagenes/creep.png" alt="creeper" class="img-fluid rounded-3">
            </div>
        </div>
    </div>

    <!-- FILA DE TEXTOS -->
    <div class="row">
        <div class="col-12">
            <p class="texto-justificado">
                Creemos que cada piñata es única. Por eso, combinamos las técnicas
                tradicionales de la cartonería y el papel con diseños personalizados
                que se adaptan a lo que cada cliente imagina. Desde los clásicos de
                picos hasta personajes modernos y temáticas especiales, elaboramos
                cada pieza cuidando los materiales para que el momento de romperla
                sea inolvidable.
            </p>
            <p class="texto-justificado">
                Hoy en día, evolucionamos para estar más cerca de ti. A través de este
                espacio digital, buscamos hacer más fácil y rápida la consulta de
                nuestro catálogo y la creación de tus pedidos especiales, combinando
                el corazón de un taller artesanal con la tecnología de hoy.
            </p>
            <p class="text-center fw-semibold">
                ¡Gracias por dejarnos ser parte de tus mejores momentos y celebrar contigo!
            </p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../plantilla/footer.php'; ?>