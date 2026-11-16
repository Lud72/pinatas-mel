<?php
include __DIR__ . '/plantilla/header-publico.php';
?>

<link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/estilos/index.css">

<div class="contenedor1">
    <div class="inicio">
        <div class="fiesta">
            <img src="<?= BASE_URL ?>/imagenes/fiesta.png" alt="fiesta">
        </div>

        <section class="card">
            Encuentra la compañera perfecta para la fiesta de tus sueños
        </section>
    </div>
</div>

<section class="cardtitulo"> Los favoritos de la fiesta </section>

<div class="personaje">
    <div class="cardpersonaje">
        <img src="<?= BASE_URL ?>/imagenes/ceni.png" alt="cenicienta">
        <p>Princesas</p>
    </div>

    <div class="cardpersonaje">
        <img src="<?= BASE_URL ?>/imagenes/super.png" alt="superman">
        <p>Superheroes</p>
    </div>
    <div class="cardpersonaje">
        <img src="<?= BASE_URL ?>/imagenes/dragon.png" alt="dragon">
        <p>Dinosaurios</p>
    </div>
</div>

<hr>

<section class="cardtitulo2"> Inspiración piñatera </section>

<div class="tematica">
    <div class="cardtematica">
        <div class="verde">
            <img src="<?= BASE_URL ?>/imagenes/mami.png" alt="mamila">
        </div>
        <p>¡La dulzura más esperada! Dale el toque más tierno y divertido a la llegada de tu bebé con nuestra piñata en forma de mamila, perfecta para consentir a los futuros papás.</p>
    </div>

    <div class="cardtematica">
        <div class="verde">
            <img src="<?= BASE_URL ?>/imagenes/jiraf.png" alt="jirafa">
        </div>
        <p>¡Una aventura salvaje y llena de color! Transporta a tus invitados directo al safari con esta jirafa piñatera, ideal para rugir de emoción en su día especial.</p>
    </div>
    <div class="cardtematica">
        <div class="verde">
            <img src="<?= BASE_URL ?>/imagenes/carro.png" alt="carro">
        </div>
        <p>¡A toda velocidad hacia la diversión! Enciende los motores de la fiesta con una piñata sobre ruedas que dejará a todos listos para la línea de meta.</p>
    </div>
</div>

<?php include __DIR__ . '/plantilla/footer.php'; ?>