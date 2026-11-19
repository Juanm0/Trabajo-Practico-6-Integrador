<?php
/**
 * Registro - PetCare
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';

// Si ya está logueado, redirigir al dashboard
if (isLoggedIn()) {
    header('Location: ' . APP_URL . '/index.php');
    exit;
}

$pageTitle = 'Registrarse';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $telefono = trim($_POST['telefono'] ?? '');
    
    if (empty($nombre) || empty($apellido) || empty($email) || empty($password)) {
        $error = 'Todos los campos obligatorios deben completarse';
    } elseif ($password !== $confirmPassword) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } else {
        $result = register($nombre, $apellido, $email, $password, $telefono, 3); // Rol por defecto: Usuario común
        if ($result['success']) {
            $success = 'Registro exitoso. <a href="' . APP_URL . '/pages/auth/login.php">Inicia sesión</a>';
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - <?php echo $pageTitle; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo APP_URL; ?>/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-heart-pulse text-primary" style="font-size: 3rem;"></i>
                            <h2 class="mt-3 fw-bold"><?php echo APP_NAME; ?></h2>
                            <p class="text-muted">Crear nueva cuenta</p>
                        </div>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i><?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i><?php echo $success; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label"><i class="bi bi-person me-1"></i>Nombre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="apellido" class="form-label"><i class="bi bi-person me-1"></i>Apellido <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="apellido" name="apellido" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label"><i class="bi bi-envelope me-1"></i>Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="telefono" class="form-label"><i class="bi bi-phone me-1"></i>Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label"><i class="bi bi-lock me-1"></i>Contraseña <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password" required minlength="6">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label"><i class="bi bi-lock me-1"></i>Confirmar Contraseña <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6">
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-person-plus me-2"></i>Registrarse
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-4">
                            <p class="mb-0">¿Ya tienes cuenta? <a href="<?php echo APP_URL; ?>/pages/auth/login.php">Inicia sesión</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
