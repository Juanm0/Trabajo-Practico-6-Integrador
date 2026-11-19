<?php

require_once dirname(__DIR__, 2) . '/includes/config.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';
require_once dirname(__DIR__, 2) . '/includes/db.php';
requireLogin();
requireRole(['Admin', 'Veterinario']);

$pageTitle = 'Editar Consulta';
$id = $_GET['id'] ?? 0;
$consulta = getConsultaById($id);

if (!$consulta) {
    $_SESSION['flash_message'] = 'Consulta no encontrada';
    $_SESSION['flash_type'] = 'danger';
    header('Location: ' . APP_URL . '/pages/consulta/listar.php');
    exit;
}


$db = getDB();
$stmt = $db->query("SELECT m.id_mascota, m.nombre, m.especie, c.nombre as cliente_nombre, c.apellido as cliente_apellido 
                    FROM mascotas m JOIN clientes c ON m.id_cliente = c.id_cliente 
                    ORDER BY c.apellido, c.nombre, m.nombre");
$mascotas = $stmt->fetchAll();


$stmt = $db->query("SELECT id_veterinario, nombre, apellido, especialidad, numero_registro FROM veterinarios ORDER BY apellido, nombre");
$veterinarios = $stmt->fetchAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fechaConsulta = $_POST['fecha_consulta'] ?? '';
    $motivo = trim($_POST['motivo'] ?? '');
    $diagnostico = trim($_POST['diagnostico'] ?? '');
    $precio = $_POST['precio'] ?? '';
    $idMascota = $_POST['id_mascota'] ?? '';
    $idVeterinario = $_POST['id_veterinario'] ?? '';
    
    if (empty($fechaConsulta) || empty($motivo) || empty($precio) || empty($idMascota) || empty($idVeterinario)) {
        $error = 'Todos los campos obligatorios deben completarse';
    } elseif (!is_numeric($precio) || $precio < 0) {
        $error = 'El precio debe ser un número válido';
    } else {
        if (updateConsulta($id, $fechaConsulta, $motivo, $diagnostico, $precio, $idMascota, $idVeterinario)) {
            $_SESSION['flash_message'] = 'Consulta actualizada exitosamente';
            $_SESSION['flash_type'] = 'success';
            header('Location: ' . APP_URL . '/pages/consulta/listar.php');
            exit;
        } else {
            $error = 'Error al actualizar la consulta';
        }
    }
}
require_once dirname(__DIR__, 2) . '/includes/header.php';

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-pencil me-2"></i><?php echo $pageTitle; ?></h1>
    <a href="<?php echo APP_URL; ?>/pages/consulta/listar.php" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
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
                            <label for="fecha_consulta" class="form-label"><i class="bi bi-calendar me-1"></i>Fecha y Hora <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="fecha_consulta" name="fecha_consulta" required value="<?php echo date('Y-m-d\TH:i', strtotime($consulta['fecha_consulta'])); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="id_mascota" class="form-label"><i class="bi bi-heart me-1"></i>Mascota <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_mascota" name="id_mascota" required>
                                <option value="">Seleccionar mascota...</option>
                                <?php foreach ($mascotas as $mascota): ?>
                                    <option value="<?php echo $mascota['id_mascota']; ?>" <?php echo $consulta['id_mascota'] == $mascota['id_mascota'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($mascota['nombre'] . ' (' . $mascota['especie'] . ') - ' . $mascota['cliente_nombre'] . ' ' . $mascota['cliente_apellido']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="id_veterinario" class="form-label"><i class="bi bi-person-badge me-1"></i>Veterinario <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_veterinario" name="id_veterinario" required>
                            <option value="">Seleccionar veterinario...</option>
                            <?php foreach ($veterinarios as $vet): ?>
                                <option value="<?php echo $vet['id_veterinario']; ?>" <?php echo $consulta['id_veterinario'] == $vet['id_veterinario'] ? 'selected' : ''; ?>>
                                    Dr. <?php echo htmlspecialchars($vet['nombre'] . ' ' . $vet['apellido'] . ' - ' . $vet['especialidad'] . ' (' . $vet['numero_registro'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="motivo" class="form-label"><i class="bi bi-chat-left-text me-1"></i>Motivo de la Consulta <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="motivo" name="motivo" rows="3" required maxlength="100"><?php echo htmlspecialchars($consulta['motivo']); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="diagnostico" class="form-label"><i class="bi bi-file-medical me-1"></i>Diagnóstico</label>
                        <textarea class="form-control" id="diagnostico" name="diagnostico" rows="3" maxlength="500"><?php echo htmlspecialchars($consulta['diagnostico'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="precio" class="form-label"><i class="bi bi-currency-dollar me-1"></i>Precio <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0" required value="<?php echo $consulta['precio']; ?>">
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Guardar Cambios
                        </button>
                        <a href="<?php echo APP_URL; ?>/pages/consulta/listar.php" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__, 2) . '/includes/footer.php'; ?>
