<?php
/**
 * Interfaz para el servicio de usuario
 * 
 * Define los métodos que debe implementar cualquier servicio de usuario
 * 
 * @package app\Interfaces
 */
namespace app\Interfaces;

interface UserServiceInterface
{
    /**
     * Verifica si el usuario está autenticado
     * 
     * @return bool True si el usuario está autenticado, false en caso contrario
     */
    public function isAuthenticated(): bool;
    
    /**
     * Obtiene la información del usuario actual
     * 
     * @return array|null Información del usuario o null si no está autenticado
     */
    public function getCurrentUser(): ?array;
    
    /**
     * Redirige al usuario a la página de login si no está autenticado
     * 
     * @return void
     */
    public function requireAuthentication(): void;
}
