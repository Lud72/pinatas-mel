<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piñatas-Mel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/pinatas-mel/estilos/styles.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background-color: #D4005B;">
    <div class="container-fluid px-4">

        <a class="navbar-brand d-flex align-items-center" href="/pinatas-mel/index.php">
            <img src="/pinatas-mel/imagenes/logo.png" alt="Logo" width="40" class="me-2">
            <span class="fs-6 fst-italic">Donde la diversión comienza a palazos.</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPublico">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarPublico">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item"><a class="nav-link text-white" href="/pinatas-mel/index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="/pinatas-mel/paginas/Usuario/nosotros.php">Nosotros</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="/pinatas-mel/paginas/Usuario/contactos.php">Contáctanos</a></li>

                <!-- Desplegable hamburguesa (solo escritorio) -->
                <li class="nav-item dropdown d-none d-lg-block">
                    <button class="btn btn-link text-white fs-3 p-0 ms-2" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-list"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/pinatas-mel/paginas/Usuario/catalogo.php">
                            <i class="bi bi-grid me-2"></i>Catálogo</a></li>
                        <li><a class="dropdown-item" href="/pinatas-mel/paginas/Usuario/personalizado.php">
                            <i class="bi bi-palette me-2"></i>Personalización</a></li>
                        <li><a class="dropdown-item" href="/pinatas-mel/paginas/Usuario/distribuidor.php">
                            <i class="bi bi-truck me-2"></i>Distribuidores</a></li>
                    </ul>
                </li>

                <li class="nav-item ms-3">
                    <a class="nav-link text-white fs-4" href="/pinatas-mel/paginas/Usuario/Carrito.php">
                        <i class="bi bi-cart3"></i>
                    </a>
                </li>
            </ul>

            <!-- Versión móvil: los mismos enlaces dentro del colapso -->
            <ul class="navbar-nav d-lg-none mt-2">
                <li class="nav-item"><a class="nav-link text-white" href="/pinatas-mel/paginas/Usuario/catalogo.php">Catálogo</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="/pinatas-mel/paginas/Usuario/personalizado.php">Personalización</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="/pinatas-mel/paginas/Usuario/distribuidor.php">Distribuidores</a></li>
            </ul>
        </div>
    </div>
</nav>

<main class="container-fluid p-0">