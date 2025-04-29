<?php
/**
 * Trait para manejo de errores
 * 
 * Este trait proporciona métodos para manejar errores de manera consistente
 * 
 * @package app\Traits
 */
namespace app\Traits;

trait ErrorHandlerTrait
{
    /**
     * Registra un error y muestra un mensaje
     * 
     * @param string $message Mensaje de error
     * @param string $title Título del error (opcional)
     * @param int $statusCode Código de estado HTTP (opcional)
     * @return void
     */
    protected function handleError(string $message, string $title = 'Error', int $statusCode = 500): void
    {
        // Registrar el error si existe la función logError
        if (function_exists('logError')) {
            logError($message);
        }
        
        // Establecer el código de estado HTTP si es diferente de 200
        if ($statusCode !== 200) {
            http_response_code($statusCode);
        }
        
        // Mostrar el mensaje de error
        echo "<div class='error-container'>";
        echo "<h1>" . htmlspecialchars($title) . "</h1>";
        echo "<p>" . htmlspecialchars($message) . "</p>";
        echo "</div>";
        exit;
    }
    
    /**
     * Registra un error y muestra la vista de error 404
     * 
     * @param string $message Mensaje de error
     * @return void
     */
    protected function handleNotFound(string $message): void
    {
        // Registrar el error
        if (function_exists('logError')) {
            logError("Recurso no encontrado: " . $message);
        }
        
        // Establecer el código de estado HTTP
        http_response_code(404);
        
        // Si tenemos acceso al servicio de vistas, mostrar la vista de error 404
        if (isset($this->viewService)) {
            $this->viewService->render('errors/404.php', [
                'title' => 'Página no encontrada'
            ]);
            exit;
        }
        
        // Si no tenemos acceso al servicio de vistas, mostrar un mensaje simple
        $this->handleError($message, 'Página no encontrada', 404);
    }
}
