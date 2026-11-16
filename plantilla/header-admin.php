<?php require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Piñatas-Mel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/estilos/styles.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background-color: #D4005B;">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center text-decoration-none" href="<?= BASE_URL ?>/index.php">
            <img src="<?= BASE_URL ?>/imagenes/logo.png" alt="Logo" width="30" class="me-2">
            <span class="fs-6 fst-italic text-white">Donde la diversión comienza a palazos.</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarAdmin">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item"><a class="nav-link text-white fw-bold" href="<?= BASE_URL ?>/paginas/admin/pedidos.php">Pedidos</a></li>
                <li class="nav-item"><a class="nav-link text-white fw-bold" href="<?= BASE_URL ?>/paginas/admin/catalogo_A.php">Catálogo</a></li>
                <li class="nav-item"><a class="nav-link text-white fw-bold" href="<?= BASE_URL ?>/paginas/admin/reportes.php">Reportes</a></li>
                <li class="nav-item"><a class="nav-link text-white fw-bold" href="<?= BASE_URL ?>/paginas/admin/productos_A.php">Productos</a></li>
                <li class="nav-item ms-4">
                    <a href="<?= BASE_URL ?>/index.php" class="btn btn-outline-light btn-sm">Salir al sitio</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">