<?php
/**
 * Consultas relacionadas con productos.
 */

function obtenerProductos(PDO $pdo, ?int $categoriaId = null): array
{
    $sql = "SELECT p.id, p.categoria_id, p.slug, p.nombre, p.imagen, p.precio,
                   p.descripcion_corta, p.capacidad, p.rendimiento,
                   p.estructura, p.decoracion, p.activo
            FROM productos p
            WHERE p.activo = 1";
    $params = [];

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