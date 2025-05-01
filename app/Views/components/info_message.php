<?php
/**
 * Componente para mostrar mensajes informativos
 *
 * @param string $message Mensaje informativo a mostrar
 * @param string $class Clase CSS adicional (opcional)
 */

// Verificar que se ha proporcionado un mensaje
if (!isset($message) || empty($message)) {
    return;
}

// Clase CSS adicional
$additionalClass = isset($class) ? ' ' . $class : '';
?>

<div class="info-message<?= $additionalClass ?>">
    <?= htmlspecialchars($message) ?>
</div>
