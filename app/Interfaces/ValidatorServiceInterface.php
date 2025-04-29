<?php
/**
 * Interfaz para el servicio de validación
 * 
 * Define los métodos que debe implementar cualquier servicio de validación
 * 
 * @package app\Interfaces
 */
namespace app\Interfaces;

interface ValidatorServiceInterface
{
    /**
     * Valida un correo electrónico
     * 
     * @param string $email Correo electrónico a validar
     * @return bool True si el correo es válido, false en caso contrario
     */
    public function validateEmail(string $email): bool;
    
    /**
     * Valida una contraseña
     * 
     * @param string $password Contraseña a validar
     * @param int $minLength Longitud mínima (opcional)
     * @return bool True si la contraseña es válida, false en caso contrario
     */
    public function validatePassword(string $password, int $minLength = 6): bool;
    
    /**
     * Sanitiza un valor para evitar inyección de código
     * 
     * @param string $value Valor a sanitizar
     * @return string Valor sanitizado
     */
    public function sanitize(string $value): string;
    
    /**
     * Valida un formulario completo
     * 
     * @param array $data Datos del formulario
     * @param array $rules Reglas de validación
     * @return array Array con los errores encontrados (vacío si no hay errores)
     */
    public function validateForm(array $data, array $rules): array;
}
