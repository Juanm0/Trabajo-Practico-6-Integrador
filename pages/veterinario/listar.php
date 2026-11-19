<?php
/**
 * Listar Veterinarios - PetCare
 */
require_once dirname(__DIR__, 2) . '/includes/config.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';
require_once dirname(__DIR__, 2) . '/includes/header.php';

requireLogin();
requireRole(['Admin', 'Veterinario']);

$pageTitle = 'Veterinarios';
$search = $_GET['search'] ?? '';
$veterinarios = getAllVeterinarios($search);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-person-badge me-2"></i><?php echo $pageTitle; ?></h1>
    <a href="<?php echo APP_URL; ?>/pages/veterinario/crear.php" class="btn btn-info">
        <i class="bi bi-plus-circle me-2"></i>Nuevo Veterinario
    </a>
</div>

<!-- Búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-9">
                <input type="text" class="form-control" name="search" placeholder="Buscar por nombre, apellido, especialidad o registro..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-search me-1"></i>Buscar</button>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de veterinarios -->
<div class="card">
    <div class="card-body p-0">
        <?php if (empty($veterinarios)): ?>
            <div class="text-center py-5">
                <i class="bi bi-person-badge fs-1 text-muted"></i>
                <p class="text-muted mt-2">No hay veterinarios registrados</p>
                <a href="<?php echo APP_URL; ?>/pages/veterinario/crear.php" class="btn btn-info">Crear primer veterinario</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>N° Registro</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Especialidad</th>
                            <th>Teléfono</th>
                            <th>DNI</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($veterinarios as $vet): ?>
                            <tr>
                                <td><span class="badge bg-info"><?php echo htmlspecialchars($vet['numero_registro']); ?></span></td>
                                <td><?php echo htmlspecialchars($vet['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($vet['apellido']); ?></td>
                                <td><?php echo htmlspecialchars($vet['especialidad'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($vet['telefono'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($vet['dni']); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo APP_URL; ?>/pages/veterinario/ver.php?id=<?php echo $vet['id_veterinario']; ?>" class="btn btn-outline-primary" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?php echo APP_URL; ?>/pages/veterinario/editar.php?id=<?php echo $vet['id_veterinario']; ?>" class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?php echo APP_URL; ?>/pages/veterinario/eliminar.php?id=<?php echo $vet['id_veterinario']; ?>" class="btn btn-outline-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este veterinario?')">
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
