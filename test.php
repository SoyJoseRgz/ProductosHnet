<?php
// Archivo de prueba simple
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de Página</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        h1 {
            color: #3498db;
        }
        .success {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Página de Prueba</h1>
        <p class="success">Si puedes ver esta página, el servidor PHP está funcionando correctamente.</p>
        
        <h2>Información del Servidor</h2>
        <ul style="text-align: left;">
            <li><strong>PHP Version:</strong> <?php echo phpversion(); ?></li>
            <li><strong>Server Software:</strong> <?php echo $_SERVER['SERVER_SOFTWARE']; ?></li>
            <li><strong>Server Name:</strong> <?php echo $_SERVER['SERVER_NAME']; ?></li>
            <li><strong>Document Root:</strong> <?php echo $_SERVER['DOCUMENT_ROOT']; ?></li>
            <li><strong>Request URI:</strong> <?php echo $_SERVER['REQUEST_URI']; ?></li>
            <li><strong>Script Name:</strong> <?php echo $_SERVER['SCRIPT_NAME']; ?></li>
        </ul>
        
        <p>
            <a href="index.php" style="display: inline-block; background-color: #3498db; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px;">Ir a la Página Principal</a>
        </p>
    </div>
</body>
</html>
