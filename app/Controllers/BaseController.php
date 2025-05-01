<?php
/**
 * Controlador base
 *
 * Esta clase abstracta proporciona funcionalidad común para todos los controladores
 *
 * @package app\Controllers
 */
namespace app\Controllers;

use app\Interfaces\ViewServiceInterface;
use app\Interfaces\SessionServiceInterface;
use app\Interfaces\LoggerServiceInterface;
use app\Services\ServiceFactory;
use app\Traits\ErrorHandlerTrait;

abstract class BaseController
{
    use ErrorHandlerTrait;

    /**
     * Servicio de vistas
     *
     * @var ViewServiceInterface
     */
    protected $viewService;

    /**
     * Servicio de sesión
     *
     * @var SessionServiceInterface
     */
    protected $sessionService;

    /**
     * Servicio de logging
     *
     * @var LoggerServiceInterface
     */
    protected $logger;

    /**
     * Indica si se requiere autenticación para este controlador
     *
     * @var bool
     */
    protected $requiresAuth = true;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Inicializar servicios comunes
        $this->viewService = ServiceFactory::getViewService();
        $this->sessionService = ServiceFactory::getSessionService();
        $this->logger = ServiceFactory::getLoggerService();

        // Verificar autenticación si es necesario
        if ($this->requiresAuth) {
            $this->sessionService->requireAuthentication();
        }
    }

    /**
     * Renderiza una vista
     *
     * @param string $viewPath Ruta de la vista
     * @param array $data Datos para la vista
     * @return void
     */
    protected function render(string $viewPath, array $data = []): void
    {
        $this->viewService->render($viewPath, $data);
    }

    /**
     * Incluye un componente en la vista
     *
     * @param string $componentName Nombre del componente
     * @param array $data Datos para pasar al componente
     * @return void
     */
    protected function includeComponent(string $componentName, array $data = []): void
    {
        $this->viewService->includeComponent($componentName, $data);
    }

    /**
     * Maneja errores de API y realiza reintentos si es necesario
     *
     * @param mixed $result Resultado de la operación de API
     * @param string $errorMessage Mensaje de error a registrar
     * @param callable $retryCallback Función a ejecutar para reintentar la operación
     * @param array $logContext Contexto adicional para el log
     * @return mixed Resultado de la operación o del reintento
     */
    protected function handleApiError($result, string $errorMessage, callable $retryCallback, array $logContext = []): mixed
    {
        // Si el resultado es nulo o falso, consideramos que hubo un error
        if ($result === null || $result === false) {
            // Registrar el error
            $this->logger->error($errorMessage, $logContext);

            // Intentar nuevamente con el callback proporcionado
            $retryResult = $retryCallback();

            // Registrar el resultado del reintento
            if ($retryResult !== null && $retryResult !== false) {
                $this->logger->info('Reintento exitoso después de error de API', $logContext);
                return $retryResult;
            } else {
                $this->logger->error('Reintento fallido después de error de API', $logContext);
                return $result; // Devolver el resultado original (error)
            }
        }

        // Si no hubo error, devolver el resultado original
        return $result;
    }
}
