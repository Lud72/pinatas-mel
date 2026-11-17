<?php
/**
 * Helper para subir imágenes al directorio /imagenes/.
 */

/**
 * Sube una imagen y devuelve el nombre del archivo guardado.
 * Devuelve null si no se subió archivo.
 * Lanza RuntimeException si hay error de validación o de movimiento.
 */
function subirImagen(array $archivo, string $prefijo = 'img'): ?string
{
    if (!isset($archivo['error']) || $archivo['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Error al subir la imagen (código ' . $archivo['error'] . ').');
    }

    // Validar tipo
    $tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $archivo['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $tiposPermitidos, true)) {
        throw new RuntimeException('Formato de imagen no permitido. Usa JPG, PNG, WEBP o GIF.');
    }

    // Validar tamaño (máx 5 MB)
    if ($archivo['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException('La imagen supera los 5 MB.');
    }

    // Nombre único: prefijo_timestamp_random.ext
    $ext = match ($mime) {
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
        default      => 'jpg',
    };
    $nombre = $prefijo . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;

    $destino = __DIR__ . '/../imagenes/' . $nombre;

    if (!move_uploaded_file($archivo['tmp_name'], $destino)) {
        throw new RuntimeException('No se pudo guardar la imagen en el servidor.');
    }

    return $nombre;
}

/**
 * Elimina una imagen del directorio /imagenes/ si existe.
 * No lanza error si no existe.
 */
function eliminarImagen(string $nombre): void
{
    if ($nombre === '') {
        return;
    }
    $ruta = __DIR__ . '/../imagenes/' . $nombre;
    if (is_file($ruta)) {
        @unlink($ruta);
    }
}