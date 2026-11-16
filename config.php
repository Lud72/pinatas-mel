<?php
/**
 * Configuración global del proyecto Piñatas-Mel.
 * Todo lo que sea "constante" del proyecto va aquí.
 */

// Ruta base del proyecto en el servidor.
// Cambia esto si renombras la carpeta o subes a un hosting.
define('BASE_URL', '/pinatas-mel');

// Ruta física absoluta del proyecto en disco (útil para includes y uploads).
define('BASE_PATH', __DIR__);

// Zona horaria (opcional pero recomendado).
date_default_timezone_set('America/Mexico_City');