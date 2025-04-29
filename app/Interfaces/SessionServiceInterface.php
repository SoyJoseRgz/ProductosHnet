<?php
/**
 * Interfaz para el servicio de sesión
 * 
 * Define los métodos que debe implementar cualquier servicio de sesión
 * 
 * @package app\Interfaces
 */
namespace app\Interfaces;

interface SessionServiceInterface
{
    /**
     * Inicia una sesión para un usuario
     * 
     * @param array $userData Datos del usuario
     * @return bool True si se inició la sesión correctamente, false en caso contrario
     */
    public function start(array $userData): bool;
    
    /**
     * Cierra la sesión actual
     * 
     * @return void
     */
    public function end(): void;
    
    /**
     * Verifica si hay una sesión activa
     * 
     * @return bool True si hay una sesión activa, false en caso contrario
     */
    public function isActive(): bool;
    
    /**
     * Obtiene los datos del usuario de la sesión
     * 
     * @return array|null Datos del usuario o null si no hay sesión
     */
    public function getUserData(): ?array;
    
    /**
     * Establece un valor en la sesión
     * 
     * @param string $key Clave
     * @param mixed $value Valor
     * @return void
     */
    public function set(string $key, $value): void;
    
    /**
     * Obtiene un valor de la sesión
     * 
     * @param string $key Clave
     * @param mixed $default Valor por defecto si la clave no existe
     * @return mixed Valor almacenado o valor por defecto
     */
    public function get(string $key, $default = null);
    
    /**
     * Elimina un valor de la sesión
     * 
     * @param string $key Clave
     * @return void
     */
    public function remove(string $key): void;
    
    /**
     * Verifica si una clave existe en la sesión
     * 
     * @param string $key Clave
     * @return bool True si la clave existe, false en caso contrario
     */
    public function has(string $key): bool;
    
    /**
     * Redirige al usuario a la página de login si no hay sesión activa
     * 
     * @return void
     */
    public function requireAuthentication(): void;
}
