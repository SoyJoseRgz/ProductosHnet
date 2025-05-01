<?php
/**
 * Repositorio para la API de WooCommerce
 *
 * Este repositorio maneja la interacción con la API de WooCommerce y proporciona caché para mejorar el rendimiento
 *
 * @package app\Repositories
 */
namespace app\Repositories;

use app\Interfaces\WooCommerceRepositoryInterface;
use app\Interfaces\WooCommerceApiServiceInterface;
use app\Interfaces\SyscomRepositoryInterface;
use app\Services\ServiceFactory;

class WooCommerceRepository extends BaseRepository implements WooCommerceRepositoryInterface
{
    /**
     * Servicio de API de WooCommerce
     *
     * @var WooCommerceApiServiceInterface
     */
    private $apiService;

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
        parent::__construct();
        $this->apiService = ServiceFactory::getWooCommerceApiService();
        $this->syscomRepository = ServiceFactory::getSyscomRepository();
    }

    /**
     * {@inheritdoc}
     */
    public function testConnection(): bool
    {
        return $this->apiService->testConnection();
    }

    /**
     * {@inheritdoc}
     */
    public function getProducts(int $page = 1, int $perPage = 10): ?array
    {
        $cacheKey = "woo_products_page_{$page}_per_{$perPage}";

        // Preparar los parámetros para la petición
        $params = [
            'page' => $page,
            'per_page' => $perPage
        ];

        // Usar el método executeWithCache para simplificar el código
        return $this->executeWithCache(
            $cacheKey,
            function() use ($params) {
                // Realizar la petición
                return $this->apiService->getProducts($params);
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getProduct(int $productId): ?array
    {
        $cacheKey = "woo_product_{$productId}";

        // Usar el método executeWithCache para simplificar el código
        return $this->executeWithCache(
            $cacheKey,
            function() use ($productId) {
                // Realizar la petición
                return $this->apiService->getProduct($productId);
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function createProduct(array $productData): ?array
    {
        // No cachear operaciones de escritura
        return $this->apiService->createProduct($productData);
    }

    /**
     * {@inheritdoc}
     */
    public function updateProduct(int $productId, array $productData): ?array
    {
        // No cachear operaciones de escritura
        $response = $this->apiService->updateProduct($productId, $productData);

        // Si la actualización fue exitosa, actualizar la caché
        if ($response !== null) {
            $cacheKey = "woo_product_{$productId}";
            $this->saveToCache($cacheKey, $response);
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteProduct(int $productId, bool $force = false): bool
    {
        // No cachear operaciones de escritura
        $result = $this->apiService->deleteProduct($productId, $force);

        // Si la eliminación fue exitosa, eliminar de la caché
        if ($result) {
            $cacheKey = "woo_product_{$productId}";
            unset($this->cache[$cacheKey]);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function convertSyscomToWooProduct(array $syscomProduct, float $profitPercentage = 20.0, float $exchangeRate = 0.0): array
    {
        // Si no se proporciona tipo de cambio, intentar obtenerlo
        if ($exchangeRate <= 0) {
            $exchangeRateData = $this->syscomRepository->getExchangeRate();
            if ($exchangeRateData && isset($exchangeRateData['1_mes'])) {
                $exchangeRate = (float)$exchangeRateData['1_mes'];
            } else {
                // Valor predeterminado si no se puede obtener
                $exchangeRate = 17.5;
                $this->logger->warning('No se pudo obtener el tipo de cambio, usando valor predeterminado', [
                    'default_rate' => $exchangeRate
                ]);
            }
        }

        // Calcular precios
        $basePrice = isset($syscomProduct['precios']['precio_descuento'])
            ? (float)$syscomProduct['precios']['precio_descuento']
            : (float)$syscomProduct['precios']['precio_lista'];

        // Aplicar porcentaje de ganancia
        $finalPrice = $basePrice * (1 + ($profitPercentage / 100));

        // Calcular precio en moneda local (MXN)
        $localPrice = $finalPrice * $exchangeRate;

        // Preparar imágenes
        $images = [];
        if (isset($syscomProduct['imagenes']) && is_array($syscomProduct['imagenes'])) {
            foreach ($syscomProduct['imagenes'] as $image) {
                if (isset($image['imagen_grande'])) {
                    $images[] = [
                        'src' => $image['imagen_grande']
                    ];
                }
            }
        }

        // Preparar categorías
        $categories = [];
        if (isset($syscomProduct['categorias']) && is_array($syscomProduct['categorias'])) {
            foreach ($syscomProduct['categorias'] as $category) {
                $categories[] = [
                    'name' => $category['nombre']
                ];
            }
        }

        // Construir descripción
        $description = '';
        if (isset($syscomProduct['descripcion'])) {
            $description .= $syscomProduct['descripcion'];
        }

        if (isset($syscomProduct['caracteristicas']) && is_array($syscomProduct['caracteristicas'])) {
            $description .= "\n\n<h3>Características</h3>\n<ul>";
            foreach ($syscomProduct['caracteristicas'] as $feature) {
                $description .= "\n<li><strong>{$feature['nombre']}:</strong> {$feature['valor']}</li>";
            }
            $description .= "\n</ul>";
        }

        // Construir datos del producto para WooCommerce
        $wooProduct = [
            'name' => $syscomProduct['modelo'] ?? 'Producto SYSCOM',
            'type' => 'simple',
            'regular_price' => number_format($finalPrice, 2, '.', ''),
            'description' => $description,
            'short_description' => $syscomProduct['titulo'] ?? '',
            'categories' => $categories,
            'images' => $images,
            'manage_stock' => true,
            'stock_quantity' => $syscomProduct['existencia'] ?? 0,
            'sku' => $syscomProduct['clave'] ?? '',
            'meta_data' => [
                [
                    'key' => '_syscom_id',
                    'value' => $syscomProduct['producto_id'] ?? ''
                ],
                [
                    'key' => '_syscom_price_mxn',
                    'value' => number_format($localPrice, 2, '.', '')
                ],
                [
                    'key' => '_syscom_original_price',
                    'value' => number_format($basePrice, 2, '.', '')
                ],
                [
                    'key' => '_syscom_profit_percentage',
                    'value' => $profitPercentage
                ],
                [
                    'key' => '_syscom_exchange_rate',
                    'value' => $exchangeRate
                ]
            ]
        ];

        // Agregar marca como atributo
        if (isset($syscomProduct['marca'])) {
            $wooProduct['attributes'] = [
                [
                    'name' => 'Marca',
                    'position' => 0,
                    'visible' => true,
                    'variation' => false,
                    'options' => [$syscomProduct['marca']]
                ]
            ];
        }

        return $wooProduct;
    }

    /**
     * {@inheritdoc}
     */
    public function batchUploadSyscomProducts(array $syscomProducts, float $profitPercentage = 20.0, float $exchangeRate = 0.0): array
    {
        $results = [
            'success' => [],
            'errors' => []
        ];

        // Procesar cada producto
        foreach ($syscomProducts as $syscomProduct) {
            try {
                // Convertir el producto al formato de WooCommerce
                $wooProduct = $this->convertSyscomToWooProduct($syscomProduct, $profitPercentage, $exchangeRate);

                // Crear el producto en WooCommerce
                $response = $this->createProduct($wooProduct);

                if ($response !== null) {
                    $results['success'][] = [
                        'syscom_id' => $syscomProduct['producto_id'] ?? 'unknown',
                        'woo_id' => $response['id'] ?? 'unknown',
                        'name' => $response['name'] ?? 'unknown'
                    ];
                } else {
                    $results['errors'][] = [
                        'syscom_id' => $syscomProduct['producto_id'] ?? 'unknown',
                        'name' => $syscomProduct['modelo'] ?? 'unknown',
                        'error' => 'Error al crear el producto en WooCommerce'
                    ];
                }
            } catch (\Exception $e) {
                $this->logger->error('Error al procesar producto para WooCommerce', [
                    'syscom_id' => $syscomProduct['producto_id'] ?? 'unknown',
                    'error' => $e->getMessage()
                ]);

                $results['errors'][] = [
                    'syscom_id' => $syscomProduct['producto_id'] ?? 'unknown',
                    'name' => $syscomProduct['modelo'] ?? 'unknown',
                    'error' => $e->getMessage()
                ];
            }
        }

        return $results;
    }

}
