<?php
/**
 * Vista de error genérica
 *
 * Esta vista muestra un mensaje de error genérico
 *
 * @package app\Views\errors
 */

// Esta vista es incluida desde el controlador a través del ViewService
// No necesita incluir el layout directamente
?>

<div class="error-container">
    <?php
    // Usar el componente de encabezado de página
    $viewService->includeComponent('page_header', [
        'title' => htmlspecialchars($statusCode) . ' - ' . htmlspecialchars($title)
    ]);
    ?>

    <div class="error-message">
        <p><?= htmlspecialchars($message) ?></p>
    </div>

    <?php
    // Usar el componente de acciones de error
    $viewService->includeComponent('error_actions');
    ?>
</div>
