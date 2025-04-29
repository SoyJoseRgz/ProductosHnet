<?php
/**
 * Servicio de autenticación
 *
 * Este servicio maneja la lógica de autenticación de usuarios
 *
 * @package app\Services
 */
namespace app\Services;

use app\Interfaces\AuthServiceInterface;
use app\Models\User;

class AuthService implements AuthServiceInterface
{
    /**
     * Repositorio de usuarios (en este caso, un array de usuarios)
     *
     * @var array
     */
    private $users;

    /**
     * Usuarios convertidos a objetos User
     *
     * @var User[]
     */
    private $userObjects = [];

    /**
     * Constructor
     *
     * @param array $users Repositorio de usuarios
     */
    public function __construct(array $users)
    {
        $this->users = $users;

        // Convertir los usuarios a objetos User
        foreach ($users as $email => $userData) {
            $this->userObjects[$email] = new User(
                $email,
                $userData['name'],
                $userData['password']
            );
        }
    }

    /**
     * Verifica las credenciales del usuario
     *
     * @param string $email Correo electrónico del usuario
     * @param string $password Contraseña del usuario
     * @return bool True si las credenciales son válidas, false en caso contrario
     */
    public function verifyCredentials(string $email, string $password): bool
    {
        // Verificar si el usuario existe
        if (!isset($this->userObjects[$email])) {
            return false;
        }

        // Verificar la contraseña
        return $this->userObjects[$email]->verifyPassword($password);
    }

    /**
     * Obtiene la información del usuario por su correo electrónico
     *
     * @param string $email Correo electrónico del usuario
     * @return array|null Información del usuario o null si no existe
     */
    public function getUserByEmail(string $email): ?array
    {
        return isset($this->userObjects[$email]) ? $this->userObjects[$email]->toArray() : null;
    }

    /**
     * Inicia sesión para un usuario
     *
     * @param string $email Correo electrónico del usuario
     * @return bool True si se inició sesión correctamente, false en caso contrario
     */
    public function login(string $email): bool
    {
        if (!isset($this->userObjects[$email])) {
            return false;
        }

        $user = $this->userObjects[$email];
        $_SESSION['user'] = [
            'email' => $email,
            'name' => $user->getName()
        ];

        return true;
    }

    /**
     * Cierra la sesión del usuario actual
     *
     * @return void
     */
    public function logout(): void
    {
        session_unset();
        session_destroy();
    }
}
