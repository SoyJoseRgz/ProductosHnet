<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' . APP_NAME : APP_NAME ?></title>

    <!-- La información de depuración de consola ha sido desactivada -->
    <?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'development' && isset($_GET['debug'])): ?>
    <script>
        console.log('APP_URL: <?= APP_URL ?>');
        console.log('BASE_PATH: <?= BASE_PATH ?>');
        console.log('REQUEST_URI: <?= $_SERVER['REQUEST_URI'] ?>');
    </script>
    <?php endif; ?>

    <!-- Estilos base -->
    <link rel="stylesheet" href="<?= APP_URL ?>/public/css/style.css">

    <!-- Estilos de componentes -->
    <link rel="stylesheet" href="<?= APP_URL ?>/public/css/components.css">

    <!-- Estilos de layout -->
    <link rel="stylesheet" href="<?= APP_URL ?>/public/css/layout.css">

    <!-- Estilos adicionales -->
    <?php if (isset($extraCss)): ?>
        <?php foreach ($extraCss as $css): ?>
            <link rel="stylesheet" href="<?= APP_URL ?>/public/css/<?= $css ?>.css">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <?php if (isset($_SESSION['user'])): ?>
    <header>
        <h1><?= APP_NAME ?></h1>
        <div class="user-menu">
            <div class="user-info">
                <div class="user-avatar">
                    <?= strtoupper(substr($_SESSION['user']['name'], 0, 2)) ?>
                </div>
                <span class="user-name"><?= htmlspecialchars($_SESSION['user']['name']) ?></span>
            </div>
            <a href="<?= APP_URL ?>/logout" class="logout-btn">
                <i class="logout-icon"></i>
                <span>Cerrar Sesión</span>
            </a>
        </div>
    </header>

    <div class="app-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3><span class="app-text">APLICACIONES</span></h3>
            </div>
            <nav class="sidebar-nav">
                <ul class="sidebar-menu">
                    <li class="sidebar-item">
                        <a href="<?= APP_URL ?>/" class="sidebar-link">
                            <i class="sidebar-icon home-icon"></i>
                            <span>Inicio</span>
                        </a>
                    </li>
                    <li class="sidebar-item <?= strpos($_SERVER['REQUEST_URI'], '/syscom') !== false ? 'active' : '' ?>">
                        <a href="#" class="sidebar-link has-submenu">
                            <i class="sidebar-icon syscom-icon"></i>
                            <span>SYSCOM</span>
                            <i class="submenu-arrow"></i>
                        </a>
                        <ul class="submenu <?= strpos($_SERVER['REQUEST_URI'], '/syscom') !== false ? 'open' : '' ?>">
                            <li class="submenu-item <?= strpos($_SERVER['REQUEST_URI'], '/syscom/categories') !== false ? 'active' : '' ?>">
                                <a href="<?= APP_URL ?>/syscom/categories" class="submenu-link">
                                    <i class="sidebar-icon category-icon"></i>
                                    <span>Categorías</span>
                                </a>
                            </li>
                            <li class="submenu-item <?= strpos($_SERVER['REQUEST_URI'], '/syscom/exchange-rate') !== false ? 'active' : '' ?>">
                                <a href="<?= APP_URL ?>/syscom/exchange-rate" class="submenu-link">
                                    <i class="sidebar-icon exchange-icon"></i>
                                    <span>Tipo de Cambio</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
    <?php endif; ?>

    <div class="container">
        <?php
        // Capture content in a buffer to avoid issues
        ob_start();
        include $content;
        $contentOutput = ob_get_clean();

        // Display the content
        echo $contentOutput;
        ?>
    </div>

    <!-- JavaScript para el sidebar -->
    <script src="<?= APP_URL ?>/public/js/sidebar.js"></script>

    <?php if (isset($extraJs)): ?>
        <?php foreach ($extraJs as $js): ?>
            <script src="<?= APP_URL ?>/public/js/<?= $js ?>.js"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['user'])): ?>
        </main>
    </div>
    <?php endif; ?>

    <!-- Debug information only available in development mode with debug parameter -->
    <?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'development' && isset($_GET['debug'])): ?>
    <div style="margin-top: 50px; padding: 15px; background-color: #f8f9fa; border: 1px solid #ddd; border-radius: 4px;">
        <h3>Debug Information</h3>
        <p><strong>APP_URL:</strong> <?= APP_URL ?></p>
        <p><strong>REQUEST_URI:</strong> <?= $_SERVER['REQUEST_URI'] ?></p>
        <p><strong>Content file:</strong> <?= $content ?></p>
    </div>
    <?php endif; ?>
</body>
</html>
