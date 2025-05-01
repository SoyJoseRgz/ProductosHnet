<?php
/**
 * Controlador para la API de WooCommerce
 *
 * Este controlador maneja las operaciones relacionadas con la API de WooCommerce
 *
 * @package app\Controllers
 */
namespace app\Controllers;

use app\Interfaces\WooCommerceRepositoryInterface;
use app\Services\ServiceFactory;

class WooCommerceController extends BaseController
{
    /**
     * Repositorio de WooCommerce
     *
     * @var WooCommerceRepositoryInterface
     */
    private $wooCommerceRepository;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Llamar al constructor padre para inicializar servicios comunes
        parent::__construct();

        // Inicializar servicios específicos
        $this->wooCommerceRepository = ServiceFactory::getWooCommerceRepository();
    }

    /**
     * Página principal de WooCommerce
     *
     * @return void
     */
    public function index(): void
    {
        // Probar la conexión con WooCommerce
        $connectionStatus = $this->wooCommerceRepository->testConnection();

        // Renderizar vista
        $this->render('woocommerce/index.php', [
            'title' => 'WooCommerce',
            'connectionStatus' => $connectionStatus,
            'extraCss' => ['woocommerce'],
            'extraJs' => ['woocommerce']
        ]);
    }


}
