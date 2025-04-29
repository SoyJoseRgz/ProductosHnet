<?php
/**
 * Controlador de autenticación
 *
 * Este controlador maneja las operaciones relacionadas con la autenticación de usuarios
 *
 * @package app\Controllers
 */
namespace app\Controllers;

use app\Interfaces\AuthServiceInterface;
use app\Interfaces\ViewServiceInterface;
use app\Interfaces\SessionServiceInterface;
use app\Interfaces\ValidatorServiceInterface;
use app\Services\ServiceFactory;
use app\Traits\ErrorHandlerTrait;
use app\Utils\Redirector;

class AuthController
{
    use ErrorHandlerTrait;

    /**
     * Servicio de autenticación
     *
     * @var AuthServiceInterface
     */
    private $authService;

    /**
     * Servicio de vistas
     *
     * @var ViewServiceInterface
     */
    private $viewService;

    /**
     * Servicio de sesión
     *
     * @var SessionServiceInterface
     */
    private $sessionService;

    /**
     * Servicio de validación
     *
     * @var ValidatorServiceInterface
     */
    private $validatorService;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Inicializar los servicios usando la fábrica
        $this->authService = ServiceFactory::getAuthService();
        $this->viewService = ServiceFactory::getViewService();
        $this->sessionService = ServiceFactory::getSessionService();
        $this->validatorService = ServiceFactory::getValidatorService();
    }

    /**
     * Muestra la página de login y procesa el formulario
     *
     * @return void
     */
    public function login(): void
    {
        // Si el usuario ya está autenticado, redirigir a la página principal
        if ($this->sessionService->isActive()) {
            Redirector::home();
        }

        $error = '';

        // Procesar el formulario si es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener y validar los datos del formulario
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validar el formulario
            $rules = [
                'email' => ['required', 'email'],
                'password' => ['required', 'min:6']
            ];

            $errors = $this->validatorService->validateForm($_POST, $rules);

            // Si no hay errores de validación, verificar credenciales
            if (empty($errors)) {
                // Sanitizar el email
                $email = $this->validatorService->sanitize($email);

                // Verificar credenciales
                if ($this->authService->verifyCredentials($email, $password)) {
                    // Obtener datos del usuario
                    $userData = $this->authService->getUserByEmail($email);

                    // Iniciar sesión
                    $this->sessionService->start([
                        'email' => $email,
                        'name' => $userData['name']
                    ]);

                    // Registrar el inicio de sesión
                    if (function_exists('logError')) {
                        logError("Usuario $email inició sesión");
                    }

                    // Redirigir a la página principal
                    Redirector::home();
                } else {
                    $error = 'Credenciales inválidas. Por favor, intenta de nuevo.';
                }
            } else {
                // Mostrar el primer error de validación
                $error = reset($errors);
            }
        }

        // Datos para la vista
        $viewData = [
            'title' => 'Iniciar Sesión',
            'extraCss' => ['login'],
            'error' => $error
        ];

        // Renderizar la vista de login
        $this->viewService->render('auth/login.php', $viewData);
    }

    /**
     * Cierra la sesión del usuario
     *
     * @return void
     */
    public function logout(): void
    {
        // Obtener datos del usuario antes de cerrar sesión
        $userData = $this->sessionService->getUserData();

        // Cerrar la sesión
        $this->sessionService->end();

        // Registrar el cierre de sesión
        if (function_exists('logError') && isset($userData['email'])) {
            logError("Usuario {$userData['email']} cerró sesión");
        }

        // Redirigir al login
        Redirector::login();
    }
}
