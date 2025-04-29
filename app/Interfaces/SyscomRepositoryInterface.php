<?php
/**
 * Interfaz para el repositorio de SYSCOM
 *
 * Define los métodos necesarios para interactuar con los datos de la API de SYSCOM
 *
 * @package app\Interfaces
 */
namespace app\Interfaces;

interface SyscomRepositoryInterface
{
    /**
     * Obtiene un listado de productos
     *
     * @param int $page Número de página
     * @param int $limit Límite de resultados por página
     * @param array $filters Filtros adicionales
     * @return array|null Listado de productos o null en caso de error
     */
    public function getProducts(int $page = 1, int $limit = 10, array $filters = []): ?array;
    
    /**
     * Busca productos por término de búsqueda
     *
     * @param string $query Término de búsqueda
     * @param int $page Número de página
     * @param int $limit Límite de resultados por página
     * @return array|null Resultados de la búsqueda o null en caso de error
     */
    public function searchProducts(string $query, int $page = 1, int $limit = 10): ?array;
    
    /**
     * Obtiene información detallada de un producto
     *
     * @param int $productId ID del producto
     * @return array|null Información del producto o null en caso de error
     */
    public function getProductDetails(int $productId): ?array;
    
    /**
     * Obtiene las categorías disponibles
     *
     * @return array|null Listado de categorías o null en caso de error
     */
    public function getCategories(): ?array;
    
    /**
     * Obtiene productos por categoría
     *
     * @param int $categoryId ID de la categoría
     * @param int $page Número de página
     * @param int $limit Límite de resultados por página
     * @return array|null Listado de productos o null en caso de error
     */
    public function getProductsByCategory(int $categoryId, int $page = 1, int $limit = 10): ?array;
}
