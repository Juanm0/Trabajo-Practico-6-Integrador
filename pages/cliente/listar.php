<?php

require_once dirname(__DIR__, 2) . '/includes/config.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';
require_once dirname(__DIR__, 2) . '/includes/header.php';

requireLogin();
requireRole(['Admin', 'Usuario común']);

$pageTitle = 'Clientes';
$search = $_GET['search'] ?? '';
$clientes = getAllClientes($search);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-people me-2"></i><?php echo $pageTitle; ?></h1>
    <a href="<?php echo APP_URL; ?>/pages/cliente/crear.php" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Nuevo Cliente
    </a>
</div>

<!-- Búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-9">
                <input type="text" class="form-control" name="search" placeholder="Buscar por nombre, apellido o DNI..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-search me-1"></i>Buscar</button>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de clientes -->
<div class="card">
    <div class="card-body p-0">
        <?php if (empty($clientes)): ?>
            <div class="text-center py-5">
                <i class="bi bi-people fs-1 text-muted"></i>
                <p class="text-muted mt-2">No hay clientes registrados</p>
                <a href="<?php echo APP_URL; ?>/pages/cliente/crear.php" class="btn btn-primary">Crear primer cliente</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>DNI</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Teléfono</th>
                            <th>Mascotas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                            <?php
                                $db = getDB();
                                $stmt = $db->prepare("SELECT COUNT(*) FROM mascotas WHERE id_cliente = ?");
                                $stmt->execute([$cliente['id_cliente']]);
                                $mascotasCount = $stmt->fetchColumn();
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($cliente['dni']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['apellido']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['telefono'] ?? '-'); ?></td>
                                <td>
                                    <span class="badge bg-info"><?php echo $mascotasCount; ?> mascota(s)</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo APP_URL; ?>/pages/cliente/ver.php?id=<?php echo $cliente['id_cliente']; ?>" class="btn btn-outline-primary" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?php echo APP_URL; ?>/pages/cliente/editar.php?id=<?php echo $cliente['id_cliente']; ?>" class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?php echo APP_URL; ?>/pages/cliente/eliminar.php?id=<?php echo $cliente['id_cliente']; ?>" class="btn btn-outline-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este cliente?')">
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
