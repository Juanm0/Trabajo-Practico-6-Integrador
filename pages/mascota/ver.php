<?php
/**
 * Ver Mascota - PetCare
 */
require_once dirname(__DIR__, 2) . '/includes/config.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';
requireLogin();
requireRole(['Admin', 'Usuario común', 'Veterinario']);

$pageTitle = 'Ver Mascota';
$id = $_GET['id'] ?? 0;
$mascota = getMascotaById($id);

if (!$mascota) {
    $_SESSION['flash_message'] = 'Mascota no encontrada';
    $_SESSION['flash_type'] = 'danger';
    header('Location: ' . APP_URL . '/pages/mascota/listar.php');
    exit;
}

$consultas = getConsultasByMascota($id);
require_once dirname(__DIR__, 2) . '/includes/header.php';

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-eye me-2"></i><?php echo $pageTitle; ?></h1>
    <div>
        <a href="<?php echo APP_URL; ?>/pages/mascota/editar.php?id=<?php echo $id; ?>" class="btn btn-outline-warning me-2">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <a href="<?php echo APP_URL; ?>/pages/mascota/listar.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-heart fs-1 text-danger"></i>
                <h4 class="mt-3"><?php echo htmlspecialchars($mascota['nombre']); ?></h4>
                <p class="text-muted"><?php echo htmlspecialchars($mascota['especie']); ?> - <?php echo htmlspecialchars($mascota['raza'] ?? 'Sin raza'); ?></p>
                <span class="badge bg-primary"><?php echo htmlspecialchars($mascota['sexo'] ?? 'N/A'); ?></span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Fecha Nacimiento:</strong> <?php echo $mascota['fecha_nacimiento'] ? date('d/m/Y', strtotime($mascota['fecha_nacimiento'])) : 'No especificada'; ?></li>
                <li class="list-group-item"><strong>Cliente:</strong> <?php echo htmlspecialchars($mascota['cliente_nombre'] . ' ' . $mascota['cliente_apellido']); ?></li>
                <li class="list-group-item"><strong>Total Consultas:</strong> <?php echo count($consultas); ?></li>
            </ul>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-journal-medical me-2"></i>Historial de Consultas</h5>
                <a href="<?php echo APP_URL; ?>/pages/consulta/crear.php?mascota=<?php echo $id; ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Nueva Consulta
                </a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($consultas)): ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-journal-medical fs-1"></i>
                        <p class="mt-2">No hay consultas registradas para esta mascota</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Veterinario</th>
                                    <th>Especialidad</th>
                                    <th>Motivo</th>
                                    <th>Diagnóstico</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($consultas as $consulta): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($consulta['fecha_consulta'])); ?></td>
                                    <td><?php echo htmlspecialchars($consulta['vet_nombre'] . ' ' . $consulta['vet_apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($consulta['especialidad']); ?></td>
                                    <td><?php echo htmlspecialchars($consulta['motivo']); ?></td>
                                    <td><?php echo htmlspecialchars($consulta['diagnostico'] ?? '-'); ?></td>
                                    <td>$<?php echo number_format($consulta['precio'], 2, ',', '.'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__, 2) . '/includes/footer.php'; ?>
