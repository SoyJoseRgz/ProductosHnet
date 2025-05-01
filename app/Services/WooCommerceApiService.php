<?php
/**
 * Servicio para interactuar con la API de WooCommerce
 *
 * Este servicio maneja la autenticación y las peticiones a la API de WooCommerce
 *
 * @package app\Services
 */
namespace app\Services;

use app\Interfaces\WooCommerceApiServiceInterface;

class WooCommerceApiService extends BaseApiService implements WooCommerceApiServiceInterface
{
    /**
     * URL de la tienda WooCommerce
     *
     * @var string
     */
    private $storeUrl;

    /**
     * Clave de consumidor para la API
     *
     * @var string
     */
    private $consumerKey;

    /**
     * Secreto de consumidor para la API
     *
     * @var string
     */
    private $consumerSecret;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        // Cargar configuración desde constantes definidas en config.php
        $this->storeUrl = defined('WOOCOMMERCE_STORE_URL') ? WOOCOMMERCE_STORE_URL : 'http://mi-tienda.local/';
        $this->consumerKey = defined('WOOCOMMERCE_CONSUMER_KEY') ? WOOCOMMERCE_CONSUMER_KEY : 'ck_9843139c9f9b7224b4af786289477a9c546cd7c5';
        $this->consumerSecret = defined('WOOCOMMERCE_CONSUMER_SECRET') ? WOOCOMMERCE_CONSUMER_SECRET : 'cs_86743d39590d38fc5449904f1a594d764fe391f6';

        // Construir el endpoint base
        $this->apiBaseUrl = $this->storeUrl . 'wp-json/wc/v3';
    }

    /**
     * {@inheritdoc}
     */
    public function testConnection(): bool
    {
        $this->logger->info('Probando conexión con WooCommerce API');

        // Intentar obtener información básica de la API
        $response = $this->request('GET', '');

        // Si la respuesta no es null, la conexión fue exitosa
        return $response !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function getProducts(array $params = []): ?array
    {
        $this->logger->info('Obteniendo productos de WooCommerce', ['params' => $params]);
        return $this->request('GET', 'products', $params);
    }

    /**
     * {@inheritdoc}
     */
    public function getProduct(int $productId): ?array
    {
        $this->logger->info('Obteniendo producto de WooCommerce', ['product_id' => $productId]);
        return $this->request('GET', "products/{$productId}");
    }

    /**
     * {@inheritdoc}
     */
    public function createProduct(array $productData): ?array
    {
        $this->logger->info('Creando producto en WooCommerce', ['product_data' => $productData]);
        return $this->request('POST', 'products', $productData);
    }

    /**
     * {@inheritdoc}
     */
    public function updateProduct(int $productId, array $productData): ?array
    {
        $this->logger->info('Actualizando producto en WooCommerce', [
            'product_id' => $productId,
            'product_data' => $productData
        ]);
        return $this->request('PUT', "products/{$productId}", $productData);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteProduct(int $productId, bool $force = false): bool
    {
        $this->logger->info('Eliminando producto de WooCommerce', [
            'product_id' => $productId,
            'force' => $force
        ]);

        $params = ['force' => $force];
        $response = $this->request('DELETE', "products/{$productId}", $params);

        return $response !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function request(string $method, string $endpoint, array $params = []): ?array
    {
        // Construir la URL completa
        $url = $this->apiBaseUrl . '/' . ltrim($endpoint, '/');

        // Configurar headers para autenticación básica
        $headers = [
            'Content-Type: application/json'
        ];

        // Usar el método de la clase base para hacer la petición HTTP
        // Pasamos un parámetro adicional para indicar que no queremos codificar los parámetros como JSON en DELETE
        $jsonEncode = strtoupper($method) !== 'DELETE';

        // Configurar opciones adicionales para cURL
        $curlOptions = [
            CURLOPT_USERPWD => $this->consumerKey . ':' . $this->consumerSecret
        ];

        // Hacer la petición HTTP usando el método de la clase base
        return $this->makeHttpRequest($method, $url, $params, $headers, $jsonEncode, $curlOptions);
    }
}
