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
use app\Services\ServiceFactory;

class SyscomController extends BaseController
{
    /**
     * Repositorio de SYSCOM
     *
     * @var SyscomRepositoryInterface
     */
    private $syscomRepository;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Llamar al constructor padre para inicializar servicios comunes
        parent::__construct();

        // Inicializar servicios específicos
        $this->syscomRepository = ServiceFactory::getSyscomRepository();
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

        // Obtener categorías y manejar posibles errores
        $categories = $this->syscomRepository->getCategories();

        // Usar el método handleApiError para manejar errores y reintentos
        $categories = $this->handleApiError(
            $categories,
            'Error al obtener categorías de SYSCOM',
            function() {
                // Callback para reintento directo con la API
                $apiService = ServiceFactory::getSyscomApiService();
                $directResult = $apiService->request('GET', 'categorias');

                // Registrar resultado del reintento
                $this->logger->info('Intento directo de obtener categorías', [
                    'resultado' => $directResult !== null ? 'éxito' : 'fallo'
                ]);

                return $directResult;
            }
        );

        // Registrar éxito si tenemos datos
        if ($categories !== null) {
            $this->logger->info('Categorías obtenidas correctamente', [
                'cantidad' => is_array($categories) ? count($categories) : 0
            ]);
        }

        // Renderizar vista
        $this->render('syscom/categories.php', [
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

        // Obtener productos de la categoría y manejar posibles errores
        $products = $this->syscomRepository->getProductsByCategory($categoryId, $page, $order);

        // No hay un reintento directo para productos, pero podríamos implementarlo si fuera necesario
        // Por ahora, solo registramos el error o éxito

        // Registrar éxito si tenemos datos
        if ($products !== null) {
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
        } else {
            $this->logger->error('Error al obtener productos de la categoría', [
                'categoria_id' => $categoryId
            ]);
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
        $this->render('syscom/products.php', [
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

        // Obtener tipo de cambio y manejar posibles errores
        $exchangeRate = $this->syscomRepository->getExchangeRate();

        // Usar el método handleApiError para manejar errores y reintentos
        $exchangeRate = $this->handleApiError(
            $exchangeRate,
            'Error al obtener tipo de cambio de SYSCOM',
            function() {
                // Callback para reintento directo con la API
                $apiService = ServiceFactory::getSyscomApiService();
                $directResult = $apiService->request('GET', 'tipocambio');

                // Registrar resultado del reintento
                $this->logger->info('Intento directo de obtener tipo de cambio', [
                    'resultado' => $directResult !== null ? 'éxito' : 'fallo'
                ]);

                return $directResult;
            }
        );

        // Registrar éxito si tenemos datos
        if ($exchangeRate !== null) {
            $this->logger->info('Tipo de cambio obtenido correctamente', [
                'datos' => json_encode($exchangeRate)
            ]);
        }

        // Renderizar vista
        $this->render('syscom/exchange_rate.php', [
            'title' => 'Tipo de Cambio',
            'exchangeRate' => $exchangeRate,
            'extraCss' => ['syscom'],
            'extraJs' => ['syscom']
        ]);
    }
}
