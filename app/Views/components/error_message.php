<?php
/**
 * Componente para mostrar mensajes de error
 *
 * @param string $message Mensaje de error a mostrar
 * @param string $class Clase CSS adicional (opcional)
 */

// Verificar que se ha proporcionado un mensaje
if (!isset($message) || empty($message)) {
    return;
}

// Clase CSS adicional
$additionalClass = isset($class) ? ' ' . $class : '';
?>

<div class="error-message<?= $additionalClass ?>">
    <?= htmlspecialchars($message) ?>
</div>
