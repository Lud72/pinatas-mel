<?php
// /pinatas-mel/paginas/Usuario/procesar_personalizado.php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../../funciones/imagen.php';

// Solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: personalizado.php');
    exit;
}

// CSRF
if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    $_SESSION['errores_personalizado'] = ['Token de seguridad inválido. Recarga la página.'];
    header('Location: personalizado.php');
    exit;
}

$errores = [];

$nombre    = trim($_POST['nombre'] ?? '');
$telefono  = trim($_POST['telefono'] ?? '');
$personaje = trim($_POST['personaje'] ?? '');
$texto     = trim($_POST['texto_personalizado'] ?? '');
$tamano    = filter_input(INPUT_POST, 'tamano', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => TAMANO_MIN, 'max_range' => TAMANO_MAX],
]);
$aperturas = $_POST['apertura'] ?? [];

// Validaciones
if ($nombre === '' || mb_strlen($nombre) > 100)   $errores[] = 'El nombre es obligatorio (máx. 100 caracteres).';
if ($telefono === '' || !preg_match('/^[0-9+\-\s()]{7,20}$/', $telefono)) {
    $errores[] = 'Teléfono inválido (7–20 dígitos).';
}
if ($personaje === '' || mb_strlen($personaje) > 255) $errores[] = 'Describe el personaje (máx. 255 caracteres).';
if ($texto !== '' && mb_strlen($texto) > 100)        $errores[] = 'El texto personalizado no debe superar 100 caracteres.';
if ($tamano === false || $tamano === null) {
    $errores[] = 'Tamaño inválido (entre ' . TAMANO_MIN . ' y ' . TAMANO_MAX . ' cm).';
}
if (empty($aperturas)) {
    $errores[] = 'Selecciona al menos un tipo de apertura.';
}
$aperturas = array_values(array_intersect($aperturas, APERTURAS_VALIDAS));
if (empty($aperturas) && !in_array('Selecciona al menos un tipo de apertura.', $errores, true)) {
    $errores[] = 'Tipo de apertura inválido.';
}

// Procesar imágenes (múltiples)
$imagenesGuardadas = [];
$totalArchivos = count($_FILES['referencia']['name'] ?? []);
if ($totalArchivos > MAX_IMAGENES) {
    $errores[] = 'Máximo ' . MAX_IMAGENES . ' imágenes de referencia.';
} else {
    for ($i = 0; $i < $totalArchivos; $i++) {
        if (($_FILES['referencia']['error'][$i] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            continue; // sin archivo en esa posición
        }
        $file = [
            'name'     => $_FILES['referencia']['name'][$i],
            'type'     => $_FILES['referencia']['type'][$i],
            'tmp_name' => $_FILES['referencia']['tmp_name'][$i],
            'error'    => $_FILES['referencia']['error'][$i],
            'size'     => $_FILES['referencia']['size'][$i],
        ];
        $res = comprimirImagenWebp($file);
        if ($res['ok']) {
            $imagenesGuardadas[] = $res['archivo'];
        } else {
            $errores[] = "Imagen " . ($i + 1) . ": " . $res['error'];
        }
    }
}

// Si hay errores, borrar imágenes ya subidas y volver
if (!empty($errores)) {
    borrarImagenes($imagenesGuardadas);
    $_SESSION['errores_personalizado']  = $errores;
    $_SESSION['old_personalizado']      = $_POST; // para repoblar el form
    header('Location: personalizado.php');
    exit;
}

// Insertar en BD
try {
    $sql = "INSERT INTO pedidos_personalizados
            (nombre_cliente, telefono, personaje, referencia_imagen,
             texto_personalizado, tamano_cm, tipo_apertura)
            VALUES (:nombre, :telefono, :personaje, :imagen, :texto, :tamano, :apertura)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombre'    => $nombre,
        ':telefono'  => $telefono,
        ':personaje' => $personaje,
        ':imagen'    => $imagenesGuardadas[0] ?? null, // primera como principal
        ':texto'     => $texto !== '' ? $texto : null,
        ':tamano'    => $tamano,
        ':apertura'  => implode(', ', $aperturas),
    ]);
    $pedidoId = (int)$pdo->lastInsertId();

    // Guardar todas las imágenes extra (si tienes tabla pedidos_imagenes)
    if (count($imagenesGuardadas) > 1) {
        $stmtImg = $pdo->prepare("INSERT INTO pedidos_personalizados_imagenes (pedido_id, archivo) VALUES (:id, :archivo)");
        foreach ($imagenesGuardadas as $img) {
            $stmtImg->execute([':id' => $pedidoId, ':archivo' => $img]);
        }
    }

    require_once __DIR__ . '/../../funciones/carrito.php';

// ... después del INSERT y del lastInsertId ...

// Guardar imágenes extra
if (count($imagenesGuardadas) > 1) {
    $stmtImg = $pdo->prepare("INSERT INTO pedidos_personalizados_imagenes (pedido_id, archivo) VALUES (:id, :archivo)");
    foreach ($imagenesGuardadas as $img) {
        $stmtImg->execute([':id' => $pedidoId, ':archivo' => $img]);
    }
}

// Añadir al carrito (persistente en BD)
agregarPersonalizadoAlCarrito($pdo, $pedidoId, $personaje);

$_SESSION['exito_personalizado'] = true;
header('Location: Carrito.php');
exit;

} catch (PDOException $e) {
    // Log interno, mensaje genérico al usuario
    error_log('[personalizado] ' . $e->getMessage(), 3, BASE_PATH . '/logs/errores.log');
    borrarImagenes($imagenesGuardadas);
    $_SESSION['errores_personalizado'] = ['No pudimos guardar tu solicitud. Intenta más tarde.'];
    $_SESSION['old_personalizado']     = $_POST;
    header('Location: personalizado.php');
    exit;
}