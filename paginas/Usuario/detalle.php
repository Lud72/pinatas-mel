<?php
$productos = [
    'hamburguesa' => [
        'nombre'      => 'Piñata de Hamburguesa',
        'imagen'      => 'hamburguesa.png',
        'precio'      => 500,
        'capacidad'   => 'Capacidad estimada: 3 a 5 kg de dulces surtidos (o juguetes pequeños y confeti).',
        'rendimiento' => '5 a 25 niños',
        'estructura'  => 'Estructura: Cartón de alta resistencia y papel periódico/cartonería tradicional.',
        'decoracion'  => 'Decoración: Papel china y crepé picado/plisado en capas de colores vivos (pan, carne, queso, lechuga y jitomate) con detalles en foamy/goma EVA (como las semillas de ajonjolí en la tapa del pan).'
    ],
    'fresa' => [
        'nombre'      => 'Piñata de Fresa',
        'imagen'      => 'fresa.png',
        'precio'      => 450,
        'capacidad'   => 'Capacidad estimada: 2 a 4 kg de dulces surtidos.',
        'rendimiento' => '5 a 20 niños',
        'estructura'  => 'Estructura: Cartón y papel periódico.',
        'decoracion'  => 'Decoración: Papel china rojo brillante con detalles verdes para las hojas.'
    ],
    'buble-tea' => [
        'nombre'      => 'Piñata de Buble Tea',
        'imagen'      => 'buble-tea.png',
        'precio'      => 480,
        'capacidad'   => 'Capacidad estimada: 3 kg de dulces.',
        'rendimiento' => '5 a 20 niños',
        'estructura'  => 'Estructura: Cartón y papel periódico.',
        'decoracion'  => 'Decoración: Representa el famoso vaso transparente con detalles en foamy.'
    ],
    'palomitas' => [
        'nombre'      => 'Piñata de Palomitas',
        'imagen'      => 'palomitas.png',
        'precio'      => 400,
        'capacidad'   => 'Capacidad estimada: 2 a 4 kg de dulces.',
        'rendimiento' => '5 a 15 niños',
        'estructura'  => 'Estructura: Cartón y papel periódico.',
        'decoracion'  => 'Decoración: Papel china en tonos rosa, blanco y amarillo.'
    ],
    'pozole' => [
        'nombre'      => 'Piñata de Pozole',
        'imagen'      => 'pozole.png',
        'precio'      => 520,
        'capacidad'   => 'Capacidad estimada: 3 a 5 kg de dulces.',
        'rendimiento' => '5 a 25 niños',
        'estructura'  => 'Estructura: Cartón y papel periódico.',
        'decoracion'  => 'Decoración: Detalles de lechuga, rábanos y maíz en papel china.'
    ],
    'taco' => [
        'nombre'      => 'Piñata de Taco',
        'imagen'      => 'taco.png',
        'precio'      => 450,
        'capacidad'   => 'Capacidad estimada: 2 a 4 kg de dulces.',
        'rendimiento' => '5 a 20 niños',
        'estructura'  => 'Estructura: Cartón y papel periódico.',
        'decoracion'  => 'Decoración: Papel china simulando tortilla, carne y verduras.'
    ],
];

$slug = $_GET['producto'] ?? '';

if (!isset($productos[$slug])) {
    header('Location: catalogo.php');
    exit;
}

$producto = $productos[$slug];
?>

<?php include __DIR__ . '/../../plantilla/header-publico.php'; ?>

<div class="container my-5">
    <div class="card card-producto p-4">
        <div class="row">
            <div class="col-md-5 text-center">
                <img src="/pinatas-mel/imagenes/<?= htmlspecialchars($producto['imagen']) ?>" 
                     alt="<?= htmlspecialchars($producto['nombre']) ?>" 
                     class="img-fluid" style="max-height: 350px;">
            </div>
            <div class="col-md-7">
                <h2 class="mb-4"><?= htmlspecialchars($producto['nombre']) ?></h2>
                <p><strong>Precio:</strong> $<?= htmlspecialchars($producto['precio']) ?> MXN</p>
                <p><strong>Capacidad de dulces y sorpresa:</strong><br><?= htmlspecialchars($producto['capacidad']) ?></p>
                <p><strong>Rendimiento:</strong> <?= htmlspecialchars($producto['rendimiento']) ?></p>
                <p><strong>Materiales de fabricación:</strong><br><?= htmlspecialchars($producto['estructura']) ?></p>
                <p><strong>Decoración:</strong><br><?= htmlspecialchars($producto['decoracion']) ?></p>
                <button class="btn btn-primario rounded-pill px-4 py-2 mt-3">Añadir al carrito</button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../plantilla/footer.php'; ?>