<?php



define('DB_HOST', 'sql211.infinityfree.com');
define('DB_NAME', 'if0_43012718_petcaredb');
define('DB_USER', 'if0_43012718');
define('DB_PASS', 'browtf1234isood');
define('DB_CHARSET', 'utf8mb4');


/**
 * PARA QUE CORRA EN XAMPP HAY QUE PONER ESTOS VALORES PERO DE LOCALHOST, ACTUALMENTE ME LOS OLVIDÉ
 */



define('APP_NAME', 'PetCare');
define('APP_URL', 'https://juanjuanjuan.freedev.app/tp6');


define('BASE_PATH', dirname(__DIR__));


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


date_default_timezone_set('America/Argentina/Buenos_Aires');
