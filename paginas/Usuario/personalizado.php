<?php
include_once __DIR__ . '/../../conexion.php';

$mensaje_exito = false;
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = trim($_POST['nombre'] ?? '');
    $telefono  = trim($_POST['telefono'] ?? '');
    $personaje = trim($_POST['personaje'] ?? '');
    $texto     = trim($_POST['texto-personalizado'] ?? '');
    $tamano    = $_POST['tamano'] ?? null;
    $aperturas = $_POST['apertura'] ?? [];

    if ($nombre === '')   $errores[] = 'El nombre es obligatorio.';
    if ($telefono === '') $errores[] = 'El teléfono es obligatorio.';
    if ($personaje === '') $errores[] = 'Describe el personaje.';

    $nombre_imagen = null;
    if (isset($_FILES['referencia']) && $_FILES['referencia']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['referencia']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (!in_array($ext, $permitidas)) {
            $errores[] = 'La imagen debe ser jpg, png, gif o webp.';
        } elseif ($_FILES['referencia']['size'] > 5 * 1024 * 1024) {
            $errores[] = 'La imagen no debe superar 5 MB.';
        } else {
            // ==========================================
            // COMPRESIÓN Y REDIMENSIONADO DE IMAGEN
            // ==========================================
            $ruta_temporal = $_FILES['referencia']['tmp_name'];
            
            // 1. Obtener dimensiones originales
            list($ancho_orig, $alto_orig, $tipo) = getimagesize($ruta_temporal);
            
            // 2. Calcular nuevas dimensiones (máximo 1200px de ancho)
            $max_ancho = 1200;
            if ($ancho_orig > $max_ancho) {
                $ratio = $max_ancho / $ancho_orig;
                $nuevo_ancho = $max_ancho;
                $nuevo_alto = (int)($alto_orig * $ratio);
            } else {
                $nuevo_ancho = $ancho_orig;
                $nuevo_alto = $alto_orig;
            }
            
            // 3. Crear un lienzo (canvas) en blanco
            $lienzo = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
            
            // 4. Cargar la imagen original según su formato
            switch ($tipo) {
                case IMAGETYPE_JPEG: $imagen_orig = imagecreatefromjpeg($ruta_temporal); break;
                case IMAGETYPE_PNG:  $imagen_orig = imagecreatefrompng($ruta_temporal); break;
                case IMAGETYPE_GIF:  $imagen_orig = imagecreatefromgif($ruta_temporal); break;
                case IMAGETYPE_WEBP: $imagen_orig = imagecreatefromwebp($ruta_temporal); break;
                default:
                    $errores[] = 'Formato de imagen no soportado.';
                    $imagen_orig = null;
                    break;
            }
            
            if (isset($imagen_orig) && $imagen_orig) {
                // 5. Copiar y redimensionar al lienzo
                imagecopyresampled($lienzo, $imagen_orig, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho_orig, $alto_orig);
                
                // 6. Guardar como WEBP (pesa hasta 80% menos que JPG)
                $nombre_imagen = uniqid('ref_') . '.webp';
                $destino = __DIR__ . '/../../uploads/referencias/' . $nombre_imagen;
                
                if (!imagewebp($lienzo, $destino, 75)) { // 75 = calidad (0-100)
                    $errores[] = 'No se pudo guardar la imagen comprimida.';
                    $nombre_imagen = null;
                }
                
                // 7. Liberar memoria
                imagedestroy($lienzo);
                imagedestroy($imagen_orig);
            }
        }
    }

    if (empty($errores)) {
        try {
            $sql = "INSERT INTO pedidos_personalizados 
                    (nombre_cliente, telefono, personaje, referencia_imagen,
                     texto_personalizado, tamano_cm, tipo_apertura)
                    VALUES (:nombre, :telefono, :personaje, :imagen, :texto, :tamano, :apertura)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre'   => $nombre,
                ':telefono' => $telefono,
                ':personaje' => $personaje,
                ':imagen'   => $nombre_imagen,
                ':texto'    => $texto ?: null,
                ':tamano'   => $tamano ?: null,
                ':apertura' => implode(', ', $aperturas) ?: null,
            ]);

            $mensaje_exito = true;
        } catch (PDOException $e) {
            $errores[] = 'Error al guardar: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personalización - Piñatas-Mel</title>
    <link rel="stylesheet" href="../../estilos/personalizado.css">
</head>
<body>

    <?php 
        if (file_exists('../../plantilla/header-publico.php')) {
            include '../../plantilla/header-publico.php';
        } else {
    ?>
    <header class="header">
        <div class="logo-container">
            <img src="../../imagenes/logo.png" alt="Piñatas-Mel Logo" class="logo">
            <span class="slogan">Donde la diversión comienza a palazos.</span>
        </div>
        <nav class="nav">
            <a href="../../index.php">Inicio</a>
            <a href="nosotros.php">Nosotros</a>
            <a href="contactos.php">Contáctanos</a>
            <a href="Carrito.php" class="cart-icon">🛒</a>
            <div class="menu-dropdown">
                <button class="menu-btn">☰</button>
                <div class="dropdown-content">
                    <a href="catalogo.php">Catálogo</a>
                    <a href="personalizado.php" class="active">Personalización</a>
                    <a href="distribuidor.php">Distribuidores</a>
                </div>
            </div>
        </nav>
    </header>
    <?php } ?>

    <main class="creation-zone">
        <div class="hero-content">
            <div class="hero-image-container">
                <img src="../../imagenes/Pinata_per_crea.png" alt="Zona de creación" class="hero-img">
            </div>
            <div class="hero-text">
                <h1>¡Llegaste a la zona de creación!</h1>
                <p>Platícanos tu idea y hazla realidad</p>
            </div>
        </div>

        <section class="form-card">
            <form id="customization-form" action="personalizado.php" method="POST" enctype="multipart/form-data">
                
                <div id="step-1" class="form-step active">
                    <h2>Datos de Contacto</h2>
                    
                    <div class="form-group">
                        <label for="nombre">Nombre Completo</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Ingresa un Nombre" required>
                    </div>

                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono" placeholder="Ingresa el número de teléfono" required>
                    </div>

                    <div class="button-group single-right">
                        <button type="button" class="btn btn-primary" onclick="nextStep(2)">Siguiente</button>
                    </div>
                </div>

                <div id="step-2" class="form-step">
                    <h2>Detalles del Diseño</h2>
                    
                    <div class="form-group">
                        <label for="personaje">¿Qué personaje, forma u objeto quieres?</label>
                        <input type="text" id="personaje" name="personaje">
                    </div>

                    <div class="form-group">
                        <label for="referencia">Sube una imagen de referencia / foto</label>
                        <div class="file-upload-box">
                            <input type="file" id="referencia" name="referencia" accept="image/*">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="texto-personalizado">Texto o Nombre personalizado en la piñata</label>
                        <input type="text" id="texto-personalizado" name="texto-personalizado">
                    </div>

                    <div class="button-group">
                        <button type="button" class="btn btn-secondary" onclick="nextStep(1)">Regresar</button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(3)">Siguiente</button>
                    </div>
                </div>

                <div id="step-3" class="form-step">
                    <h2>Detalles de Tamaño</h2>
                    
                    <div class="form-group">
                        <label for="tamano">Tamaño deseado(cm)</label>
                        <input type="number" id="tamano" name="tamano" min="1">
                    </div>

                    <div class="form-group">
                        <label class="label-title">Tipo de apertura</label>
                        <label class="checkbox-container">
                            <input type="checkbox" name="apertura[]" value="tradicional">
                            <span class="checkmark"></span>
                            De Palo / Tradicional
                        </label>
                        <label class="checkbox-container">
                            <input type="checkbox" name="apertura[]" value="cintas">
                            <span class="checkmark"></span>
                            De Cintas / Jalón
                        </label>
                    </div>

                    <div class="button-group">
                        <button type="button" class="btn btn-secondary" onclick="nextStep(2)">Regresar</button>
                        <button type="submit" class="btn btn-primary">Añadir al Carrito</button>
                    </div>
                </div>

            </form>
        </section>

        <div id="success-alert" class="success-banner <?php echo $mensaje_exito ? '' : 'hidden'; ?>">
            <p>Se agregó exitosamente</p>
        </div>
    </main>

    <?php if (!empty($errores)): ?>
    <div class="alert alert-danger">
        <strong>Hubo errores:</strong>
        <ul class="mb-0">
            <?php foreach ($errores as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <?php 
        if (file_exists('../../plantilla/footer.php')) {
            include '../../plantilla/footer.php';
        } else {
    ?>
    <footer class="footer">
        <div class="footer-social">
            <span>Síguenos</span>
            <div class="social-icons">
                <a href="#"><i class="icon-facebook"></i>Facebook</a>
                <a href="#"><i class="icon-instagram"></i>Instagram</a>
                <a href="#"><i class="icon-x"></i>X</a>
            </div>
        </div>
        <div class="footer-copy">
            &copy; <?php echo date('Y'); ?> Piñatas-Mel
        </div>
    </footer>
    <?php } ?>

    <script>
        function nextStep(stepNumber) {
            document.querySelectorAll('.form-step').forEach(step => {
                step.classList.remove('active');
            });
            document.getElementById('step-' + stepNumber).classList.add('active');
        }
    </script>
</body>
</html>