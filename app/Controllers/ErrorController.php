<?php
/**
 * Controlador de errores
 *
 * Este controlador maneja las páginas de error
 *
 * @package app\Controllers
 */
namespace app\Controllers;

use app\Utils\Redirector;

class ErrorController extends BaseController
{
    /**
     * Indica si se requiere autenticación para este controlador
     *
     * @var bool
     */
    protected $requiresAuth = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Llamar al constructor padre para inicializar servicios comunes
        parent::__construct();
    }

    /**
     * Muestra la página de error 404
     *
     * @return void
     */
    public function notFound(): void
    {
        // Establecer el código de estado HTTP
        http_response_code(404);

        // Registrar el error
        $this->logger->warning("Página no encontrada: " . ($_SERVER['REQUEST_URI'] ?? 'Desconocida'));

        // Datos para la vista
        $viewData = [
            'title' => 'Página no encontrada'
        ];

        // Renderizar la vista de error 404
        $this->render('errors/404.php', $viewData);
    }

    /**
     * Muestra una página de error genérica
     *
     * @param string $message Mensaje de error
     * @param string $title Título de la página
     * @param int $statusCode Código de estado HTTP
     * @return void
     */
    public function showError(string $message, string $title = 'Error', int $statusCode = 500): void
    {
        // Establecer el código de estado HTTP
        http_response_code($statusCode);

        // Registrar el error
        $this->logger->error("Error ($statusCode): $message");

        // Datos para la vista
        $viewData = [
            'title' => $title,
            'message' => $message,
            'statusCode' => $statusCode
        ];

        // Renderizar la vista de error genérica si existe
        if (file_exists(BASE_PATH . '/app/Views/errors/generic.php')) {
            $this->render('errors/generic.php', $viewData);
        } else {
            // Si no existe, mostrar un mensaje simple
            echo "<h1>" . htmlspecialchars($title) . "</h1>";
            echo "<p>" . htmlspecialchars($message) . "</p>";
            echo "<p><a href='" . APP_URL . "'>Volver al inicio</a></p>";
        }
    }
}
