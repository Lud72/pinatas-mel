<?php
// /pinatas-mel/funciones/imagen.php
require_once __DIR__ . '/../config.php';

/**
 * Comprime y redimensiona una imagen a WEBP.
 * @return array{ok:bool, archivo?:string, error?:string}
 */
function comprimirImagenWebp(array $file): array
{
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'error' => 'Error al subir la imagen (código ' . ($file['error'] ?? '?') . ').'];
    }
    if ($file['size'] > MAX_PESO_IMAGEN) {
        return ['ok' => false, 'error' => 'La imagen no debe superar 5 MB.'];
    }

    $info = @getimagesize($file['tmp_name']);
    if ($info === false) {
        return ['ok' => false, 'error' => 'El archivo no es una imagen válida.'];
    }
    [$anchoOrig, $altoOrig, $tipo] = $info;

    $permitidos = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP];
    if (!in_array($tipo, $permitidos, true)) {
        return ['ok' => false, 'error' => 'Formato no soportado (usa jpg, png, gif o webp).'];
    }

    // Redimensionar si es más ancha que el máximo
    if ($anchoOrig > MAX_ANCHO_IMAGEN) {
        $ratio = MAX_ANCHO_IMAGEN / $anchoOrig;
        $nuevoAncho = MAX_ANCHO_IMAGEN;
        $nuevoAlto  = (int)($altoOrig * $ratio);
    } else {
        $nuevoAncho = $anchoOrig;
        $nuevoAlto  = $altoOrig;
    }

    $lienzo = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

    // Conservar transparencia en PNG/GIF/WEBP
    if (in_array($tipo, [IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP], true)) {
        imagealphablending($lienzo, false);
        imagesavealpha($lienzo, true);
        $transparente = imagecolorallocatealpha($lienzo, 0, 0, 0, 127);
        imagefilledrectangle($lienzo, 0, 0, $nuevoAncho, $nuevoAlto, $transparente);
    }

    $orig = match ($tipo) {
        IMAGETYPE_JPEG => imagecreatefromjpeg($file['tmp_name']),
        IMAGETYPE_PNG  => imagecreatefrompng($file['tmp_name']),
        IMAGETYPE_GIF  => imagecreatefromgif($file['tmp_name']),
        IMAGETYPE_WEBP => imagecreatefromwebp($file['tmp_name']),
        default        => false,
    };
    if (!$orig) {
        imagedestroy($lienzo);
        return ['ok' => false, 'error' => 'No se pudo procesar la imagen.'];
    }

    imagecopyresampled($lienzo, $orig, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $anchoOrig, $altoOrig);

    if (!is_dir(UPLOADS_REF)) {
        @mkdir(UPLOADS_REF, 0755, true);
    }

    $nombre = uniqid('ref_', true) . '.webp';
    $destino = UPLOADS_REF . $nombre;

    $ok = imagewebp($lienzo, $destino, CALIDAD_WEBP);
    imagedestroy($lienzo);
    imagedestroy($orig);

    return $ok
        ? ['ok' => true, 'archivo' => $nombre]
        : ['ok' => false, 'error' => 'No se pudo guardar la imagen comprimida.'];
}

/** Borra archivos de referencia huérfanos (si el INSERT falla). */
function borrarImagenes(array $nombres): void
{
    foreach ($nombres as $n) {
        $ruta = UPLOADS_REF . $n;
        if (is_file($ruta)) @unlink($ruta);
    }
}