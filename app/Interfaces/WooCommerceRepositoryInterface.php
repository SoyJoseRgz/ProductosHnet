<?php
/**
 * Interfaz para el repositorio de WooCommerce
 *
 * Define los métodos necesarios para interactuar con los datos de la API de WooCommerce
 *
 * @package app\Interfaces
 */
namespace app\Interfaces;

interface WooCommerceRepositoryInterface
{
    /**
     * Verifica la conexión con la API de WooCommerce
     *
     * @return bool True si la conexión es exitosa, false en caso contrario
     */
    public function testConnection(): bool;
    
    /**
     * Obtiene todos los productos de WooCommerce
     *
     * @param int $page Número de página para la paginación
     * @param int $perPage Cantidad de productos por página
     * @return array|null Lista de productos o null en caso de error
     */
    public function getProducts(int $page = 1, int $perPage = 10): ?array;
    
    /**
     * Obtiene un producto específico de WooCommerce
     *
     * @param int $productId ID del producto
     * @return array|null Datos del producto o null en caso de error
     */
    public function getProduct(int $productId): ?array;
    
    /**
     * Crea un nuevo producto en WooCommerce
     *
     * @param array $productData Datos del producto a crear
     * @return array|null Datos del producto creado o null en caso de error
     */
    public function createProduct(array $productData): ?array;
    
    /**
     * Actualiza un producto existente en WooCommerce
     *
     * @param int $productId ID del producto a actualizar
     * @param array $productData Datos actualizados del producto
     * @return array|null Datos del producto actualizado o null en caso de error
     */
    public function updateProduct(int $productId, array $productData): ?array;
    
    /**
     * Elimina un producto de WooCommerce
     *
     * @param int $productId ID del producto a eliminar
     * @param bool $force Si es true, elimina permanentemente el producto
     * @return bool True si se eliminó correctamente, false en caso contrario
     */
    public function deleteProduct(int $productId, bool $force = false): bool;
    
    /**
     * Convierte un producto de SYSCOM a formato WooCommerce
     *
     * @param array $syscomProduct Datos del producto de SYSCOM
     * @param float $profitPercentage Porcentaje de ganancia a aplicar al precio
     * @param float $exchangeRate Tipo de cambio para convertir a moneda local
     * @return array Datos del producto en formato WooCommerce
     */
    public function convertSyscomToWooProduct(array $syscomProduct, float $profitPercentage = 20.0, float $exchangeRate = 0.0): array;
    
    /**
     * Sube un lote de productos de SYSCOM a WooCommerce
     *
     * @param array $syscomProducts Lista de productos de SYSCOM
     * @param float $profitPercentage Porcentaje de ganancia a aplicar al precio
     * @param float $exchangeRate Tipo de cambio para convertir a moneda local
     * @return array Resultados de la operación (éxitos, errores)
     */
    public function batchUploadSyscomProducts(array $syscomProducts, float $profitPercentage = 20.0, float $exchangeRate = 0.0): array;
}
