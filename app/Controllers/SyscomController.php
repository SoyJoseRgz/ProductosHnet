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
     * Página principal de productos SYSCOM
     *
     * @return void
     */
    public function index(): void
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

        // Obtener productos
        $products = $this->syscomRepository->getProducts($page, $limit);

        // Renderizar vista
        $this->viewService->render('syscom/index.php', [
            'title' => 'Productos SYSCOM',
            'products' => $products,
            'page' => $page,
            'limit' => $limit,
            'extraCss' => ['syscom'],
            'extraJs' => ['syscom']
        ]);
    }

    /**
     * Búsqueda de productos
     *
     * @return void
     */
    public function search(): void
    {
        $query = isset($_GET['q']) ? trim($_GET['q']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 12;

        $results = null;
        $categories = null;

        // Obtener categorías para el filtro
        $categories = $this->syscomRepository->getCategories();

        if (!empty($query)) {
            // Registrar búsqueda
            $this->logger->info('Búsqueda de productos', ['query' => $query]);

            // Realizar búsqueda
            $results = $this->syscomRepository->searchProducts($query, $page, $limit);
        }

        // Renderizar vista
        $this->viewService->render('syscom/search.php', [
            'title' => 'Búsqueda de Productos',
            'query' => $query,
            'results' => $results,
            'categories' => $categories,
            'page' => $page,
            'limit' => $limit,
            'extraCss' => ['syscom'],
            'extraJs' => ['syscom']
        ]);
    }

    /**
     * Detalles de un producto
     *
     * @return void
     */
    public function product(): void
    {
        $productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($productId <= 0) {
            $this->handleError('ID de producto inválido');
            return;
        }

        // Obtener detalles del producto
        $product = $this->syscomRepository->getProductDetails($productId);

        if ($product === null) {
            $this->handleError('Producto no encontrado');
            return;
        }

        // Registrar visualización
        $this->logger->info('Visualización de producto', ['product_id' => $productId]);

        // Renderizar vista
        $this->viewService->render('syscom/product.php', [
            'title' => 'Detalles del Producto',
            'product' => $product,
            'extraCss' => ['syscom'],
            'extraJs' => ['syscom']
        ]);
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
     * Productos por categoría
     *
     * @return void
     */
    public function category(): void
    {
        $categoryId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

        if ($categoryId <= 0) {
            $this->handleError('ID de categoría inválido');
            return;
        }

        // Obtener productos por categoría
        $products = $this->syscomRepository->getProductsByCategory($categoryId, $page, $limit);

        // Renderizar vista
        $this->viewService->render('syscom/category.php', [
            'title' => 'Productos por Categoría',
            'products' => $products,
            'categoryId' => $categoryId,
            'page' => $page,
            'limit' => $limit,
            'extraCss' => ['syscom'],
            'extraJs' => ['syscom']
        ]);
    }
}
