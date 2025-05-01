<?php
/**
 * Controlador de la página principal
 *
 * Este controlador maneja las operaciones relacionadas con la página principal
 *
 * @package app\Controllers
 */
namespace app\Controllers;

class HomeController extends BaseController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // Llamar al constructor padre para inicializar servicios comunes
        parent::__construct();
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
        $this->render('home/index.php', $viewData);
    }
}
