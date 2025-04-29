<div class="syscom-container">
    <h1>Productos SYSCOM</h1>
    
    <div class="search-container">
        <form action="<?= APP_URL ?>/syscom/search" method="GET" class="search-form">
            <div class="input-group">
                <input type="text" name="q" placeholder="Buscar productos..." class="form-control">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </div>
        </form>
    </div>
    
    <div class="categories-link">
        <a href="<?= APP_URL ?>/syscom/categories" class="btn btn-outline-primary">
            <i class="fas fa-tags"></i> Ver Categorías
        </a>
    </div>
    
    <?php if (isset($products) && $products !== null): ?>
        <div class="products-container">
            <?php if (isset($products['data']) && !empty($products['data'])): ?>
                <div class="products-grid">
                    <?php foreach ($products['data'] as $product): ?>
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
                    $totalPages = isset($products['total_paginas']) ? $products['total_paginas'] : 1;
                    $currentPage = $page;
                    ?>
                    
                    <div class="pagination">
                        <?php if ($currentPage > 1): ?>
                            <a href="<?= APP_URL ?>/syscom?page=<?= $currentPage - 1 ?>&limit=<?= $limit ?>" class="page-link">
                                <i class="fas fa-chevron-left"></i> Anterior
                            </a>
                        <?php endif; ?>
                        
                        <span class="page-info">Página <?= $currentPage ?> de <?= $totalPages ?></span>
                        
                        <?php if ($currentPage < $totalPages): ?>
                            <a href="<?= APP_URL ?>/syscom?page=<?= $currentPage + 1 ?>&limit=<?= $limit ?>" class="page-link">
                                Siguiente <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="no-results">
                    <p>No se encontraron productos.</p>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="error-container">
            <p>Error al cargar los productos. Por favor, intente nuevamente más tarde.</p>
        </div>
    <?php endif; ?>
</div>
