<?php
/**
 * Consultas relacionadas con productos.
 */

function obtenerProductos(PDO $pdo, ?int $categoriaId = null, bool $soloActivos = true): array
{
    $sql = "SELECT p.id, p.categoria_id, p.slug, p.nombre, p.imagen, p.precio,
                   p.descripcion_corta, p.capacidad, p.rendimiento,
                   p.estructura, p.decoracion, p.activo
            FROM productos p
            WHERE 1=1";
    $params = [];

    if ($soloActivos) {
        $sql .= " AND p.activo = 1";
    }

    if ($categoriaId !== null) {
        $sql .= " AND p.categoria_id = :categoria_id";
        $params['categoria_id'] = $categoriaId;
    }

    $sql .= " ORDER BY p.id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function obtenerProductoPorSlug(PDO $pdo, string $slug): ?array
{
    $stmt = $pdo->prepare("SELECT id, categoria_id, slug, nombre, imagen, precio,
                                  descripcion_corta, capacidad, rendimiento,
                                  estructura, decoracion, activo
                           FROM productos
                           WHERE slug = :slug AND activo = 1
                           LIMIT 1");
    $stmt->execute(['slug' => $slug]);
    $prod = $stmt->fetch();
    return $prod ?: null;
}

function crearProducto(PDO $pdo, array $datos): int
{
    $stmt = $pdo->prepare("
        INSERT INTO productos
            (categoria_id, slug, nombre, imagen, precio, descripcion_corta,
             capacidad, rendimiento, estructura, decoracion, activo)
        VALUES
            (:categoria_id, :slug, :nombre, :imagen, :precio, :descripcion_corta,
             :capacidad, :rendimiento, :estructura, :decoracion, :activo)
    ");
    $stmt->execute([
        'categoria_id'      => $datos['categoria_id'],
        'slug'              => $datos['slug'],
        'nombre'            => $datos['nombre'],
        'imagen'            => $datos['imagen'],
        'precio'            => $datos['precio'],
        'descripcion_corta' => $datos['descripcion_corta'] ?? null,
        'capacidad'         => $datos['capacidad'] ?? null,
        'rendimiento'       => $datos['rendimiento'] ?? null,
        'estructura'        => $datos['estructura'] ?? null,
        'decoracion'        => $datos['decoracion'] ?? null,
        'activo'            => $datos['activo'] ?? 1,
    ]);
    return (int)$pdo->lastInsertId();
}

function actualizarProducto(PDO $pdo, int $id, array $datos): void
{
    $stmt = $pdo->prepare("
        UPDATE productos
        SET nombre            = :nombre,
            imagen            = :imagen,
            precio            = :precio,
            descripcion_corta = :descripcion_corta,
            capacidad         = :capacidad,
            rendimiento       = :rendimiento,
            estructura        = :estructura,
            decoracion        = :decoracion,
            activo            = :activo
        WHERE id = :id
    ");
    $stmt->execute([
        'nombre'            => $datos['nombre'],
        'imagen'            => $datos['imagen'],
        'precio'            => $datos['precio'],
        'descripcion_corta' => $datos['descripcion_corta'] ?? null,
        'capacidad'         => $datos['capacidad'] ?? null,
        'rendimiento'       => $datos['rendimiento'] ?? null,
        'estructura'        => $datos['estructura'] ?? null,
        'decoracion'        => $datos['decoracion'] ?? null,
        'activo'            => $datos['activo'] ?? 1,
        'id'                => $id,
    ]);
}

function eliminarProducto(PDO $pdo, int $id): bool
{
    if (productoEstaEnPedidos($pdo, $id)) {
        return false;
    }
    $stmt = $pdo->prepare("DELETE FROM productos WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return true;
}

function productoEstaEnPedidos(PDO $pdo, int $productoId): bool
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM pedido_items WHERE producto_id = :id");
    $stmt->execute(['id' => $productoId]);
    return (int)$stmt->fetchColumn() > 0;
}