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
    <h2>404 - Página no encontrada</h2>
    <p>Lo sentimos, la página que estás buscando no existe.</p>
    <a href="<?= APP_URL ?>" class="btn">Volver al inicio</a>
</div>
