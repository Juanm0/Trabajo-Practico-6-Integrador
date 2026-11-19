<?php

require_once dirname(__DIR__, 2) . '/includes/config.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';
require_once dirname(__DIR__, 2) . '/includes/db.php';
requireLogin();
requireRole(['Admin', 'Usuario común']);

$pageTitle = 'Editar Mascota';
$id = $_GET['id'] ?? 0;
$mascota = getMascotaById($id);

if (!$mascota) {
    $_SESSION['flash_message'] = 'Mascota no encontrada';
    $_SESSION['flash_type'] = 'danger';
    header('Location: ' . APP_URL . '/pages/mascota/listar.php');
    exit;
}


$db = getDB();
$stmt = $db->query("SELECT id_cliente, dni, nombre, apellido FROM clientes ORDER BY apellido, nombre");
$clientes = $stmt->fetchAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $especie = trim($_POST['especie'] ?? '');
    $raza = trim($_POST['raza'] ?? '');
    $fechaNacimiento = $_POST['fecha_nacimiento'] ?? '';
    $sexo = $_POST['sexo'] ?? '';
    $idCliente = $_POST['id_cliente'] ?? '';
    
    if (empty($nombre) || empty($especie) || empty($idCliente)) {
        $error = 'Nombre, especie y cliente son obligatorios';
    } else {
        if (updateMascota($id, $nombre, $especie, $raza, $fechaNacimiento ?: null, $sexo, $idCliente)) {
            $_SESSION['flash_message'] = 'Mascota actualizada exitosamente';
            $_SESSION['flash_type'] = 'success';
            header('Location: ' . APP_URL . '/pages/mascota/listar.php');
            exit;
        } else {
            $error = 'Error al actualizar la mascota';
        }
    }
}
require_once dirname(__DIR__, 2) . '/includes/header.php';

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-pencil me-2"></i><?php echo $pageTitle; ?></h1>
    <a href="<?php echo APP_URL; ?>/pages/mascota/listar.php" class="btn btn-outline-secondary">
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
                            <label for="nombre" class="form-label"><i class="bi bi-heart me-1"></i>Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required maxlength="50" value="<?php echo htmlspecialchars($mascota['nombre']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="especie" class="form-label"><i class="bi bi-tag me-1"></i>Especie <span class="text-danger">*</span></label>
                            <select class="form-select" id="especie" name="especie" required>
                                <option value="">Seleccionar...</option>
                                <option value="Perro" <?php echo $mascota['especie'] === 'Perro' ? 'selected' : ''; ?>>Perro</option>
                                <option value="Gato" <?php echo $mascota['especie'] === 'Gato' ? 'selected' : ''; ?>>Gato</option>
                                <option value="Ave" <?php echo $mascota['especie'] === 'Ave' ? 'selected' : ''; ?>>Ave</option>
                                <option value="Roedor" <?php echo $mascota['especie'] === 'Roedor' ? 'selected' : ''; ?>>Roedor</option>
                                <option value="Reptil" <?php echo $mascota['especie'] === 'Reptil' ? 'selected' : ''; ?>>Reptil</option>
                                <option value="Otro" <?php echo $mascota['especie'] === 'Otro' ? 'selected' : ''; ?>>Otro</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="raza" class="form-label"><i class="bi bi-tag me-1"></i>Raza</label>
                            <input type="text" class="form-control" id="raza" name="raza" maxlength="50" value="<?php echo htmlspecialchars($mascota['raza'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fecha_nacimiento" class="form-label"><i class="bi bi-calendar me-1"></i>Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $mascota['fecha_nacimiento'] ? date('Y-m-d', strtotime($mascota['fecha_nacimiento'])) : ''; ?>" max="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sexo" class="form-label"><i class="bi bi-gender-ambiguous me-1"></i>Sexo</label>
                            <select class="form-select" id="sexo" name="sexo">
                                <option value="">Seleccionar...</option>
                                <option value="Macho" <?php echo ($mascota['sexo'] ?? '') === 'Macho' ? 'selected' : ''; ?>>Macho</option>
                                <option value="Hembra" <?php echo ($mascota['sexo'] ?? '') === 'Hembra' ? 'selected' : ''; ?>>Hembra</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="id_cliente" class="form-label"><i class="bi bi-person me-1"></i>Cliente <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_cliente" name="id_cliente" required>
                                <option value="">Seleccionar cliente...</option>
                                <?php foreach ($clientes as $cliente): ?>
                                    <option value="<?php echo $cliente['id_cliente']; ?>" <?php echo $mascota['id_cliente'] == $cliente['id_cliente'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido'] . ' (' . $cliente['dni'] . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Guardar Cambios
                        </button>
                        <a href="<?php echo APP_URL; ?>/pages/mascota/listar.php" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__, 2) . '/includes/footer.php'; ?>
