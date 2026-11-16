<?php
/**
 * Consultas para la sección de Reportes (ventas).
 */

/**
 * Devuelve las líneas de venta (producto x pedido) de pedidos pagados o entregados.
 * Opcionalmente filtra por rango de fechas (YYYY-MM-DD).
 */
function obtenerReporteVentas(PDO $pdo, ?string $desde = null, ?string $hasta = null): array
{
    $sql = "
        SELECT
            pi.id              AS item_id,
            pi.cantidad,
            pi.precio_unitario,
            (pi.cantidad * pi.precio_unitario) AS subtotal,
            p.nombre           AS producto_nombre,
            p.imagen           AS producto_imagen,
            pe.folio,
            pe.cliente_nombre,
            pe.creado_en
        FROM pedido_items pi
        INNER JOIN productos p  ON p.id  = pi.producto_id
        INNER JOIN pedidos   pe ON pe.id = pi.pedido_id
        WHERE pe.estado IN ('pagado', 'entregado')
    ";

    $params = [];
    if ($desde !== null) {
        $sql .= " AND DATE(pe.creado_en) >= :desde";
        $params['desde'] = $desde;
    }
    if ($hasta !== null) {
        $sql .= " AND DATE(pe.creado_en) <= :hasta";
        $params['hasta'] = $hasta;
    }

    $sql .= " ORDER BY pe.creado_en DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Suma total de ventas (misma condición que el reporte).
 */
function totalVentas(PDO $pdo, ?string $desde = null, ?string $hasta = null): float
{
    $sql = "
        SELECT COALESCE(SUM(pi.cantidad * pi.precio_unitario), 0) AS total
        FROM pedido_items pi
        INNER JOIN pedidos pe ON pe.id = pi.pedido_id
        WHERE pe.estado IN ('pagado', 'entregado')
    ";

    $params = [];
    if ($desde !== null) {
        $sql .= " AND DATE(pe.creado_en) >= :desde";
        $params['desde'] = $desde;
    }
    if ($hasta !== null) {
        $sql .= " AND DATE(pe.creado_en) <= :hasta";
        $params['hasta'] = $hasta;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return (float)$stmt->fetchColumn();
}