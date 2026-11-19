<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';


function isLoggedIn() {
    return isset($_SESSION['user_id']);
}


function getCurrentUser() {
    if (!isLoggedIn()) return null;
    
    $db = getDB();
    $stmt = $db->prepare("SELECT u.*, r.nombre as rol_nombre FROM usuarios u LEFT JOIN roles r ON u.id_rol = r.id_rol WHERE u.id_usuario = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}


function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . APP_URL . '/pages/auth/login.php');
        exit;
    }
}


function requireRole($roles) {
    requireLogin();
    $user = getCurrentUser();
    if (!in_array($user['rol_nombre'], (array)$roles)) {
        header('Location: ' . APP_URL . '/index.php');
        exit;
    }
}


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


function logout() {
    session_destroy();
    header('Location: ' . APP_URL . '/pages/auth/login.php');
    exit;
}


function register($nombre, $apellido, $email, $password, $telefono = null, $id_rol = 3) {
    $db = getDB();
    
    
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


function getRoles() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM roles");
    return $stmt->fetchAll();
}
