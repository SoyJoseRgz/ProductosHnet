<?php
/**
 * Servicio de logging
 *
 * Este servicio maneja el registro de eventos en la aplicación
 *
 * @package app\Services
 */
namespace app\Services;

use app\Interfaces\LoggerServiceInterface;

class LoggerService implements LoggerServiceInterface
{
    /**
     * Directorio de logs
     *
     * @var string
     */
    private $logDir;
    
    /**
     * Archivo de log actual
     *
     * @var string
     */
    private $logFile;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->logDir = BASE_PATH . '/logs';
        
        // Crear directorio de logs si no existe
        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0755, true);
        }
        
        // Definir archivo de log actual (uno por día)
        $this->logFile = $this->logDir . '/app_' . date('Y-m-d') . '.log';
    }
    
    /**
     * {@inheritdoc}
     */
    public function info(string $message, array $context = []): void
    {
        $this->log('INFO', $message, $context);
    }
    
    /**
     * {@inheritdoc}
     */
    public function error(string $message, array $context = []): void
    {
        $this->log('ERROR', $message, $context);
    }
    
    /**
     * {@inheritdoc}
     */
    public function debug(string $message, array $context = []): void
    {
        // Solo registrar mensajes de depuración en entorno de desarrollo
        if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
            $this->log('DEBUG', $message, $context);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function warning(string $message, array $context = []): void
    {
        $this->log('WARNING', $message, $context);
    }
    
    /**
     * Registra un mensaje en el archivo de log
     *
     * @param string $level Nivel de log
     * @param string $message Mensaje a registrar
     * @param array $context Contexto adicional
     * @return void
     */
    private function log(string $level, string $message, array $context = []): void
    {
        // Formatear mensaje
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = empty($context) ? '' : ' ' . json_encode($context);
        $logMessage = "[$timestamp] [$level] $message$contextStr" . PHP_EOL;
        
        // Escribir en archivo
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }
}
