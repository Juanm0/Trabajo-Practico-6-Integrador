<?php

require_once dirname(__DIR__, 2) . '/includes/config.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';
require_once dirname(__DIR__, 2) . '/includes/db.php';
requireLogin();
requireRole(['Admin', 'Veterinario']);

$pageTitle = 'Ver Veterinario';
$id = $_GET['id'] ?? 0;
$vet = getVeterinarioById($id);

if (!$vet) {
    $_SESSION['flash_message'] = 'Veterinario no encontrado';
    $_SESSION['flash_type'] = 'danger';
    header('Location: ' . APP_URL . '/pages/veterinario/listar.php');
    exit;
}


$db = getDB();
$stmt = $db->prepare("SELECT c.*, m.nombre as mascota_nombre, cli.nombre as cliente_nombre, cli.apellido as cliente_apellido
                      FROM consultas c
                      JOIN mascotas m ON c.id_mascota = m.id_mascota
                      JOIN clientes cli ON m.id_cliente = cli.id_cliente
                      WHERE c.id_veterinario = ?
                      ORDER BY c.fecha_consulta DESC");
$stmt->execute([$id]);
$consultas = $stmt->fetchAll();
require_once dirname(__DIR__, 2) . '/includes/header.php';

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-eye me-2"></i><?php echo $pageTitle; ?></h1>
    <div>
        <a href="<?php echo APP_URL; ?>/pages/veterinario/editar.php?id=<?php echo $id; ?>" class="btn btn-outline-warning me-2">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <a href="<?php echo APP_URL; ?>/pages/veterinario/listar.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-person-badge fs-1 text-info"></i>
                <h4 class="mt-3">Dr. <?php echo htmlspecialchars($vet['nombre'] . ' ' . $vet['apellido']); ?></h4>
                <span class="badge bg-info"><?php echo htmlspecialchars($vet['numero_registro']); ?></span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Especialidad:</strong> <?php echo htmlspecialchars($vet['especialidad'] ?? 'General'); ?></li>
                <li class="list-group-item"><strong>DNI:</strong> <?php echo htmlspecialchars($vet['dni']); ?></li>
                <li class="list-group-item"><strong>Teléfono:</strong> <?php echo htmlspecialchars($vet['telefono'] ?? 'No especificado'); ?></li>
                <li class="list-group-item"><strong>Total Consultas:</strong> <?php echo count($consultas); ?></li>
            </ul>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-journal-medical me-2"></i>Consultas Realizadas</h5>
            </div>
            <div class="card-body p-0">
                <?php if (empty($consultas)): ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-journal-medical fs-1"></i>
                        <p class="mt-2">Este veterinario no tiene consultas registradas</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Mascota</th>
                                    <th>Cliente</th>
                                    <th>Motivo</th>
                                    <th>Diagnóstico</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($consultas as $consulta): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($consulta['fecha_consulta'])); ?></td>
                                    <td><?php echo htmlspecialchars($consulta['mascota_nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($consulta['cliente_nombre'] . ' ' . $consulta['cliente_apellido']); ?></td>
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
