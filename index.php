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

// Autoload de clases
spl_autoload_register(function ($class) {
    $file = BASE_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Iniciar sesión
session_start();

// Inicializar el servicio de logging
$logger = app\Services\ServiceFactory::getLoggerService();

// Manejador de errores personalizado
set_error_handler(function($errno, $errstr, $errfile, $errline) use ($logger) {
    $logger->error($errstr, ['file' => $errfile, 'line' => $errline]);
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

// Enrutamiento básico
if (empty($uri) || $uri === 'index.php') {
    // Crear el servicio de sesión para verificar la autenticación
    $sessionService = app\Services\ServiceFactory::getSessionService();

    // Si el usuario está autenticado, mostrar la página principal
    if ($sessionService->isActive()) {
        $controller = new app\Controllers\HomeController();
        $controller->index();
    } else {
        // Si no está autenticado, redirigir al login
        header('Location: ' . APP_URL . '/login');
        exit;
    }
} elseif ($uri === 'login') {
    try {
        $logger->info("Intentando cargar el controlador de autenticación para login");
        $controller = new app\Controllers\AuthController();
        $logger->info("Controlador de autenticación creado, llamando al método login");
        $controller->login();
        $logger->info("Método login ejecutado");
    } catch (Exception $e) {
        $logger->error("Error en login: " . $e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
        echo "<h1>Error en la página de login</h1>";
        echo "<p>Se ha producido un error. Por favor, contacte al administrador.</p>";
        echo "<pre>" . $e->getMessage() . "</pre>";
    }
} elseif ($uri === 'logout') {
    $controller = new app\Controllers\AuthController();
    $controller->logout();

} elseif ($uri === 'syscom' || $uri === 'syscom/') {
    // Redirigir a categorías de SYSCOM
    header('Location: ' . APP_URL . '/syscom/categories');
    exit;
} elseif ($uri === 'syscom/categories') {
    // Categorías de SYSCOM
    $controller = new app\Controllers\SyscomController();
    $controller->categories();
} elseif (preg_match('~^syscom/productos/(\d+)$~', $uri, $matches)) {
    // Productos de una categoría específica
    $categoryId = (int)$matches[1];
    $controller = new app\Controllers\SyscomController();
    $controller->products($categoryId);
} elseif ($uri === 'syscom/exchange-rate') {
    // Tipo de cambio de SYSCOM
    $controller = new app\Controllers\SyscomController();
    $controller->exchangeRate();
} elseif ($uri === 'woocommerce' || $uri === 'woocommerce/') {
    // Página principal de WooCommerce
    $controller = new app\Controllers\WooCommerceController();
    $controller->index();
} else {
    // Registrar información sobre la ruta no encontrada
    $logger->warning("Ruta no encontrada: $uri");

    // Página no encontrada
    $controller = new app\Controllers\ErrorController();
    $controller->notFound();
}
