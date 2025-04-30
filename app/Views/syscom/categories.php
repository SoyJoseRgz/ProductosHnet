<div class="syscom-container">
    <h1 class="page-title">Categorías de Productos</h1>

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
        <div class="error-container">
            <p>Error al cargar las categorías. Por favor, intente nuevamente más tarde.</p>

            <?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'development'): ?>
                <div class="debug-info">
                    <h3>Información de depuración</h3>
                    <p>Verifique las credenciales de la API en el archivo config/config.php</p>
                    <p>Asegúrese de que las constantes SYSCOM_CLIENT_ID y SYSCOM_CLIENT_SECRET estén configuradas correctamente.</p>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
