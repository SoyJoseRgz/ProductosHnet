<?php
/**
 * Interfaz para el servicio de logging
 *
 * Define los métodos necesarios para registrar eventos en la aplicación
 *
 * @package app\Interfaces
 */
namespace app\Interfaces;

interface LoggerServiceInterface
{
    /**
     * Registra un mensaje informativo
     *
     * @param string $message Mensaje a registrar
     * @param array $context Contexto adicional (opcional)
     * @return void
     */
    public function info(string $message, array $context = []): void;
    
    /**
     * Registra un mensaje de error
     *
     * @param string $message Mensaje a registrar
     * @param array $context Contexto adicional (opcional)
     * @return void
     */
    public function error(string $message, array $context = []): void;
    
    /**
     * Registra un mensaje de depuración
     *
     * @param string $message Mensaje a registrar
     * @param array $context Contexto adicional (opcional)
     * @return void
     */
    public function debug(string $message, array $context = []): void;
    
    /**
     * Registra un mensaje de advertencia
     *
     * @param string $message Mensaje a registrar
     * @param array $context Contexto adicional (opcional)
     * @return void
     */
    public function warning(string $message, array $context = []): void;
}
