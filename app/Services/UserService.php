<?php
/**
 * Servicio de usuario
 *
 * Este servicio maneja la lógica relacionada con los usuarios y la autenticación
 *
 * @package app\Services
 */
namespace app\Services;

use app\Interfaces\UserServiceInterface;

class UserService implements UserServiceInterface
{
    /**
     * Verifica si el usuario está autenticado
     *
     * @return bool True si el usuario está autenticado, false en caso contrario
     */
    public function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Obtiene la información del usuario actual
     *
     * @return array|null Información del usuario o null si no está autenticado
     */
    public function getCurrentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Redirige al usuario a la página de login si no está autenticado
     *
     * @return void
     */
    public function requireAuthentication(): void
    {
        if (!$this->isAuthenticated()) {
            header('Location: ' . APP_URL . '/login');
            exit;
        }
    }
}
