<?php

require_once dirname(__DIR__, 2) . '/includes/config.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';
requireLogin();
requireRole(['Admin']);

$pageTitle = 'Nuevo Veterinario';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = trim($_POST['dni'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $numeroRegistro = trim($_POST['numero_registro'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $especialidad = trim($_POST['especialidad'] ?? '');
    
    if (empty($dni) || empty($nombre) || empty($apellido) || empty($numeroRegistro)) {
        $error = 'DNI, nombre, apellido y número de registro son obligatorios';
    } else {
        if (createVeterinario($dni, $nombre, $apellido, $numeroRegistro, $telefono, $especialidad)) {
            $_SESSION['flash_message'] = 'Veterinario creado exitosamente';
            $_SESSION['flash_type'] = 'success';
            header('Location: ' . APP_URL . '/pages/veterinario/listar.php');
            exit;
        } else {
            $error = 'Error al crear el veterinario. El DNI o número de registro podría ya existir.';
        }
    }
}
require_once dirname(__DIR__, 2) . '/includes/header.php';

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-person-plus me-2"></i><?php echo $pageTitle; ?></h1>
    <a href="<?php echo APP_URL; ?>/pages/veterinario/listar.php" class="btn btn-outline-secondary">
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
                            <label for="numero_registro" class="form-label"><i class="bi bi-card-text me-1"></i>N° Registro <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="numero_registro" name="numero_registro" required maxlength="20" placeholder="Ej: VR-1001">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label"><i class="bi bi-person me-1"></i>Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required maxlength="50">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellido" class="form-label"><i class="bi bi-person me-1"></i>Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required maxlength="50">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label"><i class="bi bi-phone me-1"></i>Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" maxlength="20">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="especialidad" class="form-label"><i class="bi bi-award me-1"></i>Especialidad</label>
                            <select class="form-select" id="especialidad" name="especialidad">
                                <option value="">Seleccionar...</option>
                                <option value="Cardiología">Cardiología</option>
                                <option value="Dermatología">Dermatología</option>
                                <option value="Cirugía">Cirugía</option>
                                <option value="Medicina Interna">Medicina Interna</option>
                                <option value="Neurología">Neurología</option>
                                <option value="Oftalmología">Oftalmología</option>
                                <option value="Oncología">Oncología</option>
                                <option value="General">General</option>
                                <option value="Otra">Otra</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-info">
                            <i class="bi bi-save me-2"></i>Guardar
                        </button>
                        <a href="<?php echo APP_URL; ?>/pages/veterinario/listar.php" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__, 2) . '/includes/footer.php'; ?>
