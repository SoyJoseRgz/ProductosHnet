<?php
/**
 * Interfaz para el servicio de API de SYSCOM
 *
 * Define los métodos necesarios para interactuar con la API de SYSCOM
 *
 * @package app\Interfaces
 */
namespace app\Interfaces;

interface SyscomApiServiceInterface
{
    /**
     * Obtiene un token de acceso válido para la API de SYSCOM
     *
     * Si el token actual ha expirado, obtiene uno nuevo automáticamente
     *
     * @return string Token de acceso
     */
    public function getAccessToken(): string;

    /**
     * Realiza una petición a la API de SYSCOM
     *
     * @param string $method Método HTTP (GET, POST, PUT, DELETE)
     * @param string $endpoint Endpoint de la API
     * @param array $params Parámetros para la petición
     * @param array $headers Cabeceras adicionales para la petición
     * @return array|null Respuesta de la API o null en caso de error
     */
    public function request(string $method, string $endpoint, array $params = [], array $headers = []): ?array;
}
