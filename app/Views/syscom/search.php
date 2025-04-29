<div class="syscom-container">
    <h1>Búsqueda Avanzada de Productos</h1>

    <div class="search-container">
        <form action="<?= APP_URL ?>/syscom/search" method="GET" class="search-form">
            <div class="search-row">
                <div class="input-group main-search">
                    <input type="text" name="q" value="<?= htmlspecialchars($query) ?>" placeholder="Buscar productos por nombre, modelo, marca..." class="form-control">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </div>

            <div class="search-filters">
                <div class="filter-group">
                    <label for="category">Categoría:</label>
                    <select name="category" id="category" class="form-control">
                        <option value="">Todas las categorías</option>
                        <?php if (isset($categories) && is_array($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" <?= isset($_GET['category']) && $_GET['category'] == $category['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="sort">Ordenar por:</label>
                    <select name="sort" id="sort" class="form-control">
                        <option value="relevance" <?= isset($_GET['sort']) && $_GET['sort'] == 'relevance' ? 'selected' : '' ?>>Relevancia</option>
                        <option value="price_asc" <?= isset($_GET['sort']) && $_GET['sort'] == 'price_asc' ? 'selected' : '' ?>>Precio: Menor a Mayor</option>
                        <option value="price_desc" <?= isset($_GET['sort']) && $_GET['sort'] == 'price_desc' ? 'selected' : '' ?>>Precio: Mayor a Menor</option>
                        <option value="name_asc" <?= isset($_GET['sort']) && $_GET['sort'] == 'name_asc' ? 'selected' : '' ?>>Nombre: A-Z</option>
                        <option value="name_desc" <?= isset($_GET['sort']) && $_GET['sort'] == 'name_desc' ? 'selected' : '' ?>>Nombre: Z-A</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="limit">Resultados por página:</label>
                    <select name="limit" id="limit" class="form-control">
                        <option value="12" <?= $limit == 12 ? 'selected' : '' ?>>12</option>
                        <option value="24" <?= $limit == 24 ? 'selected' : '' ?>>24</option>
                        <option value="48" <?= $limit == 48 ? 'selected' : '' ?>>48</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <div class="navigation-links">
        <a href="<?= APP_URL ?>/syscom" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Productos
        </a>
        <a href="<?= APP_URL ?>/syscom/categories" class="btn btn-outline-primary">
            <i class="fas fa-tags"></i> Ver Categorías
        </a>
    </div>

    <?php if (!empty($query)): ?>
        <div class="search-results-header">
            <h2>Resultados para: "<?= htmlspecialchars($query) ?>"</h2>
        </div>

        <?php if (isset($results) && $results !== null): ?>
            <div class="products-container">
                <?php if (isset($results['data']) && !empty($results['data'])): ?>
                    <div class="products-grid">
                        <?php foreach ($results['data'] as $product): ?>
                            <div class="product-card">
                                <div class="product-image">
                                    <?php if (isset($product['imagenes'][0]['imagen_mediana'])): ?>
                                        <img src="<?= $product['imagenes'][0]['imagen_mediana'] ?>" alt="<?= htmlspecialchars($product['nombre']) ?>">
                                    <?php else: ?>
                                        <div class="no-image">Sin imagen</div>
                                    <?php endif; ?>
                                </div>
                                <div class="product-info">
                                    <h3 class="product-name"><?= htmlspecialchars($product['nombre']) ?></h3>
                                    <p class="product-sku">SKU: <?= htmlspecialchars($product['producto_id']) ?></p>
                                    <p class="product-price">
                                        <?php if (isset($product['precios']['precio_1'])): ?>
                                            $<?= number_format($product['precios']['precio_1'], 2) ?> MXN
                                        <?php else: ?>
                                            Precio no disponible
                                        <?php endif; ?>
                                    </p>
                                    <div class="product-actions">
                                        <a href="<?= APP_URL ?>/syscom/product?id=<?= $product['producto_id'] ?>" class="btn btn-primary">
                                            <i class="fas fa-info-circle"></i> Detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Paginación -->
                    <div class="pagination-container">
                        <?php
                        $totalPages = isset($results['total_paginas']) ? $results['total_paginas'] : 1;
                        $currentPage = $page;
                        ?>

                        <div class="pagination">
                            <?php if ($currentPage > 1): ?>
                                <a href="<?= APP_URL ?>/syscom/search?q=<?= urlencode($query) ?>&page=<?= $currentPage - 1 ?>&limit=<?= $limit ?>" class="page-link">
                                    <i class="fas fa-chevron-left"></i> Anterior
                                </a>
                            <?php endif; ?>

                            <span class="page-info">Página <?= $currentPage ?> de <?= $totalPages ?></span>

                            <?php if ($currentPage < $totalPages): ?>
                                <a href="<?= APP_URL ?>/syscom/search?q=<?= urlencode($query) ?>&page=<?= $currentPage + 1 ?>&limit=<?= $limit ?>" class="page-link">
                                    Siguiente <i class="fas fa-chevron-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="no-results">
                        <p>No se encontraron productos que coincidan con su búsqueda.</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="error-container">
                <p>Error al realizar la búsqueda. Por favor, intente nuevamente más tarde.</p>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="search-instructions">
            <p>Ingrese un término de búsqueda para encontrar productos.</p>
        </div>
    <?php endif; ?>
</div>
