<?php



define('DB_HOST', 'localhost');
define('DB_NAME', 'petcare');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');








define('APP_NAME', 'PetCare');
define('APP_URL', 'http://localhost/tpr'); 


define('BASE_PATH', dirname(__DIR__));


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


date_default_timezone_set('America/Argentina/Buenos_Aires');
