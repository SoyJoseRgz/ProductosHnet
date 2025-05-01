<?php
/**
 * Repositorio base
 *
 * Esta clase abstracta proporciona funcionalidad común para todos los repositorios
 *
 * @package app\Repositories
 */
namespace app\Repositories;

use app\Interfaces\LoggerServiceInterface;
use app\Services\ServiceFactory;

abstract class BaseRepository
{
    /**
     * Servicio de logging
     *
     * @var LoggerServiceInterface
     */
    protected $logger;

    /**
     * Caché de datos
     *
     * @var array
     */
    protected $cache = [];

    /**
     * Tiempo de expiración del caché en segundos
     *
     * @var int
     */
    protected $cacheExpiration = 300; // 5 minutos por defecto

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->logger = ServiceFactory::getLoggerService();
    }

    /**
     * Obtiene datos de la caché
     *
     * @param string $key Clave de caché
     * @return array|null Datos en caché o null si no existe o ha expirado
     */
    protected function getFromCache(string $key): ?array
    {
        // Verificar si existe en caché
        if (isset($this->cache[$key])) {
            $cacheItem = $this->cache[$key];

            // Verificar si ha expirado
            if ($cacheItem['expires'] > time()) {
                $this->logCacheHit($key);
                return $cacheItem['data'];
            }

            // Eliminar de caché si ha expirado
            unset($this->cache[$key]);
        }

        $this->logCacheMiss($key);
        return null;
    }

    /**
     * Guarda datos en la caché
     *
     * @param string $key Clave de caché
     * @param array $data Datos a guardar
     * @param int|null $expiration Tiempo de expiración personalizado (opcional)
     * @return void
     */
    protected function saveToCache(string $key, array $data, ?int $expiration = null): void
    {
        $this->cache[$key] = [
            'data' => $data,
            'expires' => time() + ($expiration ?? $this->cacheExpiration)
        ];
    }

    /**
     * Registra un hit de caché
     *
     * @param string $key Clave de caché
     * @return void
     */
    protected function logCacheHit(string $key): void
    {
        // Implementación simplificada para reducir logs innecesarios
        // Se puede extender en clases hijas si es necesario
    }

    /**
     * Registra un miss de caché
     *
     * @param string $key Clave de caché
     * @return void
     */
    protected function logCacheMiss(string $key): void
    {
        // Implementación simplificada para reducir logs innecesarios
        // Se puede extender en clases hijas si es necesario
    }

    /**
     * Limpia la caché
     *
     * @param string|null $keyPrefix Prefijo de clave para limpiar solo ciertas entradas (opcional)
     * @return void
     */
    protected function clearCache(?string $keyPrefix = null): void
    {
        if ($keyPrefix === null) {
            // Limpiar toda la caché
            $this->cache = [];
            $this->logger->info('Caché limpiada completamente');
        } else {
            // Limpiar solo las entradas que coincidan con el prefijo
            $initialCount = count($this->cache);
            foreach (array_keys($this->cache) as $key) {
                if (strpos($key, $keyPrefix) === 0) {
                    unset($this->cache[$key]);
                }
            }
            $removedCount = $initialCount - count($this->cache);
            $this->logger->info("Caché limpiada con prefijo: {$keyPrefix}", [
                'removed_entries' => $removedCount
            ]);
        }
    }

    /**
     * Ejecuta una operación con manejo de caché
     *
     * @param string $cacheKey Clave de caché
     * @param callable $operation Función que realiza la operación
     * @param int|null $expiration Tiempo de expiración personalizado (opcional)
     * @return mixed Resultado de la operación
     */
    protected function executeWithCache(string $cacheKey, callable $operation, ?int $expiration = null)
    {
        // Verificar si existe en caché
        if ($cachedData = $this->getFromCache($cacheKey)) {
            return $cachedData;
        }

        // Ejecutar la operación
        $result = $operation();

        // Guardar en caché si la respuesta es válida
        if ($result !== null && is_array($result)) {
            $this->saveToCache($cacheKey, $result, $expiration);
        }

        return $result;
    }

    /**
     * Formatea un mensaje de error para logging
     *
     * @param string $operation Nombre de la operación
     * @param string $message Mensaje de error
     * @param array $context Contexto adicional
     * @return string Mensaje formateado
     */
    protected function formatErrorMessage(string $operation, string $message, array $context = []): string
    {
        $className = (new \ReflectionClass($this))->getShortName();
        $contextStr = !empty($context) ? ' - Contexto: ' . json_encode($context) : '';
        return "[{$className}::{$operation}] {$message}{$contextStr}";
    }
}
