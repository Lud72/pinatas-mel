<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../conexion.php';

// Si ya está logueado, mandarlo al panel
redirigirSiLogueado();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario  = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($usuario === '' || $password === '') {
        $error = 'Por favor ingresa usuario y contraseña.';
    } else {
        $stmt = $pdo->prepare("SELECT id, usuario, password_hash, nombre, activo
                               FROM usuarios_admin
                               WHERE usuario = :usuario
                               LIMIT 1");
        $stmt->execute(['usuario' => $usuario]);
        $admin = $stmt->fetch();

        if (!$admin || !$admin['activo']) {
            $error = 'Usuario o contraseña incorrectos.';
        } elseif (!password_verify($password, $admin['password_hash'])) {
            $error = 'Usuario o contraseña incorrectos.';
        } else {
            // Login exitoso
            session_regenerate_id(true);
            $_SESSION['admin_id']      = (int)$admin['id'];
            $_SESSION['admin_usuario'] = $admin['usuario'];
            $_SESSION['admin_nombre']  = $admin['nombre'];

            // Actualizar ultimo_login
            $pdo->prepare("UPDATE usuarios_admin SET ultimo_login = NOW() WHERE id = :id")
                ->execute(['id' => $admin['id']]);

            header('Location: ' . BASE_URL . '/paginas/admin/pedidos.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin Piñatas-Mel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/estilos/styles.css">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh;">

<div class="container" style="max-width:420px;">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">

            <div class="text-center mb-4">
                <img src="<?= BASE_URL ?>/imagenes/logo.png" alt="Logo" width="60" class="mb-2">
                <h1 class="fs-4 fw-bold mb-0">Panel de Administración</h1>
                <p class="text-muted small mb-0">Piñatas-Mel</p>
            </div>

            <?php if ($error !== ''): ?>
                <div class="alert alert-danger py-2 small">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php" autocomplete="off">
                <div class="mb-3">
                    <label for="usuario" class="form-label fw-semibold">Usuario</label>
                    <input type="text" id="usuario" name="usuario" class="form-control"
                           value="<?= htmlspecialchars($_POST['usuario'] ?? '') ?>" required autofocus>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primario w-100 rounded-pill py-2 fw-bold">
                    Entrar
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="<?= BASE_URL ?>/index.php" class="text-decoration-none small">
                    ← Volver al sitio
                </a>
            </div>

        </div>
    </div>
</div>

</body>
</html>