<?php
/**
 * Crear Cliente - PetCare
 */
require_once dirname(__DIR__, 2) . '/includes/config.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';

requireLogin();
requireRole(['Admin', 'Usuario común']);

$pageTitle = 'Nuevo Cliente';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = trim($_POST['dni'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    
    if (empty($dni) || empty($nombre) || empty($apellido)) {
        $error = 'DNI, nombre y apellido son obligatorios';
    } else {
        if (createCliente($dni, $nombre, $apellido, $telefono)) {
            $_SESSION['flash_message'] = 'Cliente creado exitosamente';
            $_SESSION['flash_type'] = 'success';
            header('Location: ' . APP_URL . '/pages/cliente/listar.php');
            exit;
        } else {
            $error = 'Error al crear el cliente. El DNI podría ya existir.';
        }
    }
}
require_once dirname(__DIR__, 2) . '/includes/header.php';

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-person-plus me-2"></i><?php echo $pageTitle; ?></h1>
    <a href="<?php echo APP_URL; ?>/pages/cliente/listar.php" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i><?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="dni" class="form-label"><i class="bi bi-badge me-1"></i>DNI <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="dni" name="dni" required maxlength="15">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label"><i class="bi bi-person me-1"></i>Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required maxlength="50">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="apellido" class="form-label"><i class="bi bi-person me-1"></i>Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required maxlength="50">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label"><i class="bi bi-phone me-1"></i>Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" maxlength="20">
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Guardar
                        </button>
                        <a href="<?php echo APP_URL; ?>/pages/cliente/listar.php" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__, 2) . '/includes/footer.php'; ?>
