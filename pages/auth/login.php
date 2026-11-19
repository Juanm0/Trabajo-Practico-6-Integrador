<?php
/**
 * Login - PetCare
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';

// Si ya está logueado, redirigir al dashboard
if (isLoggedIn()) {
    header('Location: ' . APP_URL . '/index.php');
    exit;
}

$pageTitle = 'Iniciar Sesión';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (login($email, $password)) {
        $_SESSION['flash_message'] = 'Bienvenido, ' . htmlspecialchars($_SESSION['user_nombre']);
        $_SESSION['flash_type'] = 'success';
        header('Location: ' . APP_URL . '/index.php');
        exit;
    } else {
        $error = 'Email o contraseña incorrectos';
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
            <div class="col-md-5 col-lg-4">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-heart-pulse text-primary" style="font-size: 3rem;"></i>
                            <h2 class="mt-3 fw-bold"><?php echo APP_NAME; ?></h2>
                            <p class="text-muted">Iniciar sesión en el sistema</p>
                        </div>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i><?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" novalidate>
                            <div class="mb-3">
                                <label for="email" class="form-label"><i class="bi bi-envelope me-1"></i>Email</label>
                                <input type="email" class="form-control" id="email" name="email" required autofocus>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label"><i class="bi bi-lock me-1"></i>Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-4">
                            <p class="mb-1">¿No tienes cuenta? <a href="<?php echo APP_URL; ?>/pages/auth/register.php">Regístrate</a></p>
                            <p class="mb-0"><small class="text-muted">Demo: admin@petcare.com / admin123</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
