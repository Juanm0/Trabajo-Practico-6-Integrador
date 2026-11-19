<?php

require_once dirname(__DIR__, 2) . '/includes/config.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';
require_once dirname(__DIR__, 2) . '/includes/header.php';

requireLogin();
requireRole(['Admin', 'Usuario común']);

$pageTitle = 'Mascotas';
$search = $_GET['search'] ?? '';
$mascotas = getAllMascotas($search);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-heart me-2"></i><?php echo $pageTitle; ?></h1>
    <a href="<?php echo APP_URL; ?>/pages/mascota/crear.php" class="btn btn-success">
        <i class="bi bi-plus-circle me-2"></i>Nueva Mascota
    </a>
</div>

<!-- Búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-9">
                <input type="text" class="form-control" name="search" placeholder="Buscar por nombre, especie, raza o cliente..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-search me-1"></i>Buscar</button>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de mascotas -->
<div class="card">
    <div class="card-body p-0">
        <?php if (empty($mascotas)): ?>
            <div class="text-center py-5">
                <i class="bi bi-heart fs-1 text-muted"></i>
                <p class="text-muted mt-2">No hay mascotas registradas</p>
                <a href="<?php echo APP_URL; ?>/pages/mascota/crear.php" class="btn btn-success">Crear primera mascota</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre</th>
                            <th>Especie</th>
                            <th>Raza</th>
                            <th>Fecha Nac.</th>
                            <th>Sexo</th>
                            <th>Cliente</th>
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
                                <td><?php echo htmlspecialchars($mascota['cliente_nombre'] . ' ' . $mascota['cliente_apellido']); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo APP_URL; ?>/pages/mascota/ver.php?id=<?php echo $mascota['id_mascota']; ?>" class="btn btn-outline-primary" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?php echo APP_URL; ?>/pages/mascota/editar.php?id=<?php echo $mascota['id_mascota']; ?>" class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?php echo APP_URL; ?>/pages/mascota/eliminar.php?id=<?php echo $mascota['id_mascota']; ?>" class="btn btn-outline-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar esta mascota?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
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
