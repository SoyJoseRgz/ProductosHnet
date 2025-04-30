<?php
/**
 * Fábrica de servicios
 *
 * Este servicio proporciona instancias de los servicios del sistema
 *
 * @package app\Services
 */
namespace app\Services;

use app\Interfaces\AuthServiceInterface;
use app\Interfaces\ViewServiceInterface;
use app\Interfaces\SessionServiceInterface;
use app\Interfaces\ValidatorServiceInterface;
use app\Interfaces\SyscomApiServiceInterface;
use app\Interfaces\SyscomRepositoryInterface;
use app\Interfaces\LoggerServiceInterface;
use app\Repositories\SyscomRepository;

class ServiceFactory
{
    /**
     * Instancias de servicios
     *
     * @var array
     */
    private static $instances = [];

    /**
     * Obtiene una instancia del servicio de autenticación
     *
     * @return AuthServiceInterface Instancia del servicio
     */
    public static function getAuthService(): AuthServiceInterface
    {
        if (!isset(self::$instances[AuthServiceInterface::class])) {
            self::$instances[AuthServiceInterface::class] = new AuthService($GLOBALS['users'] ?? []);
        }

        return self::$instances[AuthServiceInterface::class];
    }



    /**
     * Obtiene una instancia del servicio de vistas
     *
     * @return ViewServiceInterface Instancia del servicio
     */
    public static function getViewService(): ViewServiceInterface
    {
        if (!isset(self::$instances[ViewServiceInterface::class])) {
            self::$instances[ViewServiceInterface::class] = new ViewService();
        }

        return self::$instances[ViewServiceInterface::class];
    }

    /**
     * Obtiene una instancia del servicio de sesión
     *
     * @return SessionServiceInterface Instancia del servicio
     */
    public static function getSessionService(): SessionServiceInterface
    {
        if (!isset(self::$instances[SessionServiceInterface::class])) {
            self::$instances[SessionServiceInterface::class] = new SessionService();
        }

        return self::$instances[SessionServiceInterface::class];
    }

    /**
     * Obtiene una instancia del servicio de validación
     *
     * @return ValidatorServiceInterface Instancia del servicio
     */
    public static function getValidatorService(): ValidatorServiceInterface
    {
        if (!isset(self::$instances[ValidatorServiceInterface::class])) {
            self::$instances[ValidatorServiceInterface::class] = new ValidatorService();
        }

        return self::$instances[ValidatorServiceInterface::class];
    }

    /**
     * Obtiene una instancia del servicio de API de SYSCOM
     *
     * @return SyscomApiServiceInterface Instancia del servicio
     */
    public static function getSyscomApiService(): SyscomApiServiceInterface
    {
        if (!isset(self::$instances[SyscomApiServiceInterface::class])) {
            self::$instances[SyscomApiServiceInterface::class] = new SyscomApiService();
        }

        return self::$instances[SyscomApiServiceInterface::class];
    }

    /**
     * Obtiene una instancia del repositorio de SYSCOM
     *
     * @return SyscomRepositoryInterface Instancia del repositorio
     */
    public static function getSyscomRepository(): SyscomRepositoryInterface
    {
        if (!isset(self::$instances[SyscomRepositoryInterface::class])) {
            self::$instances[SyscomRepositoryInterface::class] = new SyscomRepository();
        }

        return self::$instances[SyscomRepositoryInterface::class];
    }

    /**
     * Obtiene una instancia del servicio de logging
     *
     * @return LoggerServiceInterface Instancia del servicio
     */
    public static function getLoggerService(): LoggerServiceInterface
    {
        if (!isset(self::$instances[LoggerServiceInterface::class])) {
            self::$instances[LoggerServiceInterface::class] = new LoggerService();
        }

        return self::$instances[LoggerServiceInterface::class];
    }
}
