<?php

require_once dirname(__DIR__, 2) . '/includes/config.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';

requireLogin();
requireRole(['Admin']);

$id = $_GET['id'] ?? 0;

if ($id) {
    
    $db = getDB();
    $stmt = $db->prepare("SELECT COUNT(*) FROM mascotas WHERE id_cliente = ?");
    $stmt->execute([$id]);
    $count = $stmt->fetchColumn();
    
    if ($count > 0) {
        $_SESSION['flash_message'] = 'No se puede eliminar el cliente porque tiene mascotas asociadas';
        $_SESSION['flash_type'] = 'danger';
    } else {
        if (deleteCliente($id)) {
            $_SESSION['flash_message'] = 'Cliente eliminado exitosamente';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_message'] = 'Error al eliminar el cliente';
            $_SESSION['flash_type'] = 'danger';
        }
    }
}

header('Location: ' . APP_URL . '/pages/cliente/listar.php');
exit;
