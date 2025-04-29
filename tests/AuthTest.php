<?php
/**
 * Pruebas para el servicio de autenticación
 */

// Definir la ruta base del proyecto si no está definida
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

// Cargar el autoloader
require_once BASE_PATH . '/index.php';

// Importar las clases necesarias
use app\Services\AuthService;

// Función simple para ejecutar pruebas
function runTest($name, $callback) {
    echo "Ejecutando prueba: $name... ";
    try {
        $result = $callback();
        if ($result) {
            echo "PASÓ\n";
            return true;
        } else {
            echo "FALLÓ\n";
            return false;
        }
    } catch (Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
        return false;
    }
}

// Datos de prueba
$testUsers = [
    'test@example.com' => [
        'password' => 'password123',
        'name' => 'Usuario de Prueba'
    ]
];

// Pruebas para AuthService
echo "=== Pruebas de AuthService ===\n";

// Prueba 1: Verificar credenciales válidas
runTest('Verificar credenciales válidas', function() use ($testUsers) {
    $authService = new AuthService($testUsers);
    return $authService->verifyCredentials('test@example.com', 'password123') === true;
});

// Prueba 2: Verificar credenciales inválidas
runTest('Verificar credenciales inválidas', function() use ($testUsers) {
    $authService = new AuthService($testUsers);
    return $authService->verifyCredentials('test@example.com', 'wrongpassword') === false;
});

// Prueba 3: Verificar usuario inexistente
runTest('Verificar usuario inexistente', function() use ($testUsers) {
    $authService = new AuthService($testUsers);
    return $authService->verifyCredentials('nonexistent@example.com', 'password123') === false;
});

// Prueba 4: Obtener información de usuario
runTest('Obtener información de usuario', function() use ($testUsers) {
    $authService = new AuthService($testUsers);
    $user = $authService->getUserByEmail('test@example.com');
    return $user !== null && $user['name'] === 'Usuario de Prueba';
});

// Prueba 5: Obtener información de usuario inexistente
runTest('Obtener información de usuario inexistente', function() use ($testUsers) {
    $authService = new AuthService($testUsers);
    $user = $authService->getUserByEmail('nonexistent@example.com');
    return $user === null;
});

echo "\nPruebas completadas.\n";
