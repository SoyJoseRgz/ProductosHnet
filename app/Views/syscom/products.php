<div class="syscom-container">
    <?php
    // Usar el componente de encabezado de página
    $viewService->includeComponent('page_header', [
        'title' => 'Productos de ' . htmlspecialchars($categoryName),
        'backUrl' => APP_URL . '/syscom/categories',
        'backText' => 'Volver a Categorías'
    ]);
    ?>

    <?php if (isset($products) && $products !== null): ?>
        <div class="products-container">
            <?php if (isset($products['productos']) && is_array($products['productos']) && !empty($products['productos'])): ?>
                <!-- Información de paginación -->
                <div class="pagination-info">
                    <p>
                        Mostrando página <?= $currentPage ?> de <?= $products['paginas'] ?? 1 ?>
                        (<?= $products['total'] ?? 0 ?> productos en total)
                    </p>
                </div>

                <!-- Opciones de ordenamiento -->
                <div class="order-options">
                    <label for="order-select">Ordenar por:</label>
                    <select id="order-select" class="order-select" onchange="changeOrder(this.value)">
                        <option value="relevancia" <?= $currentOrder === 'relevancia' ? 'selected' : '' ?>>Relevancia</option>
                        <option value="precio:asc" <?= $currentOrder === 'precio:asc' ? 'selected' : '' ?>>Precio: menor a mayor</option>
                        <option value="precio:desc" <?= $currentOrder === 'precio:desc' ? 'selected' : '' ?>>Precio: mayor a menor</option>
                        <option value="modelo:asc" <?= $currentOrder === 'modelo:asc' ? 'selected' : '' ?>>Modelo: A-Z</option>
                        <option value="modelo:desc" <?= $currentOrder === 'modelo:desc' ? 'selected' : '' ?>>Modelo: Z-A</option>
                        <option value="marca:asc" <?= $currentOrder === 'marca:asc' ? 'selected' : '' ?>>Marca: A-Z</option>
                        <option value="marca:desc" <?= $currentOrder === 'marca:desc' ? 'selected' : '' ?>>Marca: Z-A</option>
                        <option value="topseller" <?= $currentOrder === 'topseller' ? 'selected' : '' ?>>Más vendidos</option>
                    </select>
                </div>

                <!-- Listado de productos -->
                <div class="products-grid">
                    <?php foreach ($products['productos'] as $product): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <?php if (!empty($product['img_portada'])): ?>
                                    <img src="<?= htmlspecialchars($product['img_portada']) ?>" alt="<?= htmlspecialchars($product['modelo']) ?>">
                                <?php else: ?>
                                    <div class="no-image">Sin imagen</div>
                                <?php endif; ?>
                            </div>
                            <div class="product-header">
                                <h3 class="product-name"><?= htmlspecialchars($product['titulo'] ?? $product['modelo']) ?></h3>
                                <?php if (!empty($product['marca'])): ?>
                                    <div class="product-brand"><?= htmlspecialchars($product['marca']) ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="product-details">
                                <div class="product-prices">
                                    <?php if (isset($product['precios'])): ?>
                                        <?php
                                        // Obtener el precio base y aplicar descuento del 4%
                                        $precioBase = null;

                                        // Buscar el precio en orden de prioridad
                                        if (isset($product['precios']['precio_descuento']) && $product['precios']['precio_descuento'] > 0) {
                                            $precioBase = $product['precios']['precio_descuento'];
                                        } elseif (isset($product['precios']['precio_1']) && $product['precios']['precio_1'] > 0) {
                                            $precioBase = $product['precios']['precio_1'];
                                        } elseif (isset($product['precios']['precio_especial']) && $product['precios']['precio_especial'] > 0) {
                                            $precioBase = $product['precios']['precio_especial'];
                                        }

                                        if ($precioBase !== null) {
                                            $precioConDescuento = $precioBase * 0.96; // 4% de descuento
                                        ?>
                                            <div class="product-price price-syscom">
                                                <span class="price-label">SYSCOM:</span>
                                                <span class="price-value">$<?= number_format($precioConDescuento, 2) ?></span>
                                                <span class="price-currency">USD + IVA</span>
                                            </div>
                                        <?php } else { ?>
                                            <div class="product-price">
                                                <span class="price-label">Precio:</span>
                                                <span class="price-value">No disponible</span>
                                            </div>
                                        <?php } ?>
                                    <?php else: ?>
                                        <div class="product-price">
                                            <span class="price-label">Precio:</span>
                                            <span class="price-value">No disponible</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="product-model">Modelo: <?= htmlspecialchars($product['modelo']) ?></div>
                                <div class="product-stock">Stock: <?= $product['total_existencia'] ?? 0 ?></div>
                            </div>
                            <?php if (!empty($product['link'])): ?>
                                <div class="product-footer">
                                    <a href="<?= htmlspecialchars($product['link']) ?>" target="_blank" class="btn btn-primary btn-sm">Ver producto</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Paginación -->
                <?php if (isset($products['paginas']) && $products['paginas'] > 1): ?>
                    <div class="pagination">
                        <?php if ($currentPage > 1): ?>
                            <a href="<?= APP_URL ?>/syscom/productos/<?= $categoryId ?>?page=<?= $currentPage - 1 ?>&order=<?= urlencode($currentOrder) ?>" class="pagination-link">Anterior</a>
                        <?php endif; ?>

                        <?php
                        // Mostrar un número limitado de páginas
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($products['paginas'], $currentPage + 2);

                        // Asegurar que siempre mostramos 5 páginas si hay suficientes
                        if ($endPage - $startPage < 4 && $products['paginas'] >= 5) {
                            if ($startPage == 1) {
                                $endPage = min($products['paginas'], 5);
                            } elseif ($endPage == $products['paginas']) {
                                $startPage = max(1, $products['paginas'] - 4);
                            }
                        }

                        // Mostrar primera página si no está en el rango
                        if ($startPage > 1) {
                            echo '<a href="' . APP_URL . '/syscom/productos/' . $categoryId . '?page=1&order=' . urlencode($currentOrder) . '" class="pagination-link">1</a>';
                            if ($startPage > 2) {
                                echo '<span class="pagination-ellipsis">...</span>';
                            }
                        }

                        // Mostrar páginas en el rango
                        for ($i = $startPage; $i <= $endPage; $i++) {
                            $activeClass = $i == $currentPage ? 'active' : '';
                            echo '<a href="' . APP_URL . '/syscom/productos/' . $categoryId . '?page=' . $i . '&order=' . urlencode($currentOrder) . '" class="pagination-link ' . $activeClass . '">' . $i . '</a>';
                        }

                        // Mostrar última página si no está en el rango
                        if ($endPage < $products['paginas']) {
                            if ($endPage < $products['paginas'] - 1) {
                                echo '<span class="pagination-ellipsis">...</span>';
                            }
                            echo '<a href="' . APP_URL . '/syscom/productos/' . $categoryId . '?page=' . $products['paginas'] . '&order=' . urlencode($currentOrder) . '" class="pagination-link">' . $products['paginas'] . '</a>';
                        }
                        ?>

                        <?php if ($currentPage < $products['paginas']): ?>
                            <a href="<?= APP_URL ?>/syscom/productos/<?= $categoryId ?>?page=<?= $currentPage + 1 ?>&order=<?= urlencode($currentOrder) ?>" class="pagination-link">Siguiente</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="no-results">
                    <p>No se encontraron productos en esta categoría.</p>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <?php
        // Usar el componente de error de API
        $viewService->includeComponent('api_error', [
            'message' => 'Error al cargar los productos. Por favor, intente nuevamente más tarde.',
            'apiName' => 'SYSCOM'
        ]);
        ?>
    <?php endif; ?>
</div>

<script>
    function changeOrder(order) {
        window.location.href = '<?= APP_URL ?>/syscom/productos/<?= $categoryId ?>?page=<?= $currentPage ?>&order=' + encodeURIComponent(order);
    }
</script>
