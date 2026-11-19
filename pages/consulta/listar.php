<?php
/**
 * Listar Consultas - PetCare
 */
require_once dirname(__DIR__, 2) . '/includes/config.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';
require_once dirname(__DIR__, 2) . '/includes/header.php';

requireLogin();
requireRole(['Admin', 'Veterinario', 'Usuario común']);

$pageTitle = 'Consultas';
$search = $_GET['search'] ?? '';
$consultas = getAllConsultas($search);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-journal-medical me-2"></i><?php echo $pageTitle; ?></h1>
    <a href="<?php echo APP_URL; ?>/pages/consulta/crear.php" class="btn btn-warning">
        <i class="bi bi-plus-circle me-2"></i>Nueva Consulta
    </a>
</div>

<!-- Búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-9">
                <input type="text" class="form-control" name="search" placeholder="Buscar por motivo, diagnóstico, mascota o cliente..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-search me-1"></i>Buscar</button>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de consultas -->
<div class="card">
    <div class="card-body p-0">
        <?php if (empty($consultas)): ?>
            <div class="text-center py-5">
                <i class="bi bi-journal-medical fs-1 text-muted"></i>
                <p class="text-muted mt-2">No hay consultas registradas</p>
                <a href="<?php echo APP_URL; ?>/pages/consulta/crear.php" class="btn btn-warning">Crear primera consulta</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Mascota</th>
                            <th>Cliente</th>
                            <th>Veterinario</th>
                            <th>Especialidad</th>
                            <th>Motivo</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($consultas as $consulta): ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($consulta['fecha_consulta'])); ?></td>
                                <td><?php echo htmlspecialchars($consulta['mascota_nombre']); ?> <small class="text-muted">(<?php echo htmlspecialchars($consulta['especie']); ?>)</small></td>
                                <td><?php echo htmlspecialchars($consulta['cliente_nombre'] . ' ' . $consulta['cliente_apellido']); ?></td>
                                <td>Dr. <?php echo htmlspecialchars($consulta['vet_nombre'] . ' ' . $consulta['vet_apellido']); ?></td>
                                <td><?php echo htmlspecialchars($consulta['especialidad']); ?></td>
                                <td><?php echo htmlspecialchars($consulta['motivo']); ?></td>
                                <td>$<?php echo number_format($consulta['precio'], 2, ',', '.'); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo APP_URL; ?>/pages/consulta/ver.php?id=<?php echo $consulta['id_consulta']; ?>" class="btn btn-outline-primary" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <?php if (in_array($_SESSION['user_rol_nombre'] ?? '', ['Admin', 'Veterinario'])): ?>
                                        <a href="<?php echo APP_URL; ?>/pages/consulta/editar.php?id=<?php echo $consulta['id_consulta']; ?>" class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?php echo APP_URL; ?>/pages/consulta/eliminar.php?id=<?php echo $consulta['id_consulta']; ?>" class="btn btn-outline-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar esta consulta?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once dirname(__DIR__, 2) . '/includes/footer.php'; ?>
