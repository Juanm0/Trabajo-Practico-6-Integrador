<?php
/**
 * Funciones de autenticación y autorización - PetCare
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

/**
 * Verifica si el usuario ha iniciado sesión
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Obtiene el usuario actual
 */
function getCurrentUser() {
    if (!isLoggedIn()) return null;
    
    $db = getDB();
    $stmt = $db->prepare("SELECT u.*, r.nombre as rol_nombre FROM usuarios u LEFT JOIN roles r ON u.id_rol = r.id_rol WHERE u.id_usuario = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

/**
 * Redirige si no está logueado
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . APP_URL . '/pages/auth/login.php');
        exit;
    }
}

/**
 * Redirige si no tiene el rol requerido
 */
function requireRole($roles) {
    requireLogin();
    $user = getCurrentUser();
    if (!in_array($user['rol_nombre'], (array)$roles)) {
        header('Location: ' . APP_URL . '/index.php');
        exit;
    }
}

/**
 * Login de usuario
 */
function login($email, $password) {
    $db = getDB();
    $stmt = $db->prepare("SELECT u.*, r.nombre as rol_nombre FROM usuarios u LEFT JOIN roles r ON u.id_rol = r.id_rol WHERE u.email = ? AND u.activo = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id_usuario'];
        $_SESSION['user_nombre'] = $user['nombre'];
        $_SESSION['user_rol'] = $user['id_rol'];
        $_SESSION['user_rol_nombre'] = $user['rol_nombre'] ?? 'Usuario';
        return true;
    }
    return false;
}

/**
 * Logout
 */
function logout() {
    session_destroy();
    header('Location: ' . APP_URL . '/pages/auth/login.php');
    exit;
}

/**
 * Registrar usuario
 */
function register($nombre, $apellido, $email, $password, $telefono = null, $id_rol = 3) {
    $db = getDB();
    
    // Verificar si el email ya existe
    $stmt = $db->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'El email ya está registrado'];
    }
    
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO usuarios (nombre, apellido, email, password, telefono, id_rol) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $apellido, $email, $hash, $telefono, $id_rol]);
    
    return ['success' => true, 'message' => 'Registro exitoso'];
}

/**
 * Cambiar contraseña
 */
function changePassword($userId, $currentPassword, $newPassword) {
    $db = getDB();
    $stmt = $db->prepare("SELECT password FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    if (!$user || !password_verify($currentPassword, $user['password'])) {
        return ['success' => false, 'message' => 'Contraseña actual incorrecta'];
    }
    
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $db->prepare("UPDATE usuarios SET password = ? WHERE id_usuario = ?");
    $stmt->execute([$hash, $userId]);
    
    return ['success' => true, 'message' => 'Contraseña cambiada exitosamente'];
}

/**
 * Obtener todos los roles
 */
function getRoles() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM roles");
    return $stmt->fetchAll();
}
