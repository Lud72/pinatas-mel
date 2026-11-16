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