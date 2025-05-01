<?php
/**
 * Vista de error 404
 *
 * Esta vista muestra el mensaje de error cuando una página no se encuentra
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
        'title' => '404 - Página no encontrada'
    ]);
    ?>

    <div class="error-message">
        <p>Lo sentimos, la página que estás buscando no existe.</p>
    </div>

    <?php
    // Usar el componente de acciones de error
    $viewService->includeComponent('error_actions');
    ?>
</div>
