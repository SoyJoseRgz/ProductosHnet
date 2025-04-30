<?php
/**
 * Controlador para la API de SYSCOM
 *
 * Este controlador maneja las operaciones relacionadas con la API de SYSCOM
 *
 * @package app\Controllers
 */
namespace app\Controllers;

use app\Interfaces\SyscomRepositoryInterface;
use app\Interfaces\ViewServiceInterface;
use app\Interfaces\SessionServiceInterface;
use app\Interfaces\LoggerServiceInterface;
use app\Services\ServiceFactory;
use app\Traits\ErrorHandlerTrait;

class SyscomController
{
    use ErrorHandlerTrait;

    /**
     * Repositorio de SYSCOM
     *
     * @var SyscomRepositoryInterface
     */
    private $syscomRepository;

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
     * Servicio de logging
     *
     * @var LoggerServiceInterface
     */
    private $logger;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Inicializar servicios usando la fábrica
        $this->syscomRepository = ServiceFactory::getSyscomRepository();
        $this->viewService = ServiceFactory::getViewService();
        $this->sessionService = ServiceFactory::getSessionService();
        $this->logger = ServiceFactory::getLoggerService();

        // Verificar si el usuario está autenticado
        $this->sessionService->requireAuthentication();
    }

    /**
     * Categorías de productos
     *
     * @return void
     */
    public function categories(): void
    {
        // Registrar intento de obtener categorías
        $this->logger->info('Intentando obtener categorías de SYSCOM');

        // Obtener categorías
        $categories = $this->syscomRepository->getCategories();

        // Verificar si se obtuvieron categorías
        if ($categories === null) {
            $this->logger->error('Error al obtener categorías de SYSCOM');

            // Intentar obtener directamente desde el servicio de API para diagnóstico
            $apiService = ServiceFactory::getSyscomApiService();
            $directCategories = $apiService->request('GET', 'categorias');

            $this->logger->info('Intento directo de obtener categorías', [
                'resultado' => $directCategories !== null ? 'éxito' : 'fallo'
            ]);

            // Si el intento directo tuvo éxito, usar esos resultados
            if ($directCategories !== null) {
                $categories = $directCategories;
            }
        } else {
            $this->logger->info('Categorías obtenidas correctamente', [
                'cantidad' => is_array($categories) ? count($categories) : 0
            ]);
        }

        // Renderizar vista
        $this->viewService->render('syscom/categories.php', [
            'title' => 'Categorías de Productos',
            'categories' => $categories,
            'extraCss' => ['syscom'],
            'extraJs' => ['syscom']
        ]);
    }

    /**
     * Productos de una categoría específica
     *
     * @param int $categoryId ID de la categoría
     * @return void
     */
    public function products(int $categoryId): void
    {
        // Obtener el número de página de la URL
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Validar que la página sea un número positivo
        if ($page < 1) {
            $page = 1;
        }

        // Obtener el orden de los resultados
        $order = isset($_GET['order']) ? $_GET['order'] : 'relevancia';

        // Validar que el orden sea válido
        $validOrders = ['relevancia', 'precio:asc', 'precio:desc', 'modelo:asc', 'modelo:desc', 'marca:asc', 'marca:desc', 'topseller'];
        if (!in_array($order, $validOrders)) {
            $order = 'relevancia';
        }

        // Registrar intento de obtener productos
        $this->logger->info('Intentando obtener productos de la categoría', [
            'categoria_id' => $categoryId,
            'pagina' => $page,
            'orden' => $order
        ]);

        // Obtener productos de la categoría
        $products = $this->syscomRepository->getProductsByCategory($categoryId, $page, $order);

        // Verificar si se obtuvieron productos
        if ($products === null) {
            $this->logger->error('Error al obtener productos de la categoría', [
                'categoria_id' => $categoryId
            ]);
        } else {
            $this->logger->info('Productos obtenidos correctamente', [
                'categoria_id' => $categoryId,
                'cantidad' => isset($products['productos']) && is_array($products['productos']) ? count($products['productos']) : 0,
                'estructura' => json_encode(array_keys($products))
            ]);

            // Registrar información detallada del primer producto para depuración
            if (isset($products['productos']) && is_array($products['productos']) && !empty($products['productos'])) {
                $firstProduct = $products['productos'][0];
                $this->logger->info('Estructura del primer producto', [
                    'keys' => json_encode(array_keys($firstProduct)),
                    'precios_keys' => isset($firstProduct['precios']) ? json_encode(array_keys($firstProduct['precios'])) : 'No hay precios'
                ]);
            }
        }

        // Obtener información de la categoría para mostrar el nombre
        $categories = $this->syscomRepository->getCategories();
        $categoryName = 'Categoría #' . $categoryId;

        if (is_array($categories)) {
            foreach ($categories as $category) {
                if ($category['id'] == $categoryId) {
                    $categoryName = $category['nombre'];
                    break;
                }
            }
        }

        // Renderizar vista
        $this->viewService->render('syscom/products.php', [
            'title' => 'Productos de ' . $categoryName,
            'products' => $products,
            'categoryId' => $categoryId,
            'categoryName' => $categoryName,
            'currentPage' => $page,
            'currentOrder' => $order,
            'extraCss' => ['syscom'],
            'extraJs' => ['syscom']
        ]);
    }

    /**
     * Muestra la información del tipo de cambio
     *
     * @return void
     */
    public function exchangeRate(): void
    {
        // Registrar intento de obtener tipo de cambio
        $this->logger->info('Intentando obtener tipo de cambio de SYSCOM');

        // Obtener tipo de cambio
        $exchangeRate = $this->syscomRepository->getExchangeRate();

        // Verificar si se obtuvo el tipo de cambio
        if ($exchangeRate === null) {
            $this->logger->error('Error al obtener tipo de cambio de SYSCOM');

            // Intentar obtener directamente desde el servicio de API para diagnóstico
            $apiService = ServiceFactory::getSyscomApiService();
            $directExchangeRate = $apiService->request('GET', 'tipocambio');

            $this->logger->info('Intento directo de obtener tipo de cambio', [
                'resultado' => $directExchangeRate !== null ? 'éxito' : 'fallo'
            ]);

            // Si el intento directo tuvo éxito, usar esos resultados
            if ($directExchangeRate !== null) {
                $exchangeRate = $directExchangeRate;
            }
        } else {
            $this->logger->info('Tipo de cambio obtenido correctamente', [
                'datos' => json_encode($exchangeRate)
            ]);
        }

        // Renderizar vista
        $this->viewService->render('syscom/exchange_rate.php', [
            'title' => 'Tipo de Cambio',
            'exchangeRate' => $exchangeRate,
            'extraCss' => ['syscom'],
            'extraJs' => ['syscom']
        ]);
    }
}
