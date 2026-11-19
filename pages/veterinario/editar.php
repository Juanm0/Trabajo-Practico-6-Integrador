<?php
/**
 * Editar Veterinario - PetCare
 */
require_once dirname(__DIR__, 2) . '/includes/config.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';
requireLogin();
requireRole(['Admin']);

$pageTitle = 'Editar Veterinario';
$id = $_GET['id'] ?? 0;
$vet = getVeterinarioById($id);

if (!$vet) {
    $_SESSION['flash_message'] = 'Veterinario no encontrado';
    $_SESSION['flash_type'] = 'danger';
    header('Location: ' . APP_URL . '/pages/veterinario/listar.php');
    exit;
}

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
        if (updateVeterinario($id, $dni, $nombre, $apellido, $numeroRegistro, $telefono, $especialidad)) {
            $_SESSION['flash_message'] = 'Veterinario actualizado exitosamente';
            $_SESSION['flash_type'] = 'success';
            header('Location: ' . APP_URL . '/pages/veterinario/listar.php');
            exit;
        } else {
            $error = 'Error al actualizar el veterinario';
        }
    }
}
require_once dirname(__DIR__, 2) . '/includes/header.php';

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-pencil me-2"></i><?php echo $pageTitle; ?></h1>
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
                            <input type="text" class="form-control" id="dni" name="dni" required maxlength="15" value="<?php echo htmlspecialchars($vet['dni']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="numero_registro" class="form-label"><i class="bi bi-card-text me-1"></i>N° Registro <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="numero_registro" name="numero_registro" required maxlength="20" value="<?php echo htmlspecialchars($vet['numero_registro']); ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label"><i class="bi bi-person me-1"></i>Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required maxlength="50" value="<?php echo htmlspecialchars($vet['nombre']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellido" class="form-label"><i class="bi bi-person me-1"></i>Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required maxlength="50" value="<?php echo htmlspecialchars($vet['apellido']); ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label"><i class="bi bi-phone me-1"></i>Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" maxlength="20" value="<?php echo htmlspecialchars($vet['telefono'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="especialidad" class="form-label"><i class="bi bi-award me-1"></i>Especialidad</label>
                            <select class="form-select" id="especialidad" name="especialidad">
                                <option value="">Seleccionar...</option>
                                <option value="Cardiología" <?php echo ($vet['especialidad'] ?? '') === 'Cardiología' ? 'selected' : ''; ?>>Cardiología</option>
                                <option value="Dermatología" <?php echo ($vet['especialidad'] ?? '') === 'Dermatología' ? 'selected' : ''; ?>>Dermatología</option>
                                <option value="Cirugía" <?php echo ($vet['especialidad'] ?? '') === 'Cirugía' ? 'selected' : ''; ?>>Cirugía</option>
                                <option value="Medicina Interna" <?php echo ($vet['especialidad'] ?? '') === 'Medicina Interna' ? 'selected' : ''; ?>>Medicina Interna</option>
                                <option value="Neurología" <?php echo ($vet['especialidad'] ?? '') === 'Neurología' ? 'selected' : ''; ?>>Neurología</option>
                                <option value="Oftalmología" <?php echo ($vet['especialidad'] ?? '') === 'Oftalmología' ? 'selected' : ''; ?>>Oftalmología</option>
                                <option value="Oncología" <?php echo ($vet['especialidad'] ?? '') === 'Oncología' ? 'selected' : ''; ?>>Oncología</option>
                                <option value="General" <?php echo ($vet['especialidad'] ?? '') === 'General' ? 'selected' : ''; ?>>General</option>
                                <option value="Otra" <?php echo ($vet['especialidad'] ?? '') === 'Otra' ? 'selected' : ''; ?>>Otra</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Guardar Cambios
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
