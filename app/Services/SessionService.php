<?php
/**
 * Servicio de sesión
 * 
 * Este servicio maneja la sesión del usuario
 * 
 * @package app\Services
 */
namespace app\Services;

use app\Interfaces\SessionServiceInterface;
use app\Utils\Redirector;

class SessionService implements SessionServiceInterface
{
    /**
     * Clave para los datos del usuario en la sesión
     * 
     * @var string
     */
    private const USER_KEY = 'user';
    
    /**
     * Inicia una sesión para un usuario
     * 
     * @param array $userData Datos del usuario
     * @return bool True si se inició la sesión correctamente, false en caso contrario
     */
    public function start(array $userData): bool
    {
        // Verificar que los datos del usuario contienen al menos el email y el nombre
        if (!isset($userData['email']) || !isset($userData['name'])) {
            return false;
        }
        
        // Almacenar los datos del usuario en la sesión
        $_SESSION[self::USER_KEY] = $userData;
        
        return true;
    }
    
    /**
     * Cierra la sesión actual
     * 
     * @return void
     */
    public function end(): void
    {
        // Eliminar todas las variables de sesión
        session_unset();
        
        // Destruir la sesión
        session_destroy();
    }
    
    /**
     * Verifica si hay una sesión activa
     * 
     * @return bool True si hay una sesión activa, false en caso contrario
     */
    public function isActive(): bool
    {
        return isset($_SESSION[self::USER_KEY]);
    }
    
    /**
     * Obtiene los datos del usuario de la sesión
     * 
     * @return array|null Datos del usuario o null si no hay sesión
     */
    public function getUserData(): ?array
    {
        return $_SESSION[self::USER_KEY] ?? null;
    }
    
    /**
     * Establece un valor en la sesión
     * 
     * @param string $key Clave
     * @param mixed $value Valor
     * @return void
     */
    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }
    
    /**
     * Obtiene un valor de la sesión
     * 
     * @param string $key Clave
     * @param mixed $default Valor por defecto si la clave no existe
     * @return mixed Valor almacenado o valor por defecto
     */
    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Elimina un valor de la sesión
     * 
     * @param string $key Clave
     * @return void
     */
    public function remove(string $key): void
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    /**
     * Verifica si una clave existe en la sesión
     * 
     * @param string $key Clave
     * @return bool True si la clave existe, false en caso contrario
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }
    
    /**
     * Redirige al usuario a la página de login si no hay sesión activa
     * 
     * @return void
     */
    public function requireAuthentication(): void
    {
        if (!$this->isActive()) {
            Redirector::login();
        }
    }
}
