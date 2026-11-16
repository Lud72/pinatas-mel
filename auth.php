<?php
/**
 * Helpers de autenticación y sesión.
 * Se incluye desde header-admin.php y desde login.php.
 */

// Iniciar sesión solo una vez.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Devuelve true si hay un admin logueado.
 */
function estaLogueado(): bool
{
    return isset($_SESSION['admin_id']);
}

/**
 * Devuelve los datos del admin logueado (o null).
 */
function adminActual(): ?array
{
    if (!estaLogueado()) {
        return null;
    }
    return [
        'id'     => $_SESSION['admin_id'],
        'usuario' => $_SESSION['admin_usuario'] ?? '',
        'nombre' => $_SESSION['admin_nombre'] ?? '',
    ];
}

/**
 * Si no hay sesión, redirige al login.
 * Úsalo al inicio de cualquier página protegida.
 */
function requiereLogin(): void
{
    if (!estaLogueado()) {
        header('Location: ' . BASE_URL . '/paginas/admin/login.php');
        exit;
    }
}

/**
 * Redirige al panel si YA hay sesión (para no mostrar el login de nuevo).
 */
function redirigirSiLogueado(): void
{
    if (estaLogueado()) {
        header('Location: ' . BASE_URL . '/paginas/admin/pedidos.php');
        exit;
    }
}