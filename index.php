<?php
/**
 * Dashboard principal - PetCare
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

// Requiere login antes de continuar
requireLogin();

require_once __DIR__ . '/includes/header.php';

$pageTitle = 'Dashboard';

// Estadísticas
$db = getDB();
$stats = [
    'clientes' => $db->query("SELECT COUNT(*) FROM clientes")->fetchColumn(),
    'mascotas' => $db->query("SELECT COUNT(*) FROM mascotas")->fetchColumn(),
    'veterinarios' => $db->query("SELECT COUNT(*) FROM veterinarios")->fetchColumn(),
    'consultas' => $db->query("SELECT COUNT(*) FROM consultas")->fetchColumn(),
];

// Consultas recientes
$stmt = $db->query("SELECT c.*, m.nombre as mascota_nombre, cli.nombre as cliente_nombre, cli.apellido as cliente_apellido 
                    FROM consultas c
                    JOIN mascotas m ON c.id_mascota = m.id_mascota
                    JOIN clientes cli ON m.id_cliente = cli.id_cliente
                    ORDER BY c.fecha_consulta DESC LIMIT 5");
$recentConsultas = $stmt->fetchAll();

// Obtener datos del usuario desde la sesión (ahora seguro que existe por requireLogin)
$userNombre = $_SESSION['user_nombre'] ?? 'Usuario';
$userRol = $_SESSION['user_rol_nombre'] ?? 'Usuario';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-speedometer2 me-2"></i>Dashboard</h1>
    <span class="badge bg-primary fs-6"><?php echo htmlspecialchars($userNombre); ?> (<?php echo htmlspecialchars($userRol); ?>)</span>
</div>

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Clientes</h6>
                        <h2 class="mb-0"><?php echo $stats['clientes']; ?></h2>
                    </div>
                    <i class="bi bi-people fs-1 opacity-50"></i>
                </div>
                <a href="<?php echo APP_URL; ?>/pages/cliente/listar.php" class="text-white text-decoration-none small">
                    Ver todos <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Mascotas</h6>
                        <h2 class="mb-0"><?php echo $stats['mascotas']; ?></h2>
                    </div>
                    <i class="bi bi-heart fs-1 opacity-50"></i>
                </div>
                <a href="<?php echo APP_URL; ?>/pages/mascota/listar.php" class="text-white text-decoration-none small">
                    Ver todos <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Veterinarios</h6>
                        <h2 class="mb-0"><?php echo $stats['veterinarios']; ?></h2>
                    </div>
                    <i class="bi bi-person-badge fs-1 opacity-50"></i>
                </div>
                <a href="<?php echo APP_URL; ?>/pages/veterinario/listar.php" class="text-white text-decoration-none small">
                    Ver todos <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Consultas</h6>
                        <h2 class="mb-0"><?php echo $stats['consultas']; ?></h2>
                    </div>
                    <i class="bi bi-journal-medical fs-1 opacity-50"></i>
                </div>
                <a href="<?php echo APP_URL; ?>/pages/consulta/listar.php" class="text-dark text-decoration-none small">
                    Ver todos <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Accesos rápidos -->
<div class="row mb-4">
    <div class="col-12">
        <h5><i class="bi bi-lightning me-2"></i>Accesos Rápidos</h5>
        <div class="row g-2">
            <?php if (in_array($userRol, ['Admin', 'Usuario común'])): ?>
            <div class="col-md-3">
                <a href="<?php echo APP_URL; ?>/pages/cliente/crear.php" class="btn btn-outline-primary w-100 py-3">
                    <i class="bi bi-person-plus fs-3 d-block mb-1"></i>Nuevo Cliente
                </a>
            </div>
            <div class="col-md-3">
                <a href="<?php echo APP_URL; ?>/pages/mascota/crear.php" class="btn btn-outline-success w-100 py-3">
                    <i class="bi bi-heart-plus fs-3 d-block mb-1"></i>Nueva Mascota
                </a>
            </div>
            <?php endif; ?>
            <?php if (in_array($userRol, ['Admin', 'Veterinario'])): ?>
            <div class="col-md-3">
                <a href="<?php echo APP_URL; ?>/pages/veterinario/crear.php" class="btn btn-outline-info w-100 py-3">
                <i class="bi bi-person-plus fs-3 d-block mb-1"></i>Nuevo Veterinario
                </a>
            </div>
            <?php endif; ?>
            <?php if (in_array($userRol, ['Admin', 'Veterinario', 'Usuario común'])): ?>
            <div class="col-md-3">
                <a href="<?php echo APP_URL; ?>/pages/consulta/crear.php" class="btn btn-outline-warning w-100 py-3">
                    <i class="bi bi-plus-circle fs-3 d-block mb-1"></i>Nueva Consulta
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Consultas recientes -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Últimas Consultas</h5>
                <a href="<?php echo APP_URL; ?>/pages/consulta/listar.php" class="btn btn-sm btn-outline-primary">Ver todas</a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($recentConsultas)): ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-journal-medical fs-1"></i>
                        <p class="mt-2">No hay consultas registradas</p>
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
                                    <th>Precio</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentConsultas as $consulta): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($consulta['fecha_consulta'])); ?></td>
                                    <td><?php echo htmlspecialchars($consulta['mascota_nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($consulta['cliente_nombre'] . ' ' . $consulta['cliente_apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($consulta['motivo']); ?></td>
                                    <td>$<?php echo number_format($consulta['precio'], 2, ',', '.'); ?></td>
                                    <td>
                                        <a href="<?php echo APP_URL; ?>/pages/consulta/ver.php?id=<?php echo $consulta['id_consulta']; ?>" class="btn btn-sm btn-outline-primary">
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

<?php require_once __DIR__ . '/includes/footer.php'; ?>
