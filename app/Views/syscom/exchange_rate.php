<?php
/**
 * Vista para mostrar el tipo de cambio
 *
 * Esta vista muestra la información del tipo de cambio de SYSCOM
 *
 * @package app\Views\syscom
 */
?>

<div class="syscom-container">
    <h1 class="page-title">Tipo de Cambio USD a MXN</h1>

    <div class="navigation-links">
        <a href="<?= APP_URL ?>/syscom/categories" class="btn btn-secondary btn-sm">
            <i class="back-icon"></i> Volver a Categorías
        </a>
    </div>

    <?php if (isset($exchangeRate) && $exchangeRate !== null): ?>
        <div class="exchange-rate-container">
            <div class="exchange-rate-card">
                <div class="exchange-rate-header">
                    <h2>Información del Tipo de Cambio</h2>
                </div>
                <div class="exchange-rate-body">
                    <div class="exchange-rate-info">
                        <div class="exchange-rate-current">
                            <div class="exchange-rate-current-value">
                                <span class="currency-symbol">$</span>
                                <span class="rate-value"><?= isset($exchangeRate['normal']) ? number_format((float)$exchangeRate['normal'], 2) : '0.00' ?></span>
                                <span class="currency-code">MXN</span>
                            </div>
                            <div class="exchange-rate-current-label">
                                Tipo de Cambio Actual (1 USD)
                            </div>
                        </div>

                        <div class="exchange-rate-details">
                            <div class="exchange-rate-table">
                                <div class="exchange-rate-row header">
                                    <div class="exchange-rate-cell">Periodo</div>
                                    <div class="exchange-rate-cell">Valor (MXN)</div>
                                </div>

                                <div class="exchange-rate-row">
                                    <div class="exchange-rate-cell">
                                        <span class="period-label">Normal</span>
                                    </div>
                                    <div class="exchange-rate-cell">
                                        <span class="rate-amount"><?= isset($exchangeRate['normal']) ? '$' . number_format((float)$exchangeRate['normal'], 2) : 'N/A' ?></span>
                                    </div>
                                </div>

                                <div class="exchange-rate-row">
                                    <div class="exchange-rate-cell">
                                        <span class="period-label">Preferencial</span>
                                    </div>
                                    <div class="exchange-rate-cell">
                                        <span class="rate-amount"><?= isset($exchangeRate['preferencial']) ? '$' . number_format((float)$exchangeRate['preferencial'], 2) : 'N/A' ?></span>
                                    </div>
                                </div>

                                <div class="exchange-rate-row">
                                    <div class="exchange-rate-cell">
                                        <span class="period-label">Un día</span>
                                    </div>
                                    <div class="exchange-rate-cell">
                                        <span class="rate-amount"><?= isset($exchangeRate['un_dia']) ? '$' . number_format((float)$exchangeRate['un_dia'], 2) : 'N/A' ?></span>
                                    </div>
                                </div>

                                <div class="exchange-rate-row">
                                    <div class="exchange-rate-cell">
                                        <span class="period-label">Una semana</span>
                                    </div>
                                    <div class="exchange-rate-cell">
                                        <span class="rate-amount"><?= isset($exchangeRate['una_semana']) ? '$' . number_format((float)$exchangeRate['una_semana'], 2) : 'N/A' ?></span>
                                    </div>
                                </div>

                                <div class="exchange-rate-row">
                                    <div class="exchange-rate-cell">
                                        <span class="period-label">Dos semanas</span>
                                    </div>
                                    <div class="exchange-rate-cell">
                                        <span class="rate-amount"><?= isset($exchangeRate['dos_semanas']) ? '$' . number_format((float)$exchangeRate['dos_semanas'], 2) : 'N/A' ?></span>
                                    </div>
                                </div>

                                <div class="exchange-rate-row">
                                    <div class="exchange-rate-cell">
                                        <span class="period-label">Tres semanas</span>
                                    </div>
                                    <div class="exchange-rate-cell">
                                        <span class="rate-amount"><?= isset($exchangeRate['tres_semanas']) ? '$' . number_format((float)$exchangeRate['tres_semanas'], 2) : 'N/A' ?></span>
                                    </div>
                                </div>

                                <div class="exchange-rate-row">
                                    <div class="exchange-rate-cell">
                                        <span class="period-label">Un mes</span>
                                    </div>
                                    <div class="exchange-rate-cell">
                                        <span class="rate-amount"><?= isset($exchangeRate['un_mes']) ? '$' . number_format((float)$exchangeRate['un_mes'], 2) : 'N/A' ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="exchange-rate-footer">
                            <div class="exchange-rate-note">
                                <p>Los valores mostrados son proporcionados por SYSCOM y pueden variar. Última actualización: <?= date('d/m/Y H:i:s') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="error-container">
            <p>Error al cargar la información del tipo de cambio. Por favor, intente nuevamente más tarde.</p>

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
