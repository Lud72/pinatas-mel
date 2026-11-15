<?php

$pago_exitoso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['tel-personal'] ?? '';
    $titular = $_POST['nombre-titular'] ?? '';
    $tarjeta = $_POST['num-tarjeta'] ?? '';
    
    $pago_exitoso = true;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - Piñatas-Mel</title>
    <link rel="stylesheet" href="../../estilos/carrito.css">
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
            <a href="Carrito.php" class="cart-icon active-cart">🛒</a>
            <div class="menu-dropdown">
                <button class="menu-btn">☰</button>
                <div class="dropdown-content">
                    <a href="catalogo.php">Catálogo</a>
                    <a href="personalizado.php">Personalización</a>
                    <a href="distribuidor.php">Distribuidores</a>
                </div>
            </div>
        </nav>
    </header>
    <?php } ?>

    <main class="main-container">
        
        <section class="section-title">
            <h1>Mis Compras</h1>
        </section>

        <div class="cart-box">
            <div class="cart-items">
                <div class="item-row">
                    <div class="item-info">
                        <img src="../../imagenes/el_fredo.jpg" alt="Piñata El Fredo" class="item-thumb">
                        <span class="item-name">Piñata El Fredo</span>
                    </div>
                    <div class="item-controls">
                        <span class="item-price">$500</span>
                        <div class="qty-control">
                            <button type="button" onclick="updateQty(this, -1)">−</button>
                            <input type="text" value="1" readonly>
                            <button type="button" onclick="updateQty(this, 1)">+</button>
                        </div>
                    </div>
                </div>

                <div class="item-row">
                    <div class="item-info">
                        <img src="../../imagenes/spiderman.jpg" alt="Piñata Spiderman" class="item-thumb">
                        <span class="item-name">Piñata Spiderman</span>
                    </div>
                    <div class="item-controls">
                        <span class="item-price">$500</span>
                        <div class="qty-control">
                            <button type="button" onclick="updateQty(this, -1)">−</button>
                            <input type="text" value="1" readonly>
                            <button type="button" onclick="updateQty(this, 1)">+</button>
                        </div>
                    </div>
                </div>

                <div class="item-row">
                    <div class="item-info">
                        <img src="../../imagenes/Capitan_America.jpg" alt="Piñata Capitán América" class="item-thumb">
                        <span class="item-name">Piñata Capitán América</span>
                    </div>
                    <div class="item-controls">
                        <span class="item-price">$500</span>
                        <div class="qty-control">
                            <button type="button" onclick="updateQty(this, -1)">−</button>
                            <input type="text" value="1" readonly>
                            <button type="button" onclick="updateQty(this, 1)">+</button>
                        </div>
                    </div>
                </div>

                <div class="item-row">
                    <div class="item-info">
                        <img src="../../imagenes/Mexa_cactus.jpg" alt="Piñata Mexa Cactus" class="item-thumb">
                        <span class="item-name">Piñata Mexa Cactus</span>
                    </div>
                    <div class="item-controls">
                        <span class="item-price">$500</span>
                        <div class="qty-control">
                            <button type="button" onclick="updateQty(this, -1)">−</button>
                            <input type="text" value="1" readonly>
                            <button type="button" onclick="updateQty(this, 1)">+</button>
                        </div>
                    </div>
                </div>

                <div class="item-row">
                    <div class="item-info">
                        <img src="../../imagenes/Dino.jpg" alt="Piñata Dino" class="item-thumb">
                        <span class="item-name">Piñata Dino</span>
                    </div>
                    <div class="item-controls">
                        <span class="item-price">$500</span>
                        <div class="qty-control">
                            <button type="button" onclick="updateQty(this, -1)">−</button>
                            <input type="text" value="1" readonly>
                            <button type="button" onclick="updateQty(this, 1)">+</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cart-total">
                <span>TOTAL:$2500</span>
            </div>
        </div>

        <div id="btn-pagar-container" class="action-container">
            <button type="button" class="btn-main" onclick="goToStep('personal')">Pagar</button>
        </div>

        <form id="checkout-form" action="Carrito.php" method="POST">
            
            <!-- Paso 1: Datos Personales -->
            <div id="step-personal" class="sub-card hidden">
                <h2>Datos Personales</h2>
                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" id="direccion" name="direccion" placeholder="Ingresa una dirección">
                </div>
                <div class="form-group">
                    <label for="tel-personal">Teléfono</label>
                    <input type="tel" id="tel-personal" name="tel-personal" placeholder="Ingresa un número de teléfono">
                </div>
                <div class="button-group right-align">
                    <button type="button" class="btn-main" onclick="validatePersonal()">Siguiente</button>
                </div>
            </div>

            <div id="step-payment" class="sub-card hidden">
                <h2>Datos de Pago</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre-titular">Nombre del Titular</label>
                        <input type="text" id="nombre-titular" name="nombre-titular" placeholder="Ingresa un Nombre">
                    </div>
                    <div class="form-group">
                        <label for="cvv">Código de Seguridad</label>
                        <input type="text" id="cvv" name="cvv" placeholder="">
                    </div>
                </div>

                <div class="form-group">
                    <label for="num-tarjeta">Número de Tarjeta</label>
                    <input type="text" id="num-tarjeta" name="num-tarjeta" placeholder="Ingresa el número de tu tarjeta">
                </div>

                <div class="form-group">
                    <label>Fecha de expiración</label>
                    <div class="select-row">
                        <select name="mes_exp">
                            <option value="01">01-Enero</option>
                            <option value="02">02-Febrero</option>
                            <option value="03">03-Marzo</option>
                            <option value="04">04-Abril</option>
                        </select>
                        <select name="anio_exp">
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                        </select>
                    </div>
                </div>

                <div class="button-group space-between">
                    <button type="button" class="btn-main secondary" onclick="goToStep('personal')">Regresar</button>
                    <button type="submit" class="btn-main">Pagar</button>
                </div>
            </div>

        </form>

        <div id="step-success" class="success-banner <?php echo $pago_exitoso ? '' : 'hidden'; ?>">
            <p>¡Pago Exitoso!</p>
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
                <a href="#">Facebook</a>
                <a href="#">Instagram</a>
                <a href="#">X</a>
            </div>
        </div>
        <div class="footer-copy">
            &copy; <?php echo date('Y'); ?> Piñatas-Mel
        </div>
    </footer>
    <?php } ?>

    <script>
        <?php if ($pago_exitoso): ?>
            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById('btn-pagar-container').classList.add('hidden');
                document.getElementById('step-success').classList.remove('hidden');
            });
        <?php endif; ?>

        function goToStep(step) {
            document.getElementById('btn-pagar-container').classList.add('hidden');
            document.getElementById('step-personal').classList.add('hidden');
            document.getElementById('step-payment').classList.add('hidden');
            document.getElementById('step-success').classList.add('hidden');

            if (step === 'personal') {
                document.getElementById('step-personal').classList.remove('hidden');
            } else if (step === 'payment') {
                document.getElementById('step-payment').classList.remove('hidden');
            } else if (step === 'success') {
                document.getElementById('step-success').classList.remove('hidden');
            }
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        }

        function validatePersonal() {
            let dir = document.getElementById('direccion').value;
            let tel = document.getElementById('tel-personal').value;
            if(dir.trim() === '' || tel.trim() === '') {
                alert('Por favor completa los datos personales.');
                return;
            }
            goToStep('payment');
        }

        function updateQty(btn, change) {
            let input = btn.parentElement.querySelector('input');
            let val = parseInt(input.value) + change;
            if (val >= 1) input.value = val;
        }
    </script>
</body>
</html>