<?php
/**
 * Cambiar contraseña - PetCare
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/header.php';

requireLogin();

$pageTitle = 'Cambiar Contraseña';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $error = 'Todos los campos son obligatorios';
    } elseif ($newPassword !== $confirmPassword) {
        $error = 'Las contraseñas nuevas no coinciden';
    } elseif (strlen($newPassword) < 6) {
        $error = 'La nueva contraseña debe tener al menos 6 caracteres';
    } else {
        $result = changePassword($_SESSION['user_id'], $currentPassword, $newPassword);
        if ($result['success']) {
            $success = $result['message'];
        } else {
            $error = $result['message'];
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-key me-2"></i><?php echo $pageTitle; ?></h1>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
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
                    <div class="mb-3">
                        <label for="current_password" class="form-label"><i class="bi bi-lock me-1"></i>Contraseña Actual <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label"><i class="bi bi-lock me-1"></i>Nueva Contraseña <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6">
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label"><i class="bi bi-lock me-1"></i>Confirmar Nueva Contraseña <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6">
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-3">
                    <a href="<?php echo APP_URL; ?>/index.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
