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

    <?php if (isset($extraJs)): ?>
        <?php foreach ($extraJs as $js): ?>
            <script src="<?= APP_URL ?>/public/js/<?= $js ?>.js"></script>
        <?php endforeach; ?>
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
