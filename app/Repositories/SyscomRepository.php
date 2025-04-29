<?php
/**
 * Repositorio para la API de SYSCOM
 *
 * Este repositorio maneja la interacción con la API de SYSCOM y proporciona caché para mejorar el rendimiento
 *
 * @package app\Repositories
 */
namespace app\Repositories;

use app\Interfaces\SyscomRepositoryInterface;
use app\Interfaces\SyscomApiServiceInterface;
use app\Services\ServiceFactory;

class SyscomRepository implements SyscomRepositoryInterface
{
    /**
     * Servicio de API de SYSCOM
     *
     * @var SyscomApiServiceInterface
     */
    private $apiService;

    /**
     * Caché de datos
     *
     * @var array
     */
    private $cache = [];

    /**
     * Tiempo de expiración del caché en segundos
     *
     * @var int
     */
    private $cacheExpiration = 300; // 5 minutos

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->apiService = ServiceFactory::getSyscomApiService();
    }

    /**
     * {@inheritdoc}
     */
    public function getProducts(int $page = 1, int $limit = 10, array $filters = []): ?array
    {
        $cacheKey = 'products_' . $page . '_' . $limit . '_' . md5(json_encode($filters));

        // Verificar si existe en caché
        if ($cachedData = $this->getFromCache($cacheKey)) {
            return $cachedData;
        }

        // Preparar parámetros
        $params = [
            'pagina' => $page,
            'limite' => $limit
        ];

        // Agregar filtros adicionales
        if (!empty($filters)) {
            $params = array_merge($params, $filters);
        }

        // Realizar la petición
        $response = $this->apiService->request('GET', 'productos', $params);

        // Guardar en caché si la respuesta es válida
        if ($response !== null) {
            $this->saveToCache($cacheKey, $response);
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function searchProducts(string $query, int $page = 1, int $limit = 10): ?array
    {
        $cacheKey = 'search_' . md5($query) . '_' . $page . '_' . $limit;

        // Verificar si existe en caché
        if ($cachedData = $this->getFromCache($cacheKey)) {
            return $cachedData;
        }

        // Preparar parámetros
        $params = [
            'q' => $query,
            'pagina' => $page,
            'limite' => $limit
        ];

        // Realizar la petición
        $response = $this->apiService->request('GET', 'productos/busqueda', $params);

        // Guardar en caché si la respuesta es válida
        if ($response !== null) {
            $this->saveToCache($cacheKey, $response);
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductDetails(int $productId): ?array
    {
        $cacheKey = 'product_' . $productId;

        // Verificar si existe en caché
        if ($cachedData = $this->getFromCache($cacheKey)) {
            return $cachedData;
        }

        // Realizar la petición
        $response = $this->apiService->request('GET', 'productos/' . $productId);

        // Guardar en caché si la respuesta es válida
        if ($response !== null) {
            $this->saveToCache($cacheKey, $response);
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getCategories(): ?array
    {
        $cacheKey = 'categories';

        // Verificar si existe en caché
        if ($cachedData = $this->getFromCache($cacheKey)) {
            return $cachedData;
        }

        // Realizar la petición al endpoint de categorías
        // Según la documentación de SYSCOM: https://developers.syscom.mx/docs
        $response = $this->apiService->request('GET', 'categorias');

        // Guardar en caché si la respuesta es válida
        if ($response !== null) {
            // Caché más largo para categorías (1 hora)
            $this->saveToCache($cacheKey, $response, 3600);
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductsByCategory(int $categoryId, int $page = 1, int $limit = 10): ?array
    {
        $cacheKey = 'category_' . $categoryId . '_' . $page . '_' . $limit;

        // Verificar si existe en caché
        if ($cachedData = $this->getFromCache($cacheKey)) {
            return $cachedData;
        }

        // Preparar parámetros
        $params = [
            'categoria_id' => $categoryId, // Usar categoria_id según la estructura de la API
            'pagina' => $page,
            'limite' => $limit
        ];

        // Realizar la petición
        $response = $this->apiService->request('GET', 'productos', $params);

        // Guardar en caché si la respuesta es válida
        if ($response !== null) {
            $this->saveToCache($cacheKey, $response);
        }

        return $response;
    }

    /**
     * Obtiene datos de la caché
     *
     * @param string $key Clave de caché
     * @return array|null Datos en caché o null si no existe o ha expirado
     */
    private function getFromCache(string $key): ?array
    {
        // Verificar si existe en caché
        if (isset($this->cache[$key])) {
            $cacheItem = $this->cache[$key];

            // Verificar si ha expirado
            if ($cacheItem['expires'] > time()) {
                // Registrar hit de caché
                $this->logCacheHit($key);
                return $cacheItem['data'];
            }

            // Eliminar de caché si ha expirado
            unset($this->cache[$key]);
        }

        // Registrar miss de caché
        $this->logCacheMiss($key);
        return null;
    }

    /**
     * Guarda datos en la caché
     *
     * @param string $key Clave de caché
     * @param array $data Datos a guardar
     * @param int|null $expiration Tiempo de expiración personalizado (opcional)
     * @return void
     */
    private function saveToCache(string $key, array $data, ?int $expiration = null): void
    {
        $this->cache[$key] = [
            'data' => $data,
            'expires' => time() + ($expiration ?? $this->cacheExpiration)
        ];
    }

    /**
     * Registra un hit de caché
     *
     * @param string $key Clave de caché
     * @return void
     */
    private function logCacheHit(string $key): void
    {
        // Implementación simple de logging
        if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
            error_log("Cache HIT: $key");
        }
    }

    /**
     * Registra un miss de caché
     *
     * @param string $key Clave de caché
     * @return void
     */
    private function logCacheMiss(string $key): void
    {
        // Implementación simple de logging
        if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
            error_log("Cache MISS: $key");
        }
    }
}
