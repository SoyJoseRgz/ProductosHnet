<?php
/**
 * Punto de entrada principal de la aplicación
 */

// Definir la ruta base del proyecto
define('BASE_PATH', __DIR__);

// La configuración de errores se maneja en config.php
// Pero mantenemos el registro de errores para depuración
// Los errores no se mostrarán en producción

// Cargar configuración
require_once BASE_PATH . '/config/config.php';

// Iniciar sesión
session_start();

// Función para registrar errores
function logError($message, $file = null, $line = null) {
    $errorLog = BASE_PATH . '/error_log.txt';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message";
    if ($file && $line) {
        $logMessage .= " in $file on line $line";
    }
    file_put_contents($errorLog, $logMessage . PHP_EOL, FILE_APPEND);
}

// Manejador de errores personalizado
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    logError($errstr, $errfile, $errline);
    // No suprimir el manejador de errores interno de PHP
    return false;
});

// Enrutamiento simple
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = ltrim($uri, '/');

// Eliminar el prefijo de la ruta base (ProductosHnet)
$baseFolder = basename(dirname($_SERVER['SCRIPT_NAME']));
if ($baseFolder !== '') {
    $pattern = '~^' . $baseFolder . '/~';
    $uri = preg_replace($pattern, '', $uri);
}

// Eliminar ProductosHnet/ explícitamente si todavía está presente
$uri = str_replace('ProductosHnet/', '', $uri);

// Autoload de clases
spl_autoload_register(function ($class) {
    $file = BASE_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Enrutamiento básico
if (empty($uri) || $uri === 'index.php') {
    // Si el usuario está autenticado, mostrar la página principal
    if (isset($_SESSION['user'])) {
        $controller = new app\Controllers\HomeController();
        $controller->index();
    } else {
        // Si no está autenticado, redirigir al login
        header('Location: ' . APP_URL . '/login');
        exit;
    }
} elseif ($uri === 'login') {
    try {
        logError("Intentando cargar el controlador de autenticación para login");
        $controller = new app\Controllers\AuthController();
        logError("Controlador de autenticación creado, llamando al método login");
        $controller->login();
        logError("Método login ejecutado");
    } catch (Exception $e) {
        logError("Error en login: " . $e->getMessage(), $e->getFile(), $e->getLine());
        echo "<h1>Error en la página de login</h1>";
        echo "<p>Se ha producido un error. Por favor, revisa el archivo error_log.txt para más detalles.</p>";
        echo "<pre>" . $e->getMessage() . "</pre>";
    }
} elseif ($uri === 'logout') {
    $controller = new app\Controllers\AuthController();
    $controller->logout();
} elseif ($uri === 'debug') {
    // Página de depuración
    require_once BASE_PATH . '/debug.php';
} else {
    // Página no encontrada
    $controller = new app\Controllers\ErrorController();
    $controller->notFound();

    // Registrar información sobre la ruta no encontrada
    logError("Ruta no encontrada: $uri");
}
