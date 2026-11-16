<?php
// /pinatas-mel/config.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_URL', '/pinatas-mel');
define('BASE_PATH', __DIR__);
define('UPLOADS_REF', BASE_PATH . '/uploads/referencias/');
define('UPLOADS_REF_URL', BASE_URL . '/uploads/referencias/');

define('MAX_IMAGENES', 5);
define('MAX_PESO_IMAGEN', 5 * 1024 * 1024); // 5 MB
define('MAX_ANCHO_IMAGEN', 1200);
define('CALIDAD_WEBP', 75);

define('TAMANO_MIN', 30);
define('TAMANO_MAX', 200);

define('APERTURAS_VALIDAS', ['tradicional', 'cintas']);