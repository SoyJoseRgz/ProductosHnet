<?php
/**
 * Archivo de configuración principal
 */

// Configuración de la aplicación
define('APP_NAME', 'ProductosHnet');
define('APP_URL', 'http://localhost:81/ProductosHnet');

// Configuración de entorno
define('ENVIRONMENT', 'development');

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

// Configuración de la API de SYSCOM
define('SYSCOM_CLIENT_ID', '0fVKpz3hW5QhlsgU7ePWm2IBwXFDVW28');
define('SYSCOM_CLIENT_SECRET', '0NiaUnCMs7baV6HGacVKXEsZEyCO728j1AU4cA9C');

// Configuración de la API de WooCommerce
define('WOOCOMMERCE_STORE_URL', 'http://mi-tienda.local/');
define('WOOCOMMERCE_CONSUMER_KEY', 'ck_9843139c9f9b7224b4af786289477a9c546cd7c5');
define('WOOCOMMERCE_CONSUMER_SECRET', 'cs_86743d39590d38fc5449904f1a594d764fe391f6');

// Cargar usuarios
$GLOBALS['users'] = require_once BASE_PATH . '/config/users.php';
