<?php
/**
 * Consultas relacionadas con categorías.
 * TODO: si en algún momento se necesita filtrar por "activa", ya lo hace.
 */

function obtenerCategorias(PDO $pdo, bool $soloActivas = true): array
{
    $sql = "SELECT id, slug, titulo, icono, banner, activa
            FROM categorias";
    if ($soloActivas) {
        $sql .= " WHERE activa = 1";
    }
    $sql .= " ORDER BY id";

    return $pdo->query($sql)->fetchAll();
}

function obtenerCategoriaPorSlug(PDO $pdo, string $slug): ?array
{
    $stmt = $pdo->prepare("SELECT id, slug, titulo, icono, banner, activa
                           FROM categorias
                           WHERE slug = :slug AND activa = 1
                           LIMIT 1");
    $stmt->execute(['slug' => $slug]);
    $cat = $stmt->fetch();
    return $cat ?: null;
}

function crearCategoria(PDO $pdo, array $datos): int
{
    $stmt = $pdo->prepare("
        INSERT INTO categorias (slug, titulo, icono, banner, activa)
        VALUES (:slug, :titulo, :icono, :banner, :activa)
    ");
    $stmt->execute([
        'slug'   => $datos['slug'],
        'titulo' => $datos['titulo'],
        'icono'  => $datos['icono'],
        'banner' => $datos['banner'] ?? 'franja.png',
        'activa' => $datos['activa'] ?? 1,
    ]);
    return (int)$pdo->lastInsertId();
}

function actualizarCategoria(PDO $pdo, int $id, array $datos): void
{
    $stmt = $pdo->prepare("
        UPDATE categorias
        SET titulo = :titulo,
            icono  = :icono,
            activa = :activa
        WHERE id = :id
    ");
    $stmt->execute([
        'titulo' => $datos['titulo'],
        'icono'  => $datos['icono'],
        'activa' => $datos['activa'] ?? 1,
        'id'     => $id,
    ]);
}

function eliminarCategoria(PDO $pdo, int $id): void
{
    $stmt = $pdo->prepare("DELETE FROM categorias WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

/**
 * Cuenta productos por categoría (una sola query, eficiente).
 * Devuelve [categoria_id => total].
 */
function contarProductosPorCategoria(PDO $pdo): array
{
    $stmt = $pdo->query("
        SELECT categoria_id, COUNT(*) AS total
        FROM productos
        GROUP BY categoria_id
    ");
    $resultado = [];
    foreach ($stmt->fetchAll() as $row) {
        $resultado[(int)$row['categoria_id']] = (int)$row['total'];
    }
    return $resultado;
}

/**
 * Genera un slug a partir de un texto.
 */
function generarSlug(string $texto): string
{
    $texto = strtolower(trim($texto));
    $texto = iconv('UTF-8', 'ASCII//TRANSLIT', $texto);
    $texto = preg_replace('/[^a-z0-9]+/', '-', $texto);
    return trim($texto, '-');
}