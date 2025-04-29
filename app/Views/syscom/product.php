<div class="syscom-container">
    <div class="navigation-links">
        <a href="javascript:history.back()" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <?php if (isset($product) && $product !== null): ?>
        <div class="product-detail">
            <h1 class="product-title"><?= htmlspecialchars($product['nombre']) ?></h1>

            <div class="product-detail-grid">
                <div class="product-images">
                    <?php if (isset($product['imagenes']) && !empty($product['imagenes'])): ?>
                        <div class="main-image">
                            <img src="<?= $product['imagenes'][0]['imagen_grande'] ?>" alt="<?= htmlspecialchars($product['nombre']) ?>">
                        </div>

                        <?php if (count($product['imagenes']) > 1): ?>
                            <div class="thumbnail-images">
                                <?php foreach ($product['imagenes'] as $index => $image): ?>
                                    <div class="thumbnail">
                                        <img src="<?= $image['imagen_chica'] ?>" alt="Imagen <?= $index + 1 ?>">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="no-image">Sin imagen disponible</div>
                    <?php endif; ?>
                </div>

                <div class="product-info">
                    <div class="product-meta">
                        <p class="product-sku"><strong>SKU:</strong> <?= htmlspecialchars($product['producto_id']) ?></p>
                        <p class="product-model"><strong>Modelo:</strong> <?= htmlspecialchars($product['modelo'] ?? 'No disponible') ?></p>
                        <p class="product-brand"><strong>Marca:</strong> <?= htmlspecialchars($product['marca'] ?? 'No disponible') ?></p>
                    </div>

                    <div class="product-pricing">
                        <?php if (isset($product['precios'])): ?>
                            <div class="price-box">
                                <h3>Precios</h3>
                                <?php if (isset($product['precios']['precio_1'])): ?>
                                    <p class="price-main">$<?= number_format($product['precios']['precio_1'], 2) ?> MXN</p>
                                <?php endif; ?>

                                <?php if (isset($product['precios']['precio_especial'])): ?>
                                    <p class="price-special">Precio Especial: $<?= number_format($product['precios']['precio_especial'], 2) ?> MXN</p>
                                <?php endif; ?>

                                <?php if (isset($product['precios']['moneda'])): ?>
                                    <p class="price-currency">Moneda: <?= htmlspecialchars($product['precios']['moneda']) ?></p>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <p class="no-price">Precio no disponible</p>
                        <?php endif; ?>
                    </div>

                    <div class="product-stock">
                        <h3>Disponibilidad</h3>
                        <?php if (isset($product['existencia'])): ?>
                            <p class="stock-status <?= $product['existencia'] > 0 ? 'in-stock' : 'out-of-stock' ?>">
                                <?= $product['existencia'] > 0 ? 'En Stock' : 'Agotado' ?>
                            </p>
                            <p class="stock-quantity">Cantidad: <?= $product['existencia'] ?></p>
                        <?php else: ?>
                            <p class="stock-unknown">Disponibilidad desconocida</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="product-description">
                <h3>Descripción</h3>
                <?php if (isset($product['descripcion']) && !empty($product['descripcion'])): ?>
                    <div class="description-content">
                        <?= $product['descripcion'] ?>
                    </div>
                <?php else: ?>
                    <p class="no-description">No hay descripción disponible para este producto.</p>
                <?php endif; ?>
            </div>

            <?php if (isset($product['caracteristicas']) && !empty($product['caracteristicas'])): ?>
                <div class="product-specs">
                    <h3>Especificaciones</h3>
                    <table class="specs-table">
                        <tbody>
                            <?php foreach ($product['caracteristicas'] as $spec): ?>
                                <tr>
                                    <th><?= htmlspecialchars($spec['nombre']) ?></th>
                                    <td><?= htmlspecialchars($spec['valor']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="error-container">
            <h2>Producto no encontrado</h2>
            <p>Lo sentimos, no se pudo encontrar información para el producto solicitado.</p>
        </div>
    <?php endif; ?>
</div>
