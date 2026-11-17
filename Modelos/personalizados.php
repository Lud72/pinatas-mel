<?php
/**
 * Consultas para pedidos personalizados.
 */

function crearPedidoPersonalizado(PDO $pdo, array $datos): int
{
    $stmt = $pdo->prepare("
        INSERT INTO pedidos_personalizados
            (cliente_nombre, cliente_telefono, personaje, texto_personalizado,
             tamano_cm, tipo_apertura, imagen_referencia, estado)
        VALUES
            (:cliente_nombre, :cliente_telefono, :personaje, :texto_personalizado,
             :tamano_cm, :tipo_apertura, :imagen_referencia, 'pendiente')
    ");
    $stmt->execute([
        'cliente_nombre'      => $datos['cliente_nombre'],
        'cliente_telefono'    => $datos['cliente_telefono'],
        'personaje'           => $datos['personaje'],
        'texto_personalizado' => $datos['texto_personalizado'] ?: null,
        'tamano_cm'           => $datos['tamano_cm'] ?: null,
        'tipo_apertura'       => $datos['tipo_apertura'] ?: null,
        'imagen_referencia'   => $datos['imagen_referencia'] ?: null,
    ]);
    return (int)$pdo->lastInsertId();
}

function obtenerPedidosPersonalizados(PDO $pdo, ?string $estado = null): array
{
    $sql = "SELECT id, cliente_nombre, cliente_telefono, personaje,
                   texto_personalizado, tamano_cm, tipo_apertura,
                   imagen_referencia, estado, creado_en
            FROM pedidos_personalizados";
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

function cambiarEstadoPersonalizado(PDO $pdo, int $id, string $nuevoEstado): void
{
    $permitidos = ['pendiente', 'en_proceso', 'terminado', 'cancelado'];
    if (!in_array($nuevoEstado, $permitidos, true)) {
        throw new InvalidArgumentException('Estado inválido.');
    }

    $stmt = $pdo->prepare("UPDATE pedidos_personalizados SET estado = :estado WHERE id = :id");
    $stmt->execute(['estado' => $nuevoEstado, 'id' => $id]);
}