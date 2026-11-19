<?php
/**
 * Configuración de la base de datos - PetCare
 * Ajusta estos valores según tu entorno (XAMPP, InfinityFree, etc.)
 */

// Configuración para XAMPP local
define('DB_HOST', 'localhost');
define('DB_NAME', 'petcare');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Configuración para InfinityFree (descomenta y ajusta al subir)
// define('DB_HOST', 'sqlXXX.infinityfree.com');
// define('DB_NAME', 'if0_XXXXXXXX_petcare');
// define('DB_USER', 'if0_XXXXXXXX');
// define('DB_PASS', 'tu_password');

// Configuración de la aplicación
define('APP_NAME', 'PetCare');
define('APP_URL', 'http://localhost/TP6_INTEGRADOR'); // Cambia según tu carpeta en htdocs

// Ruta base del proyecto (para includes confiables en Windows)
define('BASE_PATH', dirname(__DIR__));

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Zona horaria
date_default_timezone_set('America/Argentina/Buenos_Aires');
