<?php
/**
 * Punto de entrada público
 *
 * Este archivo redirige al punto de entrada principal.
 * Es necesario cuando el servidor web está configurado para servir archivos desde la carpeta public.
 */

// Definir la ruta base del proyecto
define('BASE_PATH', dirname(__DIR__));

// Redirigir al punto de entrada principal
require_once BASE_PATH . '/index.php';
