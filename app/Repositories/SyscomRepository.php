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
    public function getProductsByCategory(int $categoryId, int $page = 1, string $order = "relevancia"): ?array
    {
        // Crear una clave de caché única basada en los parámetros
        $cacheKey = "products_category_{$categoryId}_page_{$page}_order_{$order}";

        // Verificar si existe en caché
        if ($cachedData = $this->getFromCache($cacheKey)) {
            return $cachedData;
        }

        // Preparar los parámetros para la petición
        $params = [
            'categoria' => $categoryId,
            'pagina' => $page,
            'orden' => $order
        ];

        // Realizar la petición al endpoint de productos
        $response = $this->apiService->request('GET', 'productos', $params);

        // Guardar en caché si la respuesta es válida
        if ($response !== null) {
            // Caché más corto para productos (5 minutos)
            $this->saveToCache($cacheKey, $response);
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getExchangeRate(): ?array
    {
        $cacheKey = 'exchange_rate';

        // Verificar si existe en caché
        if ($cachedData = $this->getFromCache($cacheKey)) {
            return $cachedData;
        }

        // Realizar la petición al endpoint de tipo de cambio
        $response = $this->apiService->request('GET', 'tipocambio');

        // Guardar en caché si la respuesta es válida
        if ($response !== null) {
            // Caché corto para tipo de cambio (5 minutos)
            $this->saveToCache($cacheKey, $response, 300);
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
        // Método simplificado para reducir logs innecesarios
    }

    /**
     * Registra un miss de caché
     *
     * @param string $key Clave de caché
     * @return void
     */
    private function logCacheMiss(string $key): void
    {
        // Método simplificado para reducir logs innecesarios
    }




}
