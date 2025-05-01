<?php
/**
 * Servicio de vistas
 *
 * Este servicio maneja la renderización de vistas
 *
 * @package app\Services
 */
namespace app\Services;

use app\Interfaces\ViewServiceInterface;

class ViewService implements ViewServiceInterface
{
    /**
     * Renderiza una vista con el layout principal
     *
     * @param string $viewPath Ruta de la vista a renderizar
     * @param array $data Datos para pasar a la vista
     * @return void
     */
    public function render(string $viewPath, array $data = []): void
    {
        // Agregar el servicio de vistas a los datos para poder usar componentes
        $data['viewService'] = $this;

        // Extraer variables para la vista
        extract($data);

        // Definir la ruta del contenido
        $content = BASE_PATH . '/app/Views/' . $viewPath;

        // Verificar que el archivo de vista existe
        if (!file_exists($content)) {
            $this->handleError("El archivo de vista no existe: " . $content);
        }

        // Cargar el layout principal con el contenido
        $layout = BASE_PATH . '/app/Views/layouts/main.php';
        require_once $layout;
    }

    /**
     * Incluye un componente en la vista
     *
     * @param string $componentName Nombre del componente
     * @param array $data Datos para pasar al componente
     * @return void
     */
    public function includeComponent(string $componentName, array $data = []): void
    {
        // Extraer variables para el componente
        extract($data);

        // Definir la ruta del componente
        $componentPath = BASE_PATH . '/app/Views/components/' . $componentName . '.php';

        // Verificar que el archivo del componente existe
        if (!file_exists($componentPath)) {
            $this->handleError("El archivo de componente no existe: " . $componentPath);
        }

        // Incluir el componente
        require $componentPath;
    }

    /**
     * Maneja errores en el servicio de vistas
     *
     * @param string $message Mensaje de error
     * @return void
     */
    private function handleError(string $message): void
    {
        if (function_exists('logError')) {
            logError($message);
        }

        echo "<h1>Error: Archivo de vista no encontrado</h1>";
        echo "<p>" . htmlspecialchars($message) . "</p>";
        exit;
    }
}
