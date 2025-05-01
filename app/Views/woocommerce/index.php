<?php
/**
 * Vista principal de WooCommerce
 *
 * Esta vista muestra la página principal de la integración con WooCommerce
 *
 * @package app\Views\woocommerce
 */

// Verificar si se ha proporcionado el estado de conexión
if (!isset($connectionStatus)) {
    echo "<div class='error-message'>Error: No se ha proporcionado el estado de conexión</div>";
    return;
}
?>

<div class="woocommerce-container">
    <?php
    // Usar el componente de encabezado de página
    $viewService->includeComponent('page_header', [
        'title' => 'Integración con WooCommerce'
    ]);
    ?>

    <?php if ($connectionStatus): ?>
        <?php
        // Usar el componente de mensaje de éxito
        $viewService->includeComponent('success_message', [
            'message' => 'Conexión establecida correctamente con WooCommerce'
        ]);
        ?>
    <?php else: ?>
        <?php
        // Usar el componente de error de API
        $viewService->includeComponent('api_error', [
            'message' => 'Error al conectar con WooCommerce. Verifique las credenciales y la URL de la tienda en la configuración.',
            'apiName' => 'WooCommerce'
        ]);
        ?>
    <?php endif; ?>

    <?php if ($connectionStatus): ?>

        <div class="woocommerce-info">
            <h3><i class="info-icon"></i> Información de la Integración</h3>
            <ul>
                <li>
                    <strong>URL de la tienda:</strong> <?= htmlspecialchars(WOOCOMMERCE_STORE_URL) ?>
                </li>
                <li>
                    <strong>API Version:</strong> WooCommerce REST API v3
                </li>
            </ul>
        </div>
    <?php endif; ?>
</div>
