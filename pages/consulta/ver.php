<?php
/**
 * Ver Consulta - PetCare
 */
require_once dirname(__DIR__, 2) . '/includes/config.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';
requireLogin();
requireRole(['Admin', 'Veterinario', 'Usuario común']);

$pageTitle = 'Ver Consulta';
$id = $_GET['id'] ?? 0;
$consulta = getConsultaById($id);

if (!$consulta) {
    $_SESSION['flash_message'] = 'Consulta no encontrada';
    $_SESSION['flash_type'] = 'danger';
    header('Location: ' . APP_URL . '/pages/consulta/listar.php');
    exit;
}
require_once dirname(__DIR__, 2) . '/includes/header.php';

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-eye me-2"></i><?php echo $pageTitle; ?></h1>
    <div>
        <?php if (in_array($_SESSION['user_rol_nombre'] ?? '', ['Admin', 'Veterinario'])): ?>
        <a href="<?php echo APP_URL; ?>/pages/consulta/editar.php?id=<?php echo $id; ?>" class="btn btn-outline-warning me-2">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <?php endif; ?>
        <a href="<?php echo APP_URL; ?>/pages/consulta/listar.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Información de la Consulta</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label text-muted">Fecha y Hora</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext"><?php echo date('d/m/Y H:i', strtotime($consulta['fecha_consulta'])); ?></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label text-muted">Mascota</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">
                            <a href="<?php echo APP_URL; ?>/pages/mascota/ver.php?id=<?php echo $consulta['id_mascota']; ?>">
                                <?php echo htmlspecialchars($consulta['mascota_nombre']); ?>
                            </a>
                            <small class="text-muted">(<?php echo htmlspecialchars($consulta['especie']); ?> - <?php echo htmlspecialchars($consulta['raza'] ?? 'Sin raza'); ?>)</small>
                        </p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label text-muted">Cliente</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">
                            <a href="<?php echo APP_URL; ?>/pages/cliente/ver.php?id=<?php echo $consulta['id_cliente'] ?? ''; ?>">
                                <?php echo htmlspecialchars($consulta['cliente_nombre'] . ' ' . $consulta['cliente_apellido']); ?>
                            </a>
                        </p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label text-muted">Veterinario</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">
                            <a href="<?php echo APP_URL; ?>/pages/veterinario/ver.php?id=<?php echo $consulta['id_veterinario']; ?>">
                                Dr. <?php echo htmlspecialchars($consulta['vet_nombre'] . ' ' . $consulta['vet_apellido']); ?>
                            </a>
                            <small class="text-muted">(<?php echo htmlspecialchars($consulta['especialidad']); ?>)</small>
                        </p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label text-muted">Motivo</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext"><?php echo htmlspecialchars($consulta['motivo']); ?></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label text-muted">Diagnóstico</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext"><?php echo htmlspecialchars($consulta['diagnostico'] ?? 'No especificado'); ?></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label text-muted">Precio</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext fw-bold text-success fs-5">$<?php echo number_format($consulta['precio'], 2, ',', '.'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-heart me-2"></i>Mascota</h5>
            </div>
            <div class="card-body text-center">
                <i class="bi bi-heart fs-1 text-danger"></i>
                <h4 class="mt-3"><?php echo htmlspecialchars($consulta['mascota_nombre']); ?></h4>
                <p class="text-muted"><?php echo htmlspecialchars($consulta['especie']); ?> - <?php echo htmlspecialchars($consulta['raza'] ?? 'Sin raza'); ?></p>
                <p><small>Sexo: <?php echo htmlspecialchars($consulta['sexo'] ?? 'N/A'); ?></small></p>
                <a href="<?php echo APP_URL; ?>/pages/mascota/ver.php?id=<?php echo $consulta['id_mascota']; ?>" class="btn btn-outline-primary btn-sm mt-2">
                    <i class="bi bi-eye me-1"></i>Ver Historial Completo
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__, 2) . '/includes/footer.php'; ?>
