<?php
// /pinatas-mel/funciones/carrito.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../conexion.php';

/**
 * Devuelve (o crea) el carrito activo de la sesión actual.
 */
function obtenerCarrito(PDO $pdo): int
{
    if (!isset($_SESSION['carrito_id'])) {
        $sid = session_id();
        // Buscar existente
        $stmt = $pdo->prepare("SELECT id FROM carritos WHERE session_id = :sid");
        $stmt->execute([':sid' => $sid]);
        $id = $stmt->fetchColumn();
        if (!$id) {
            $pdo->prepare("INSERT INTO carritos (session_id) VALUES (:sid)")
                ->execute([':sid' => $sid]);
            $id = (int)$pdo->lastInsertId();
        }
        $_SESSION['carrito_id'] = (int)$id;
    }
    return (int)$_SESSION['carrito_id'];
}

/**
 * Añade un producto normal al carrito. Si ya existe, suma cantidad.
 */
function agregarProductoAlCarrito(PDO $pdo, int $productoId, int $cantidad = 1): void
{
    $carritoId = obtenerCarrito($pdo);

    $stmt = $pdo->prepare("SELECT id, cantidad FROM carrito_items
                           WHERE carrito_id = :cid AND producto_id = :pid LIMIT 1");
    $stmt->execute([':cid' => $carritoId, ':pid' => $productoId]);
    $item = $stmt->fetch();

    if ($item) {
        $pdo->prepare("UPDATE carrito_items SET cantidad = cantidad + :c WHERE id = :id")
            ->execute([':c' => $cantidad, ':id' => $item['id']]);
    } else {
        $prod = $pdo->prepare("SELECT nombre, precio FROM productos WHERE id = :id");
        $prod->execute([':id' => $productoId]);
        $p = $prod->fetch();
        if (!$p) return;

        $pdo->prepare("INSERT INTO carrito_items
                       (carrito_id, producto_id, nombre, cantidad, precio_unitario)
                       VALUES (:cid, :pid, :nom, :cant, :precio)")
            ->execute([
                ':cid'    => $carritoId,
                ':pid'    => $productoId,
                ':nom'    => $p['nombre'],
                ':cant'   => $cantidad,
                ':precio' => $p['precio'],
            ]);
    }
}

/**
 * Añade un pedido personalizado al carrito (precio 0 hasta cotizar).
 */
function agregarPersonalizadoAlCarrito(PDO $pdo, int $pedidoId, string $nombre, int $cantidad = 1): void
{
    $carritoId = obtenerCarrito($pdo);

    $pdo->prepare("INSERT INTO carrito_items
                   (carrito_id, pedido_personalizado_id, nombre, cantidad, precio_unitario)
                   VALUES (:cid, :pid, :nom, :cant, 0)")
        ->execute([
            ':cid'  => $carritoId,
            ':pid'  => $pedidoId,
            ':nom'  => $nombre,
            ':cant' => $cantidad,
        ]);
}

/**
 * Devuelve los ítems del carrito con sus datos completos.
 */
function listarCarrito(PDO $pdo): array
{
    $carritoId = obtenerCarrito($pdo);
    $stmt = $pdo->prepare(
        "SELECT ci.*, p.imagen AS producto_imagen,
                pp.referencia_imagen, pp.tamano_cm, pp.tipo_apertura,
                pp.precio_cotizado, pp.estado AS pp_estado
           FROM carrito_items ci
      LEFT JOIN productos p  ON p.id  = ci.producto_id
      LEFT JOIN pedidos_personalizados pp ON pp.id = ci.pedido_personalizado_id
          WHERE ci.carrito_id = :cid
       ORDER BY ci.creado_en ASC"
    );
    $stmt->execute([':cid' => $carritoId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Actualiza cantidad de un ítem. Si cantidad <= 0, lo elimina.
 */
function actualizarCantidad(PDO $pdo, int $itemId, int $cantidad): void
{
    $carritoId = obtenerCarrito($pdo);
    if ($cantidad <= 0) {
        $pdo->prepare("DELETE FROM carrito_items WHERE id = :id AND carrito_id = :cid")
            ->execute([':id' => $itemId, ':cid' => $carritoId]);
    } else {
        $pdo->prepare("UPDATE carrito_items SET cantidad = :c WHERE id = :id AND carrito_id = :cid")
            ->execute([':c' => $cantidad, ':id' => $itemId, ':cid' => $carritoId]);
    }
}

function eliminarItem(PDO $pdo, int $itemId): void
{
    $carritoId = obtenerCarrito($pdo);
    $pdo->prepare("DELETE FROM carrito_items WHERE id = :id AND carrito_id = :cid")
        ->execute([':id' => $itemId, ':cid' => $carritoId]);
}

function vaciarCarrito(PDO $pdo): void
{
    $carritoId = obtenerCarrito($pdo);
    $pdo->prepare("DELETE FROM carrito_items WHERE carrito_id = :cid")
        ->execute([':cid' => $carritoId]);
}

/**
 * Totales: subtotal de productos con precio, y bandera de "hay por cotizar".
 */
function calcularTotales(array $items): array
{
    $subtotal = 0.0;
    $hayPorCotizar = false;
    $totalUnidades = 0;

    foreach ($items as $it) {
        $totalUnidades += (int)$it['cantidad'];
        $precio = (float)$it['precio_unitario'];
        if ($precio <= 0) {
            $hayPorCotizar = true;
        } else {
            $subtotal += $precio * (int)$it['cantidad'];
        }
    }
    return [
        'subtotal'       => $subtotal,
        'hay_por_cotizar'=> $hayPorCotizar,
        'unidades'       => $totalUnidades,
    ];
}