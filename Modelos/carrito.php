<?php
/**
 * Helpers para manejar el carrito en $_SESSION.
 * El carrito NO persiste en BD hasta que se paga.
 *
 * Estructura de $_SESSION['carrito']:
 *   [
 *     'hamburguesa' => 2,   // slug => cantidad
 *     'fresa'       => 1,
 *   ]
 */

/**
 * Inicia el carrito si no existe.
 */
function carritoIniciar(): void
{
    if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }
}

/**
 * Agrega una unidad de un producto (o incrementa cantidad).
 */
function carritoAgregar(string $slug, int $cantidad = 1): void
{
    carritoIniciar();
    if (!isset($_SESSION['carrito'][$slug])) {
        $_SESSION['carrito'][$slug] = 0;
    }
    $_SESSION['carrito'][$slug] += $cantidad;
    if ($_SESSION['carrito'][$slug] < 1) {
        unset($_SESSION['carrito'][$slug]);
    }
}

/**
 * Elimina un producto del carrito.
 */
function carritoEliminar(string $slug): void
{
    carritoIniciar();
    unset($_SESSION['carrito'][$slug]);
}

/**
 * Vacía el carrito.
 */
function carritoVaciar(): void
{
    $_SESSION['carrito'] = [];
}

/**
 * Devuelve las cantidades (slug => cantidad).
 */
function carritoObtener(): array
{
    carritoIniciar();
    return $_SESSION['carrito'];
}

/**
 * Devuelve el carrito "resuelto": combina la sesión con datos de la BD.
 * Resultado: [
 *   ['slug','nombre','imagen','precio','cantidad','subtotal'], ...
 * ]
 */
function carritoDetallado(PDO $pdo): array
{
    $carrito = carritoObtener();
    if (empty($carrito)) {
        return [];
    }

    // Cargar productos de la BD por slugs
    $slugs = array_keys($carrito);
    $placeholders = implode(',', array_fill(0, count($slugs), '?'));

    $stmt = $pdo->prepare("
        SELECT id, slug, nombre, imagen, precio
        FROM productos
        WHERE slug IN ($placeholders) AND activo = 1
    ");
    $stmt->execute($slugs);
    $productos = $stmt->fetchAll();

    $resultado = [];
    foreach ($productos as $p) {
        $cantidad = (int)$carrito[$p['slug']];
        $precio   = (float)$p['precio'];
        $resultado[] = [
            'id'        => (int)$p['id'],
            'slug'      => $p['slug'],
            'nombre'    => $p['nombre'],
            'imagen'    => $p['imagen'],
            'precio'    => $precio,
            'cantidad'  => $cantidad,
            'subtotal'  => $precio * $cantidad,
        ];
    }
    return $resultado;
}

/**
 * Total del carrito.
 */
function carritoTotal(PDO $pdo): float
{
    $total = 0.0;
    foreach (carritoDetallado($pdo) as $item) {
        $total += $item['subtotal'];
    }
    return $total;
}