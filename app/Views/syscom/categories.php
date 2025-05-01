<div class="syscom-container">
    <?php
    // Usar el componente de encabezado de página
    $viewService->includeComponent('page_header', [
        'title' => 'Categorías de Productos'
    ]);
    ?>

    <?php if (isset($categories) && $categories !== null): ?>
        <div class="categories-container">
            <?php if (is_array($categories) && !empty($categories)): ?>
                <div class="categories-grid">
                    <?php foreach ($categories as $category): ?>
                        <div class="category-card">
                            <div class="category-header">
                                <h3 class="category-name"><?= htmlspecialchars($category['nombre']) ?></h3>
                            </div>
                            <div class="category-footer">
                                <span class="category-info">ID: <?= $category['id'] ?></span>
                                <a href="<?= APP_URL ?>/syscom/productos/<?= $category['id'] ?>" class="btn btn-primary btn-sm btn-category">Ver productos</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-results">
                    <p>No se encontraron categorías.</p>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <?php
        // Usar el componente de error de API
        $viewService->includeComponent('api_error', [
            'message' => 'Error al cargar las categorías. Por favor, intente nuevamente más tarde.',
            'apiName' => 'SYSCOM'
        ]);
        ?>
    <?php endif; ?>
</div>
