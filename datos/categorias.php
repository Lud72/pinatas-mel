<?php
/**
 * Fuente única de verdad para las categorías.
 * TODO: reemplazar por consulta SQL cuando exista BD.
 *
 * Estructura:
 *   slug        → identificador en URL (?tipo=slug)
 *   titulo      → nombre visible
 *   icono       → imagen para tarjetas (catálogo público y admin)
 *   banner      → imagen de cabecera en la página de categoría
 *   productos   → conteo (solo admin; se puede calcular con el array de productos)
 */
return [
    'estrellas' => [
        'id'       => 1,
        'titulo'   => 'Tradicionales de Estrellas',
        'icono'    => 'estrella.png',
        'banner'   => 'franja.png',
        'productos' => 0,
    ],
    'personajes' => [
        'id'       => 2,
        'titulo'   => 'Personajes y Cultura Pop',
        'icono'    => 'bob.png',
        'banner'   => 'franja.png',
        'productos' => 0,
    ],
    'numeros' => [
        'id'       => 3,
        'titulo'   => 'Números y Letras',
        'icono'    => 'numero1.png',
        'banner'   => 'franja.png',
        'productos' => 0,
    ],
    'animales' => [
        'id'       => 4,
        'titulo'   => 'Animales',
        'icono'    => 'caballo.png',
        'banner'   => 'franja.png',
        'productos' => 0,
    ],
    'geometricas' => [
        'id'       => 5,
        'titulo'   => 'Formas Geométricas',
        'icono'    => 'espejo.png',
        'banner'   => 'franja.png',
        'productos' => 0,
    ],
    'comida' => [
        'id'       => 6,
        'titulo'   => 'Objetos, Comida',
        'icono'    => 'hamburguesa.png',
        'banner'   => 'franja.png',
        'productos' => 6,
    ],
    'jalon' => [
        'id'       => 7,
        'titulo'   => 'Piñatas de "Jalón"',
        'icono'    => 'stitch.png',
        'banner'   => 'franja.png',
        'productos' => 0,
    ],
    'humoristicas' => [
        'id'       => 8,
        'titulo'   => 'Humorísticas',
        'icono'    => 'perro.png',
        'banner'   => 'franja.png',
        'productos' => 0,
    ],
];