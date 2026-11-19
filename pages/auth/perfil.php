<?php
/**
 * Perfil de usuario - PetCare
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/header.php';

requireLogin();

$pageTitle = 'Mi Perfil';
$user = getCurrentUser();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-person me-2"></i><?php echo $pageTitle; ?></h1>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-person-circle" style="font-size: 8rem;"></i>
                </div>
                <h4><?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellido']); ?></h4>
                <span class="badge bg-primary fs-6"><?php echo htmlspecialchars($user['rol_nombre']); ?></span>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-gear me-2"></i>Acciones</h6>
                <div class="d-grid gap-2">
                    <a href="<?php echo APP_URL; ?>/pages/auth/cambiar_password.php" class="btn btn-outline-primary">
                        <i class="bi bi-key me-2"></i>Cambiar Contraseña
                    </a>
                    <a href="<?php echo APP_URL; ?>/pages/auth/logout.php" class="btn btn-outline-danger">
                        <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Información Personal</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label text-muted">Nombre</label>
                    <div class="col-sm-9">
                        <p class="form-control-plaintext"><?php echo htmlspecialchars($user['nombre']); ?></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label text-muted">Apellido</label>
                    <div class="col-sm-9">
                        <p class="form-control-plaintext"><?php echo htmlspecialchars($user['apellido']); ?></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label text-muted">Email</label>
                    <div class="col-sm-9">
                        <p class="form-control-plaintext"><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label text-muted">Teléfono</label>
                    <div class="col-sm-9">
                        <p class="form-control-plaintext"><?php echo htmlspecialchars($user['telefono'] ?? 'No especificado'); ?></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label text-muted">Rol</label>
                    <div class="col-sm-9">
                        <p class="form-control-plaintext"><?php echo htmlspecialchars($user['rol_nombre']); ?></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label text-muted">Fecha de Registro</label>
                    <div class="col-sm-9">
                        <p class="form-control-plaintext"><?php echo isset($user['fecha_registro']) ? date('d/m/Y H:i', strtotime($user['fecha_registro'])) : 'No disponible'; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
