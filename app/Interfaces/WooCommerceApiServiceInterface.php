<?php
/**
 * Interfaz para el servicio de API de WooCommerce
 *
 * Define los métodos necesarios para interactuar con la API de WooCommerce
 *
 * @package app\Interfaces
 */
namespace app\Interfaces;

interface WooCommerceApiServiceInterface
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
     * @param array $params Parámetros adicionales para la petición
     * @return array|null Lista de productos o null en caso de error
     */
    public function getProducts(array $params = []): ?array;

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
     * Realiza una petición a la API de WooCommerce
     *
     * @param string $method Método HTTP (GET, POST, PUT, DELETE)
     * @param string $endpoint Endpoint de la API
     * @param array $params Parámetros para la petición
     * @return array|null Respuesta de la API o null en caso de error
     */
    public function request(string $method, string $endpoint, array $params = []): ?array;
}
