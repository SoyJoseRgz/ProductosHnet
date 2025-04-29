<?php
/**
 * Archivo de configuración principal
 */

// Configuración de la aplicación
define('APP_NAME', 'ProductosHnet');
define('APP_URL', 'https://hnet.com.mx/ProductosHnet');

// Configuración de entorno
define('ENVIRONMENT', 'production');

// Configuración de errores
if (ENVIRONMENT === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
}

// Configuración de sesiones
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', ENVIRONMENT === 'production' ? 1 : 0);
ini_set('session.save_path', BASE_PATH . '/sessions');

// Cargar usuarios
$GLOBALS['users'] = require_once BASE_PATH . '/config/users.php';
