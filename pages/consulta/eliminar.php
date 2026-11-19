<?php

require_once dirname(__DIR__, 2) . '/includes/config.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';

requireLogin();
requireRole(['Admin', 'Veterinario']);

$id = $_GET['id'] ?? 0;

if ($id) {
    if (deleteConsulta($id)) {
        $_SESSION['flash_message'] = 'Consulta eliminada exitosamente';
        $_SESSION['flash_type'] = 'success';
    } else {
        $_SESSION['flash_message'] = 'Error al eliminar la consulta';
        $_SESSION['flash_type'] = 'danger';
    }
}

header('Location: ' . APP_URL . '/pages/consulta/listar.php');
exit;
