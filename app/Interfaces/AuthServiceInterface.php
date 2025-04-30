<?php
/**
 * Interfaz para el servicio de autenticación
 *
 * Define los métodos que debe implementar cualquier servicio de autenticación
 *
 * @package app\Interfaces
 */
namespace app\Interfaces;

interface AuthServiceInterface
{
    /**
     * Verifica las credenciales del usuario
     *
     * @param string $email Correo electrónico del usuario
     * @param string $password Contraseña del usuario
     * @return bool True si las credenciales son válidas, false en caso contrario
     */
    public function verifyCredentials(string $email, string $password): bool;

    /**
     * Obtiene la información del usuario por su correo electrónico
     *
     * @param string $email Correo electrónico del usuario
     * @return array|null Información del usuario o null si no existe
     */
    public function getUserByEmail(string $email): ?array;
}
