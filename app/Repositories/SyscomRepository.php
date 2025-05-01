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

class SyscomRepository extends BaseRepository implements SyscomRepositoryInterface
{
    /**
     * Servicio de API de SYSCOM
     *
     * @var SyscomApiServiceInterface
     */
    private $apiService;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->apiService = ServiceFactory::getSyscomApiService();
    }

    /**
     * {@inheritdoc}
     */
    public function getCategories(): ?array
    {
        $cacheKey = 'categories';

        // Usar el método executeWithCache para simplificar el código
        return $this->executeWithCache(
            $cacheKey,
            function() {
                // Realizar la petición al endpoint de categorías
                // Según la documentación de SYSCOM: https://developers.syscom.mx/docs
                return $this->apiService->request('GET', 'categorias');
            },
            3600 // Caché más largo para categorías (1 hora)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getProductsByCategory(int $categoryId, int $page = 1, string $order = "relevancia"): ?array
    {
        // Crear una clave de caché única basada en los parámetros
        $cacheKey = "products_category_{$categoryId}_page_{$page}_order_{$order}";

        // Preparar los parámetros para la petición
        $params = [
            'categoria' => $categoryId,
            'pagina' => $page,
            'orden' => $order
        ];

        // Usar el método executeWithCache para simplificar el código
        return $this->executeWithCache(
            $cacheKey,
            function() use ($params) {
                // Realizar la petición al endpoint de productos
                return $this->apiService->request('GET', 'productos', $params);
            },
            300 // Caché corto para productos (5 minutos)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getExchangeRate(): ?array
    {
        $cacheKey = 'exchange_rate';

        // Usar el método executeWithCache para simplificar el código
        return $this->executeWithCache(
            $cacheKey,
            function() {
                // Realizar la petición al endpoint de tipo de cambio
                return $this->apiService->request('GET', 'tipocambio');
            },
            300 // Caché corto para tipo de cambio (5 minutos)
        );
    }



}
