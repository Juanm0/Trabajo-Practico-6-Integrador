<?php

require_once dirname(__DIR__, 2) . '/includes/config.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';

requireLogin();
requireRole(['Admin']);

$id = $_GET['id'] ?? 0;

if ($id) {
    
    $db = getDB();
    $stmt = $db->prepare("SELECT COUNT(*) FROM consultas WHERE id_mascota = ?");
    $stmt->execute([$id]);
    $count = $stmt->fetchColumn();
    
    if ($count > 0) {
        $_SESSION['flash_message'] = 'No se puede eliminar la mascota porque tiene consultas asociadas';
        $_SESSION['flash_type'] = 'danger';
    } else {
        if (deleteMascota($id)) {
            $_SESSION['flash_message'] = 'Mascota eliminada exitosamente';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_message'] = 'Error al eliminar la mascota';
            $_SESSION['flash_type'] = 'danger';
        }
    }
}

header('Location: ' . APP_URL . '/pages/mascota/listar.php');
exit;
