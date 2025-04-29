<?php
/**
 * Archivo de depuración
 * Muestra información sobre la solicitud actual
 */

// Encabezados para evitar caché
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

// Mostrar todos los errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo '<h1>Información de Depuración</h1>';

// Información del servidor
echo '<h2>Información del Servidor</h2>';
echo '<pre>';
echo 'SERVER_NAME: ' . $_SERVER['SERVER_NAME'] . "\n";
echo 'HTTP_HOST: ' . $_SERVER['HTTP_HOST'] . "\n";
echo 'REQUEST_URI: ' . $_SERVER['REQUEST_URI'] . "\n";
echo 'SCRIPT_NAME: ' . $_SERVER['SCRIPT_NAME'] . "\n";
echo 'PHP_SELF: ' . $_SERVER['PHP_SELF'] . "\n";
echo 'DOCUMENT_ROOT: ' . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo '</pre>';

// Información de la ruta
echo '<h2>Análisis de Ruta</h2>';
echo '<pre>';
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
echo 'URI después de parse_url: ' . $uri . "\n";

$uri = ltrim($uri, '/');
echo 'URI después de ltrim: ' . $uri . "\n";

$baseFolder = basename(dirname($_SERVER['SCRIPT_NAME']));
echo 'Carpeta base: ' . $baseFolder . "\n";

if ($baseFolder !== '') {
    $pattern = '~^' . $baseFolder . '/~';
    echo 'Patrón de reemplazo: ' . $pattern . "\n";
    $uri = preg_replace($pattern, '', $uri);
    echo 'URI después de eliminar carpeta base: ' . $uri . "\n";
}

$uri = str_replace('ProductosHnet/', '', $uri);
echo 'URI final: ' . $uri . "\n";
echo '</pre>';

// Información de sesión
echo '<h2>Información de Sesión</h2>';
echo '<pre>';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
echo 'ID de Sesión: ' . session_id() . "\n";
echo 'Variables de Sesión: ';
print_r($_SESSION);
echo '</pre>';

// Información de cookies
echo '<h2>Cookies</h2>';
echo '<pre>';
print_r($_COOKIE);
echo '</pre>';

// Información de archivos
echo '<h2>Archivos Importantes</h2>';
echo '<pre>';
$files = [
    'index.php',
    'config/config.php',
    '.htaccess',
    'app/Controllers/AuthController.php',
    'app/Views/auth/login.php'
];

foreach ($files as $file) {
    echo $file . ': ' . (file_exists($file) ? 'Existe' : 'No existe') . "\n";
}
echo '</pre>';
