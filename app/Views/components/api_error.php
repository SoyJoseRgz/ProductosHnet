<?php
/**
 * Componente para mostrar errores de API
 *
 * @param string $message Mensaje de error personalizado (opcional)
 * @param string $apiName Nombre de la API (opcional)
 */

// Valores predeterminados
$message = $message ?? 'Error al cargar los datos. Por favor, intente nuevamente más tarde.';
$apiName = $apiName ?? 'API';
?>

<div class="error-container">
    <p><?= htmlspecialchars($message) ?></p>

    <?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'development'): ?>
        <div class="debug-info">
            <h3>Información de depuración</h3>
            <p>Verifique las credenciales de la <?= htmlspecialchars($apiName) ?> en el archivo config/config.php</p>
            <?php if ($apiName === 'SYSCOM'): ?>
                <p>Asegúrese de que las constantes SYSCOM_CLIENT_ID y SYSCOM_CLIENT_SECRET estén configuradas correctamente.</p>
            <?php elseif ($apiName === 'WooCommerce'): ?>
                <p>Asegúrese de que las constantes WOOCOMMERCE_STORE_URL, WOOCOMMERCE_CONSUMER_KEY y WOOCOMMERCE_CONSUMER_SECRET estén configuradas correctamente.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
