<?php
/**
 * Controlador de la página principal
 *
 * Este controlador maneja las operaciones relacionadas con la página principal
 *
 * @package app\Controllers
 */
namespace app\Controllers;

use app\Interfaces\SessionServiceInterface;
use app\Interfaces\ViewServiceInterface;
use app\Services\ServiceFactory;
use app\Traits\ErrorHandlerTrait;

class HomeController
{
    use ErrorHandlerTrait;

    /**
     * Servicio de sesión
     *
     * @var SessionServiceInterface
     */
    private $sessionService;

    /**
     * Servicio de vistas
     *
     * @var ViewServiceInterface
     */
    private $viewService;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Inicializar servicios usando la fábrica
        $this->sessionService = ServiceFactory::getSessionService();
        $this->viewService = ServiceFactory::getViewService();

        // Verificar si el usuario está autenticado
        $this->sessionService->requireAuthentication();
    }

    /**
     * Muestra la página principal
     *
     * @return void
     */
    public function index(): void
    {
        // Obtener datos del usuario
        $user = $this->sessionService->getUserData();

        // Verificar que tenemos datos del usuario
        if (!$user) {
            $this->handleError('No se pudo obtener la información del usuario', 'Error de sesión');
        }

        // Datos para la vista
        $viewData = [
            'title' => 'Inicio',
            'user' => $user,
            'extraCss' => ['home']
        ];

        // Renderizar la vista principal
        $this->viewService->render('home/index.php', $viewData);
    }
}
