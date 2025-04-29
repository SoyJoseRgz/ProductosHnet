<?php
/**
 * Utilidad para redirecciones
 * 
 * Esta clase proporciona métodos para manejar redirecciones de manera consistente
 * 
 * @package app\Utils
 */
namespace app\Utils;

class Redirector
{
    /**
     * Redirige a una URL
     * 
     * @param string $url URL a la que redirigir
     * @param array $params Parámetros para añadir a la URL (opcional)
     * @param int $statusCode Código de estado HTTP (opcional)
     * @return void
     */
    public static function to(string $url, array $params = [], int $statusCode = 302): void
    {
        // Añadir parámetros a la URL si existen
        if (!empty($params)) {
            $url .= (strpos($url, '?') === false) ? '?' : '&';
            $url .= http_build_query($params);
        }
        
        // Establecer el código de estado HTTP
        http_response_code($statusCode);
        
        // Redirigir
        header('Location: ' . $url);
        exit;
    }
    
    /**
     * Redirige a una ruta dentro de la aplicación
     * 
     * @param string $path Ruta dentro de la aplicación
     * @param array $params Parámetros para añadir a la URL (opcional)
     * @param int $statusCode Código de estado HTTP (opcional)
     * @return void
     */
    public static function route(string $path, array $params = [], int $statusCode = 302): void
    {
        // Construir la URL completa
        $url = APP_URL . '/' . ltrim($path, '/');
        
        // Redirigir
        self::to($url, $params, $statusCode);
    }
    
    /**
     * Redirige a la página de inicio
     * 
     * @param array $params Parámetros para añadir a la URL (opcional)
     * @param int $statusCode Código de estado HTTP (opcional)
     * @return void
     */
    public static function home(array $params = [], int $statusCode = 302): void
    {
        self::route('', $params, $statusCode);
    }
    
    /**
     * Redirige a la página de login
     * 
     * @param array $params Parámetros para añadir a la URL (opcional)
     * @param int $statusCode Código de estado HTTP (opcional)
     * @return void
     */
    public static function login(array $params = [], int $statusCode = 302): void
    {
        self::route('login', $params, $statusCode);
    }
}
