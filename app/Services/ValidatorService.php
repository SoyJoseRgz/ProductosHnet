<?php
/**
 * Servicio de validación
 * 
 * Este servicio proporciona métodos para validar datos de entrada
 * 
 * @package app\Services
 */
namespace app\Services;

use app\Interfaces\ValidatorServiceInterface;

class ValidatorService implements ValidatorServiceInterface
{
    /**
     * Errores de validación
     * 
     * @var array
     */
    private $errors = [];
    
    /**
     * Valida un correo electrónico
     * 
     * @param string $email Correo electrónico a validar
     * @return bool True si el correo es válido, false en caso contrario
     */
    public function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Valida una contraseña
     * 
     * @param string $password Contraseña a validar
     * @param int $minLength Longitud mínima (opcional)
     * @return bool True si la contraseña es válida, false en caso contrario
     */
    public function validatePassword(string $password, int $minLength = 6): bool
    {
        // Verificar longitud mínima
        if (strlen($password) < $minLength) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Sanitiza un valor para evitar inyección de código
     * 
     * @param string $value Valor a sanitizar
     * @return string Valor sanitizado
     */
    public function sanitize(string $value): string
    {
        // Eliminar espacios en blanco al inicio y al final
        $value = trim($value);
        
        // Eliminar barras invertidas
        $value = stripslashes($value);
        
        // Convertir caracteres especiales a entidades HTML
        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        
        return $value;
    }
    
    /**
     * Valida un formulario completo
     * 
     * @param array $data Datos del formulario
     * @param array $rules Reglas de validación
     * @return array Array con los errores encontrados (vacío si no hay errores)
     */
    public function validateForm(array $data, array $rules): array
    {
        // Reiniciar errores
        $this->errors = [];
        
        // Validar cada campo según las reglas
        foreach ($rules as $field => $fieldRules) {
            // Verificar si el campo existe
            if (!isset($data[$field]) && in_array('required', $fieldRules)) {
                $this->errors[$field] = "El campo $field es obligatorio";
                continue;
            }
            
            // Si el campo no existe y no es obligatorio, continuar
            if (!isset($data[$field])) {
                continue;
            }
            
            // Obtener el valor del campo
            $value = $data[$field];
            
            // Aplicar las reglas
            foreach ($fieldRules as $rule) {
                // Regla de campo obligatorio
                if ($rule === 'required' && empty($value)) {
                    $this->errors[$field] = "El campo $field es obligatorio";
                    break;
                }
                
                // Regla de correo electrónico
                if ($rule === 'email' && !$this->validateEmail($value)) {
                    $this->errors[$field] = "El campo $field debe ser un correo electrónico válido";
                    break;
                }
                
                // Regla de longitud mínima
                if (strpos($rule, 'min:') === 0) {
                    $minLength = (int) substr($rule, 4);
                    if (strlen($value) < $minLength) {
                        $this->errors[$field] = "El campo $field debe tener al menos $minLength caracteres";
                        break;
                    }
                }
                
                // Regla de longitud máxima
                if (strpos($rule, 'max:') === 0) {
                    $maxLength = (int) substr($rule, 4);
                    if (strlen($value) > $maxLength) {
                        $this->errors[$field] = "El campo $field debe tener como máximo $maxLength caracteres";
                        break;
                    }
                }
            }
        }
        
        return $this->errors;
    }
    
    /**
     * Obtiene los errores de validación
     * 
     * @return array Errores de validación
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
    
    /**
     * Verifica si hay errores de validación
     * 
     * @return bool True si hay errores, false en caso contrario
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}
