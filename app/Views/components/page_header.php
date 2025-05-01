<?php
/**
 * Componente para mostrar el encabezado de una página
 *
 * @param string $title Título de la página
 * @param string $backUrl URL para el botón de volver (opcional)
 * @param string $backText Texto para el botón de volver (opcional)
 */

// Verificar que se ha proporcionado un título
if (!isset($title) || empty($title)) {
    return;
}

// Valores predeterminados
$backUrl = $backUrl ?? '';
$backText = $backText ?? 'Volver';
?>

<h1 class="page-title"><?= htmlspecialchars($title) ?></h1>

<?php if (!empty($backUrl)): ?>
    <div class="navigation-links">
        <a href="<?= $backUrl ?>" class="btn btn-secondary btn-sm">
            <i class="back-icon"></i> <?= htmlspecialchars($backText) ?>
        </a>
    </div>
<?php endif; ?>
