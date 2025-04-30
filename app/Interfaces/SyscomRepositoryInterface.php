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
     * Obtiene las categorías disponibles
     *
     * @return array|null Listado de categorías o null en caso de error
     */
    public function getCategories(): ?array;

    /**
     * Obtiene los productos de una categoría específica
     *
     * @param int $categoryId ID de la categoría
     * @param int $page Número de página para la paginación
     * @param string $order Orden de los resultados (default: "relevancia")
     * @return array|null Listado de productos o null en caso de error
     */
    public function getProductsByCategory(int $categoryId, int $page = 1, string $order = "relevancia"): ?array;

    /**
     * Obtiene el tipo de cambio actual
     *
     * @return array|null Información del tipo de cambio o null en caso de error
     */
    public function getExchangeRate(): ?array;
}
