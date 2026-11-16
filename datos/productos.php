<?php
/**
 * Fuente única de verdad para los productos.
 * TODO: reemplazar por consulta SQL cuando exista BD.
 *
 * Estructura:
 *   slug        → identificador en URL (?producto=slug)
 *   nombre      → nombre visible
 *   imagen      → archivo en /imagenes
 *   precio      → MXN
 *   categoria   → slug de categoría (debe coincidir con datos/categorias.php)
 *   capacidad, rendimiento, estructura, decoracion → textos del desglose
 */
return [
    'hamburguesa' => [
        'nombre'      => 'Piñata de Hamburguesa',
        'imagen'      => 'hamburguesa.png',
        'precio'      => 500,
        'categoria'   => 'comida',
        'capacidad'   => 'Capacidad estimada: 3 a 5 kg de dulces surtidos (o juguetes pequeños y confeti).',
        'rendimiento' => '5 a 25 niños',
        'estructura'  => 'Estructura: Cartón de alta resistencia y papel periódico/cartonería tradicional.',
        'decoracion'  => 'Decoración: Papel china y crepé picado/plisado en capas de colores vivos con detalles en foamy/goma EVA.',
    ],
    'fresa' => [
        'nombre'      => 'Piñata de Fresa',
        'imagen'      => 'fresa.png',
        'precio'      => 450,
        'categoria'   => 'comida',
        'capacidad'   => 'Capacidad estimada: 2 a 4 kg de dulces surtidos.',
        'rendimiento' => '5 a 20 niños',
        'estructura'  => 'Estructura: Cartón y papel periódico.',
        'decoracion'  => 'Decoración: Papel china rojo brillante con detalles verdes para las hojas.',
    ],
    'buble-tea' => [
        'nombre'      => 'Piñata de Buble Tea',
        'imagen'      => 'buble-tea.png',
        'precio'      => 480,
        'categoria'   => 'comida',
        'capacidad'   => 'Capacidad estimada: 3 kg de dulces.',
        'rendimiento' => '5 a 20 niños',
        'estructura'  => 'Estructura: Cartón y papel periódico.',
        'decoracion'  => 'Decoración: Representa el famoso vaso transparente con detalles en foamy.',
    ],
    'palomitas' => [
        'nombre'      => 'Piñata de Palomitas',
        'imagen'      => 'palomitas.png',
        'precio'      => 400,
        'categoria'   => 'comida',
        'capacidad'   => 'Capacidad estimada: 2 a 4 kg de dulces.',
        'rendimiento' => '5 a 15 niños',
        'estructura'  => 'Estructura: Cartón y papel periódico.',
        'decoracion'  => 'Decoración: Papel china en tonos rosa, blanco y amarillo.',
    ],
    'pozole' => [
        'nombre'      => 'Piñata de Pozole',
        'imagen'      => 'pozole.png',
        'precio'      => 520,
        'categoria'   => 'comida',
        'capacidad'   => 'Capacidad estimada: 3 a 5 kg de dulces.',
        'rendimiento' => '5 a 25 niños',
        'estructura'  => 'Estructura: Cartón y papel periódico.',
        'decoracion'  => 'Decoración: Detalles de lechuga, rábanos y maíz en papel china.',
    ],
    'taco' => [
        'nombre'      => 'Piñata de Taco',
        'imagen'      => 'taco.png',
        'precio'      => 450,
        'categoria'   => 'comida',
        'capacidad'   => 'Capacidad estimada: 2 a 4 kg de dulces.',
        'rendimiento' => '5 a 20 niños',
        'estructura'  => 'Estructura: Cartón y papel periódico.',
        'decoracion'  => 'Decoración: Papel china simulando tortilla, carne y verduras.',
    ],
];