<?php
/**
 * Modelo de usuario
 * 
 * Representa a un usuario del sistema
 * 
 * @package app\Models
 */
namespace app\Models;

class User
{
    /**
     * Correo electrónico del usuario
     * 
     * @var string
     */
    private $email;
    
    /**
     * Nombre del usuario
     * 
     * @var string
     */
    private $name;
    
    /**
     * Contraseña del usuario (hash)
     * 
     * @var string
     */
    private $password;
    
    /**
     * Constructor
     * 
     * @param string $email Correo electrónico del usuario
     * @param string $name Nombre del usuario
     * @param string $password Contraseña del usuario (hash)
     */
    public function __construct(string $email, string $name, string $password)
    {
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
    }
    
    /**
     * Obtiene el correo electrónico del usuario
     * 
     * @return string Correo electrónico
     */
    public function getEmail(): string
    {
        return $this->email;
    }
    
    /**
     * Obtiene el nombre del usuario
     * 
     * @return string Nombre
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * Verifica si la contraseña proporcionada coincide con la del usuario
     * 
     * @param string $password Contraseña a verificar
     * @return bool True si la contraseña es correcta, false en caso contrario
     */
    public function verifyPassword(string $password): bool
    {
        // Si la contraseña está almacenada como hash, usar password_verify
        if (strpos($this->password, '$2y$') === 0) {
            return password_verify($password, $this->password);
        }
        
        // Si no, comparar directamente (para compatibilidad con contraseñas en texto plano)
        return $this->password === $password;
    }
    
    /**
     * Convierte el usuario a un array
     * 
     * @return array Datos del usuario
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name
        ];
    }
}
