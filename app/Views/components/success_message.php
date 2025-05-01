<?php
/**
 * Componente para mostrar mensajes de éxito
 *
 * @param string $message Mensaje de éxito a mostrar
 * @param string $class Clase CSS adicional (opcional)
 */

// Verificar que se ha proporcionado un mensaje
if (!isset($message) || empty($message)) {
    return;
}

// Clase CSS adicional
$additionalClass = isset($class) ? ' ' . $class : '';
?>

<div class="success-message<?= $additionalClass ?>">
    <?= htmlspecialchars($message) ?>
</div>
