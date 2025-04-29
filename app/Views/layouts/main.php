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

    <!-- Estilos adicionales -->
    <?php if (isset($extraCss)): ?>
        <?php foreach ($extraCss as $css): ?>
            <link rel="stylesheet" href="<?= APP_URL ?>/public/css/<?= $css ?>.css">
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Estilos inline para asegurar que al menos hay algo visible -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .login-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin: 50px auto;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            width: 100%;
        }

        /* Estilos para el encabezado y navegación */
        header {
            background-color: #2c3e50;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 60px;
        }
        header h1 {
            margin: 0;
            font-size: 24px;
        }

        /* Estilos para el contenedor principal */
        .app-container {
            display: flex;
            min-height: 100vh;
            padding-top: 60px; /* Altura del header */
        }

        /* Estilos para el sidebar */
        .sidebar {
            width: 250px;
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
            height: calc(100vh - 60px);
            position: fixed;
            top: 60px;
            left: 0;
            overflow-y: auto;
            z-index: 900;
            transition: all 0.3s ease;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
        }

        .sidebar-header {
            padding: 20px 15px;
            border-bottom: 1px solid #dee2e6;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-header h3 {
            margin: 0;
            font-size: 15px;
            color: #495057;
            font-weight: 600;
            letter-spacing: 0;
            text-overflow: ellipsis;
            overflow: hidden;
        }

        .app-text {
            display: inline-block;
            padding: 2px 0;
            width: 100%;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-item {
            border-bottom: 1px solid #f1f1f1;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: #495057;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .sidebar-link:hover {
            background-color: #e9ecef;
        }

        .sidebar-icon {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .home-icon::before {
            content: "🏠";
        }

        .syscom-icon::before {
            content: "🔌";
        }

        .category-icon::before {
            content: "📂";
        }

        .sidebar-item.active > .sidebar-link {
            background-color: #e9ecef;
            font-weight: bold;
        }

        .has-submenu {
            position: relative;
        }

        .submenu-arrow {
            position: absolute;
            right: 15px;
            display: inline-block;
            width: 0;
            height: 0;
            margin-left: 10px;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid #6c757d;
            transition: transform 0.3s;
        }

        .sidebar-item.active .submenu-arrow {
            transform: rotate(180deg);
        }

        .submenu {
            list-style: none;
            padding: 0;
            margin: 0;
            background-color: #f1f1f1;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .submenu.open {
            max-height: 500px;
        }

        .submenu-link {
            display: flex;
            align-items: center;
            padding: 10px 15px 10px 40px;
            color: #495057;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .submenu-link:hover {
            background-color: #e9ecef;
        }

        .submenu-item.active .submenu-link {
            background-color: #e9ecef;
            font-weight: bold;
        }

        /* Estilos para el contenido principal */
        .main-content {
            flex: 1;
            padding: 20px;
            margin-left: 250px;
        }
        .main-nav {
            flex: 1;
            margin: 0 20px;
        }
        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }
        .nav-menu li {
            margin-right: 20px;
        }
        .nav-menu a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .nav-menu a:hover {
            background-color: #3498db;
        }

        /* Estilos para el menú desplegable */
        .dropdown {
            position: relative;
        }

        .dropdown-toggle {
            display: flex;
            align-items: center;
        }

        .dropdown-icon {
            display: inline-block;
            width: 0;
            height: 0;
            margin-left: 8px;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid white;
            transition: transform 0.3s;
        }

        .dropdown:hover .dropdown-icon {
            transform: rotate(180deg);
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #34495e;
            min-width: 200px;
            border-radius: 4px;
            padding: 8px 0;
            margin-top: 5px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s;
            z-index: 100;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-menu li {
            margin: 0;
            width: 100%;
        }

        .dropdown-menu a {
            display: block;
            padding: 8px 15px;
            border-radius: 0;
        }

        .dropdown-menu a:hover {
            background-color: #3498db;
        }
        .user-menu {
            display: flex;
            align-items: center;
        }
        .user-info {
            display: flex;
            align-items: center;
            margin-right: 15px;
        }
        .user-avatar {
            width: 32px;
            height: 32px;
            background-color: #3498db;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }
        .user-name {
            font-size: 14px;
        }
        .logout-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            display: flex;
            align-items: center;
        }
        .logout-icon {
            display: inline-block;
            width: 16px;
            height: 16px;
            background-color: white;
            mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Cpath d='M497 273L329 441c-15 15-41 4.5-41-17v-96H152c-13.3 0-24-10.7-24-24v-96c0-13.3 10.7-24 24-24h136V88c0-21.4 25.9-32 41-17l168 168c9.3 9.4 9.3 24.6 0 34zM192 436v-40c0-6.6-5.4-12-12-12H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h84c6.6 0 12-5.4 12-12V76c0-6.6-5.4-12-12-12H96c-53 0-96 43-96 96v192c0 53 43 96 96 96h84c6.6 0 12-5.4 12-12z'/%3E%3C/svg%3E");
            margin-right: 5px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            header {
                padding: 10px;
            }
            header h1 {
                font-size: 18px;
            }
            .user-menu {
                display: flex;
            }
            .user-name {
                display: none;
            }

            .sidebar {
                width: 60px;
                transition: width 0.3s;
            }

            .sidebar-header {
                padding: 15px 10px;
            }

            .sidebar-header h3 {
                font-size: 0;
            }

            .sidebar-header h3::before {
                content: "📱";
                font-size: 20px;
            }

            .sidebar-link span,
            .submenu-link span {
                display: none;
            }

            .submenu-arrow {
                display: none;
            }

            .sidebar:hover {
                width: 250px;
            }

            .sidebar:hover .sidebar-header h3 {
                font-size: 14px;
            }

            .sidebar:hover .sidebar-header h3::before {
                display: none;
            }

            .sidebar:hover .app-text {
                display: inline-block;
            }

            .sidebar:hover .sidebar-link span,
            .sidebar:hover .submenu-link span,
            .sidebar:hover .submenu-arrow {
                display: inline-block;
            }

            .main-content {
                margin-left: 60px;
                padding: 15px;
            }

            .sidebar:hover + .main-content {
                margin-left: 250px;
            }

            .submenu-link {
                padding-left: 15px;
            }

            .sidebar:hover .submenu-link {
                padding-left: 40px;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                margin-left: 0;
                padding: 10px;
            }

            .sidebar {
                transform: translateX(-100%);
                width: 250px;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-link span,
            .submenu-link span,
            .sidebar-header h3,
            .submenu-arrow {
                display: inline-block;
            }

            .sidebar-toggle {
                display: block;
                position: fixed;
                bottom: 20px;
                right: 20px;
                width: 50px;
                height: 50px;
                background-color: #2c3e50;
                color: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
                z-index: 1000;
                cursor: pointer;
            }
        }
    </style>
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
                        </ul>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
    <?php endif; ?>

    <div class="container">
        <?php
        // Verificar si el archivo de contenido existe
        if (file_exists($content)) {
            // Capturar el contenido en un buffer para evitar problemas
            ob_start();
            include $content;
            $contentOutput = ob_get_clean();

            // Mostrar el contenido
            echo $contentOutput;
        } else {
            echo "<div style='color: red; padding: 20px; background-color: #ffeeee; border: 1px solid #ffaaaa; border-radius: 4px;'>";
            echo "<h3>Error: No se pudo cargar el contenido</h3>";
            echo "<p>El archivo no existe: " . htmlspecialchars($content) . "</p>";
            echo "</div>";
        }
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

    <!-- La información de depuración ha sido desactivada -->
    <?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'development' && isset($_GET['debug'])): ?>
    <div style="margin-top: 50px; padding: 15px; background-color: #f8f9fa; border: 1px solid #ddd; border-radius: 4px;">
        <h3>Información de depuración</h3>
        <p><strong>APP_URL:</strong> <?= APP_URL ?></p>
        <p><strong>BASE_PATH:</strong> <?= BASE_PATH ?></p>
        <p><strong>REQUEST_URI:</strong> <?= $_SERVER['REQUEST_URI'] ?></p>
        <p><strong>SCRIPT_NAME:</strong> <?= $_SERVER['SCRIPT_NAME'] ?></p>
        <p><strong>PHP_SELF:</strong> <?= $_SERVER['PHP_SELF'] ?></p>
        <p><strong>Archivo de contenido:</strong> <?= $content ?></p>

        <h4>Verificación de archivos</h4>
        <ul>
            <li>style.css: <?= file_exists(BASE_PATH . '/public/css/style.css') ? 'Existe' : 'No existe' ?></li>
            <?php if (isset($extraCss)): foreach ($extraCss as $css): ?>
                <li><?= $css ?>.css: <?= file_exists(BASE_PATH . '/public/css/' . $css . '.css') ? 'Existe' : 'No existe' ?></li>
            <?php endforeach; endif; ?>
        </ul>
    </div>
    <?php endif; ?>
</body>
</html>
