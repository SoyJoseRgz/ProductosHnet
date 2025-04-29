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
    <h2><?= htmlspecialchars($statusCode) ?> - <?= htmlspecialchars($title) ?></h2>
    <p><?= htmlspecialchars($message) ?></p>
    <a href="<?= APP_URL ?>" class="btn">Volver al inicio</a>
</div>
