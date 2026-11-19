<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
$user = getCurrentUser();
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - <?php echo isset($pageTitle) ? $pageTitle : 'Sistema Veterinario'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo APP_URL; ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php if (isLoggedIn()): ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?php echo APP_URL; ?>/index.php">
                <i class="bi bi-heart-pulse me-2"></i><?php echo APP_NAME; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'index.php' ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/index.php">
                            <i class="bi bi-house-door me-1"></i>Inicio
                        </a>
                    </li>
                    
                    <?php if (in_array($user['rol_nombre'], ['Admin', 'Usuario común'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo strpos($currentPage, 'cliente') !== false ? 'active' : ''; ?>" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-people me-1"></i>Clientes
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/pages/cliente/listar.php"><i class="bi bi-list me-2"></i>Listar</a></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/pages/cliente/crear.php"><i class="bi bi-plus-circle me-2"></i>Nuevo</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo strpos($currentPage, 'mascota') !== false ? 'active' : ''; ?>" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-heart me-1"></i>Mascotas
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/pages/mascota/listar.php"><i class="bi bi-list me-2"></i>Listar</a></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/pages/mascota/crear.php"><i class="bi bi-plus-circle me-2"></i>Nueva</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (in_array($user['rol_nombre'], ['Admin', 'Veterinario'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo strpos($currentPage, 'veterinario') !== false ? 'active' : ''; ?>" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-badge me-1"></i>Veterinarios
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/pages/veterinario/listar.php"><i class="bi bi-list me-2"></i>Listar</a></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/pages/veterinario/crear.php"><i class="bi bi-plus-circle me-2"></i>Nuevo</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (in_array($user['rol_nombre'], ['Admin', 'Veterinario', 'Usuario común'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo strpos($currentPage, 'consulta') !== false ? 'active' : ''; ?>" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-journal-medical me-1"></i>Consultas
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/pages/consulta/listar.php"><i class="bi bi-list me-2"></i>Listar</a></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/pages/consulta/crear.php"><i class="bi bi-plus-circle me-2"></i>Nueva</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    
                    <?php if ($user['rol_nombre'] === 'Admin'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo strpos($currentPage, 'usuario') !== false ? 'active' : ''; ?>" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-gear me-1"></i>Administración
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/pages/admin/usuarios.php"><i class="bi bi-people me-2"></i>Usuarios</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i><?php echo htmlspecialchars($user['nombre']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/pages/auth/perfil.php"><i class="bi bi-person me-2"></i>Mi Perfil</a></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/pages/auth/cambiar_password.php"><i class="bi bi-key me-2"></i>Cambiar Contraseña</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo APP_URL; ?>/pages/auth/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>
    
    <main class="container py-4">
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['flash_type'] ?? 'info'; ?> alert-dismissible fade show" role="alert">
                <?php 
                    echo $_SESSION['flash_message'];
                    unset($_SESSION['flash_message'], $_SESSION['flash_type']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
