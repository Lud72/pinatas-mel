<?php
$mensaje_exito = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $personaje = $_POST['personaje'] ?? '';
    $texto_personalizado = $_POST['texto-personalizado'] ?? '';
    $tamano = $_POST['tamano'] ?? '';
    $aperturas = $_POST['apertura'] ?? [];

    $nombre_imagen = '';
    if (isset($_FILES['referencia']) && $_FILES['referencia']['error'] === UPLOAD_ERR_OK) {
        $nombre_imagen = basename($_FILES['referencia']['name']);
    }

    $mensaje_exito = true;
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