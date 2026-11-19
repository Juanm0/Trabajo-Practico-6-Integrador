<?php

require_once dirname(__DIR__, 2) . '/includes/config.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';
requireLogin();
requireRole(['Admin', 'Usuario común']);

$pageTitle = 'Ver Cliente';
$id = $_GET['id'] ?? 0;
$cliente = getClienteById($id);

if (!$cliente) {
    $_SESSION['flash_message'] = 'Cliente no encontrado';
    $_SESSION['flash_type'] = 'danger';
    header('Location: ' . APP_URL . '/pages/cliente/listar.php');
    exit;
}

$mascotas = getMascotasByCliente($id);
require_once dirname(__DIR__, 2) . '/includes/header.php';

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-eye me-2"></i><?php echo $pageTitle; ?></h1>
    <div>
        <a href="<?php echo APP_URL; ?>/pages/cliente/editar.php?id=<?php echo $id; ?>" class="btn btn-outline-warning me-2">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <a href="<?php echo APP_URL; ?>/pages/cliente/listar.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-person-circle fs-1 text-primary"></i>
                <h4 class="mt-3"><?php echo htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']); ?></h4>
                <span class="badge bg-primary"><?php echo htmlspecialchars($cliente['dni']); ?></span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Teléfono:</strong> <?php echo htmlspecialchars($cliente['telefono'] ?? 'No especificado'); ?></li>
                <li class="list-group-item"><strong>Mascotas:</strong> <?php echo count($mascotas); ?></li>
            </ul>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-heart me-2"></i>Mascotas de este Cliente</h5>
                <a href="<?php echo APP_URL; ?>/pages/mascota/crear.php?cliente=<?php echo $id; ?>" class="btn btn-sm btn-success">
                    <i class="bi bi-plus-circle me-1"></i>Nueva Mascota
                </a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($mascotas)): ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-heart fs-1"></i>
                        <p class="mt-2">Este cliente no tiene mascotas registradas</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Especie</th>
                                    <th>Raza</th>
                                    <th>Fecha Nacimiento</th>
                                    <th>Sexo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mascotas as $mascota): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($mascota['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($mascota['especie']); ?></td>
                                    <td><?php echo htmlspecialchars($mascota['raza'] ?? '-'); ?></td>
                                    <td><?php echo $mascota['fecha_nacimiento'] ? date('d/m/Y', strtotime($mascota['fecha_nacimiento'])) : '-'; ?></td>
                                    <td><?php echo htmlspecialchars($mascota['sexo'] ?? '-'); ?></td>
                                    <td>
                                        <a href="<?php echo APP_URL; ?>/pages/mascota/ver.php?id=<?php echo $mascota['id_mascota']; ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
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
