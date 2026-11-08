<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Piñatas-Mel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-light">

<!-- Barra de Navegación Admin -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
    <div class="container-fluid px-4">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center text-decoration-none" href="admin-index.php">
            <img src="assets/img/logo-pinata.png" alt="Logo" width="30" class="me-2">
            <span class="fs-6" style="color: #D4005B;">Donde la diversión comienza a palazos.</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarAdmin">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link fw-bold" href="/pinatas-mel/paginas/admin/pedidos.php" style="color: #333;">Pedidos</a></li>
                <li class="nav-item"><a class="nav-link fw-bold" href="/pinatas-mel/paginas/admin/catalogo_A.php" style="color: #D4005B;">Catálogo</a></li>
                <li class="nav-item"><a class="nav-link fw-bold" href="/pinatas-mel/paginas/admin/reportes.php" style="color: #333;">Reportes</a></li>
                <li class="nav-item"><a class="nav-link fw-bold" href="/pinatas-mel/paginas/admin/productos_A.php" style="color: #333;">Productos</a></li>
                <li class="nav-item ms-4">
                    <!-- Botón para regresar a la vista de usuario -->
                    <a href="/pinatas-mel/index.php" class="btn btn-outline-danger btn-sm">Salir al sitio</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Aquí inicia el contenido de cada página de Admin -->
<main class="container py-4">