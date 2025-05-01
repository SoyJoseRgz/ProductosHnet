<?php
/**
 * Componente para mostrar acciones en páginas de error
 *
 * @param string $backUrl URL para el botón de volver (opcional)
 * @param string $backText Texto para el botón de volver (opcional)
 */

// Valores predeterminados
$backUrl = $backUrl ?? APP_URL;
$backText = $backText ?? 'Volver al inicio';
?>

<div class="error-actions">
    <a href="<?= $backUrl ?>" class="btn btn-primary"><?= htmlspecialchars($backText) ?></a>
</div>
