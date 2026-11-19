<?php
/**
 * Administración de Usuarios - PetCare
 */
require_once dirname(__DIR__, 2) . '/includes/config.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';
require_once dirname(__DIR__, 2) . '/includes/db.php';
requireLogin();
requireRole(['Admin']);

$pageTitle = 'Administración de Usuarios';

// Manejar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $telefono = trim($_POST['telefono'] ?? '');
        $idRol = $_POST['id_rol'] ?? 3;
        
        if (empty($nombre) || empty($apellido) || empty($email) || empty($password)) {
            $_SESSION['flash_message'] = 'Todos los campos obligatorios deben completarse';
            $_SESSION['flash_type'] = 'danger';
        } else {
            if (createUsuario($nombre, $apellido, $email, $password, $telefono, $idRol)) {
                $_SESSION['flash_message'] = 'Usuario creado exitosamente';
                $_SESSION['flash_type'] = 'success';
            } else {
                $_SESSION['flash_message'] = 'Error al crear el usuario. El email podría ya existir.';
                $_SESSION['flash_type'] = 'danger';
            }
        }
        header('Location: ' . APP_URL . '/pages/admin/usuarios.php');
        exit;
    }
    
    if ($action === 'update') {
        $id = $_POST['id'] ?? 0;
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $idRol = $_POST['id_rol'] ?? 3;
        $activo = isset($_POST['activo']) ? 1 : 0;
        
        if (empty($nombre) || empty($apellido) || empty($email)) {
            $_SESSION['flash_message'] = 'Nombre, apellido y email son obligatorios';
            $_SESSION['flash_type'] = 'danger';
        } else {
            if (updateUsuario($id, $nombre, $apellido, $email, $telefono, $idRol, $activo)) {
                $_SESSION['flash_message'] = 'Usuario actualizado exitosamente';
                $_SESSION['flash_type'] = 'success';
            } else {
                $_SESSION['flash_message'] = 'Error al actualizar el usuario';
                $_SESSION['flash_type'] = 'danger';
            }
        }
        header('Location: ' . APP_URL . '/pages/admin/usuarios.php');
        exit;
    }
    
    if ($action === 'delete') {
        $id = $_POST['id'] ?? 0;
        if ($id != $_SESSION['user_id']) {
            if (deleteUsuario($id)) {
                $_SESSION['flash_message'] = 'Usuario eliminado exitosamente';
                $_SESSION['flash_type'] = 'success';
            } else {
                $_SESSION['flash_message'] = 'Error al eliminar el usuario';
                $_SESSION['flash_type'] = 'danger';
            }
        } else {
            $_SESSION['flash_message'] = 'No puedes eliminarte a ti mismo';
            $_SESSION['flash_type'] = 'danger';
        }
        header('Location: ' . APP_URL . '/pages/admin/usuarios.php');
        exit;
    }
}

$usuarios = getAllUsuarios();
$roles = getRoles();
require_once dirname(__DIR__, 2) . '/includes/header.php';

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-people me-2"></i><?php echo $pageTitle; ?></h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearUsuario">
        <i class="bi bi-plus-circle me-2"></i>Nuevo Usuario
    </button>
</div>

<!-- Tabla de usuarios -->
<div class="card">
    <div class="card-body p-0">
        <?php if (empty($usuarios)): ?>
            <div class="text-center py-5">
                <i class="bi bi-people fs-1 text-muted"></i>
                <p class="text-muted mt-2">No hay usuarios registrados</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo $usuario['id_usuario']; ?></td>
                                <td><?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $usuario['rol_nombre'] === 'Admin' ? 'danger' : ($usuario['rol_nombre'] === 'Veterinario' ? 'info' : 'secondary'); ?>">
                                        <?php echo htmlspecialchars($usuario['rol_nombre']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($usuario['telefono'] ?? '-'); ?></td>
                                <td>
                                    <?php if ($usuario['activo']): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario" 
                                            data-id="<?php echo $usuario['id_usuario']; ?>"
                                            data-nombre="<?php echo htmlspecialchars($usuario['nombre']); ?>"
                                            data-apellido="<?php echo htmlspecialchars($usuario['apellido']); ?>"
                                            data-email="<?php echo htmlspecialchars($usuario['email']); ?>"
                                            data-telefono="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>"
                                            data-id_rol="<?php echo $usuario['id_rol']; ?>"
                                            data-activo="<?php echo $usuario['activo']; ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <?php if ($usuario['id_usuario'] != $_SESSION['user_id']): ?>
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarUsuario" data-id="<?php echo $usuario['id_usuario']; ?>" data-nombre="<?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
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

<!-- Modal Crear Usuario -->
<div class="modal fade" id="modalCrearUsuario" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nombre" required maxlength="50">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="apellido" required maxlength="50">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password" required minlength="6">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" name="telefono" maxlength="20">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Rol <span class="text-danger">*</span></label>
                            <select class="form-select" name="id_rol" required>
                                <?php foreach ($roles as $rol): ?>
                                    <option value="<?php echo $rol['id_rol']; ?>"><?php echo htmlspecialchars($rol['nombre']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Usuario -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Editar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nombre" id="edit_nombre" required maxlength="50">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="apellido" id="edit_apellido" required maxlength="50">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" id="edit_email" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" name="telefono" id="edit_telefono" maxlength="20">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Rol <span class="text-danger">*</span></label>
                            <select class="form-select" name="id_rol" id="edit_id_rol" required>
                                <?php foreach ($roles as $rol): ?>
                                    <option value="<?php echo $rol['id_rol']; ?>"><?php echo htmlspecialchars($rol['nombre']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 form-check form-switch">
                        <input type="checkbox" class="form-check-input" name="activo" id="edit_activo" value="1">
                        <label class="form-check-label" for="edit_activo">Activo</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar Usuario -->
<div class="modal fade" id="modalEliminarUsuario" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="delete_id">
                <div class="modal-header">
                    <h5 class="modal-title text-danger"><i class="bi bi-trash me-2"></i>Eliminar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de eliminar al usuario <strong id="delete_nombre"></strong>?</p>
                    <p class="text-muted small">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__, 2) . '/includes/footer.php'; ?>

<script>
// Llenar modales de edición y eliminación
document.addEventListener('DOMContentLoaded', function() {
    // Modal editar
    const modalEditar = document.getElementById('modalEditarUsuario');
    if (modalEditar) {
        modalEditar.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            document.getElementById('edit_id').value = button.dataset.id;
            document.getElementById('edit_nombre').value = button.dataset.nombre;
            document.getElementById('edit_apellido').value = button.dataset.apellido;
            document.getElementById('edit_email').value = button.dataset.email;
            document.getElementById('edit_telefono').value = button.dataset.telefono;
            document.getElementById('edit_id_rol').value = button.dataset.id_rol;
            document.getElementById('edit_activo').checked = button.dataset.activo == '1';
        });
    }
    
    // Modal eliminar
    const modalEliminar = document.getElementById('modalEliminarUsuario');
    if (modalEliminar) {
        modalEliminar.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            document.getElementById('delete_id').value = button.dataset.id;
            document.getElementById('delete_nombre').textContent = button.dataset.nombre;
        });
    }
});
</script>
