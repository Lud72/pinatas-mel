<?php
/**
 * Consultas relacionadas con pedidos.
 */

function obtenerPedidos(PDO $pdo, ?string $estado = null): array
{
    $sql = "SELECT id, folio, cliente_nombre, cliente_telefono, cliente_direccion,
                   total, estado, creado_en
            FROM pedidos";
    $params = [];

    if ($estado !== null) {
        $sql .= " WHERE estado = :estado";
        $params['estado'] = $estado;
    }

    $sql .= " ORDER BY creado_en DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function obtenerPedidoConItems(PDO $pdo, int $pedidoId): ?array
{
    $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $pedidoId]);
    $pedido = $stmt->fetch();

    if (!$pedido) {
        return null;
    }

    $stmt = $pdo->prepare("
        SELECT pi.id, pi.cantidad, pi.precio_unitario,
               p.nombre AS producto_nombre, p.imagen AS producto_imagen
        FROM pedido_items pi
        INNER JOIN productos p ON p.id = pi.producto_id
        WHERE pi.pedido_id = :pedido_id
    ");
    $stmt->execute(['pedido_id' => $pedidoId]);
    $pedido['items'] = $stmt->fetchAll();

    return $pedido;
}

function contarPedidosPorEstado(PDO $pdo): array
{
    $stmt = $pdo->query("
        SELECT estado, COUNT(*) AS total
        FROM pedidos
        GROUP BY estado
    ");

    $resultado = [];
    foreach ($stmt->fetchAll() as $row) {
        $resultado[$row['estado']] = (int)$row['total'];
    }
    return $resultado;
}

function cambiarEstadoPedido(PDO $pdo, int $pedidoId, string $nuevoEstado): void
{
    $permitidos = ['pendiente', 'pagado', 'enviado', 'entregado', 'cancelado'];
    if (!in_array($nuevoEstado, $permitidos, true)) {
        throw new InvalidArgumentException('Estado inválido: ' . $nuevoEstado);
    }

    $stmt = $pdo->prepare("UPDATE pedidos SET estado = :estado WHERE id = :id");
    $stmt->execute(['estado' => $nuevoEstado, 'id' => $pedidoId]);
}