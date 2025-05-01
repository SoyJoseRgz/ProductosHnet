<?php
/**
 * Servicio base para APIs
 *
 * Esta clase abstracta proporciona funcionalidad común para servicios de API
 *
 * @package app\Services
 */
namespace app\Services;

use app\Interfaces\LoggerServiceInterface;

abstract class BaseApiService
{
    /**
     * Servicio de logging
     *
     * @var LoggerServiceInterface
     */
    protected $logger;

    /**
     * URL base de la API
     *
     * @var string
     */
    protected $apiBaseUrl;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->logger = ServiceFactory::getLoggerService();
    }

    /**
     * Realiza una petición HTTP
     *
     * @param string $method Método HTTP (GET, POST, PUT, DELETE)
     * @param string $url URL completa
     * @param array $params Parámetros para la petición
     * @param array $headers Cabeceras HTTP
     * @param bool $jsonEncode Si es true, codifica los parámetros como JSON
     * @param array $curlOptions Opciones adicionales para cURL
     * @return array|null Respuesta decodificada o null en caso de error
     */
    protected function makeHttpRequest(string $method, string $url, array $params = [], array $headers = [], bool $jsonEncode = true, array $curlOptions = []): ?array
    {
        $startTime = microtime(true);

        $this->logger->debug('Iniciando petición HTTP', [
            'method' => $method,
            'url' => $url,
            'params' => $params
        ]);

        // Inicializar cURL
        $ch = curl_init();

        // Configurar opciones comunes
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        // Aplicar opciones adicionales de cURL si se proporcionan
        foreach ($curlOptions as $option => $value) {
            curl_setopt($ch, $option, $value);
        }

        // Para entornos de desarrollo con certificados SSL autofirmados
        if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }

        // Configurar método y parámetros
        switch (strtoupper($method)) {
            case 'GET':
                if (!empty($params)) {
                    $queryString = http_build_query($params);
                    curl_setopt($ch, CURLOPT_URL, $url . (strpos($url, '?') === false ? '?' : '&') . $queryString);
                }
                break;

            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                if (!empty($params)) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonEncode ? json_encode($params) : $params);
                }
                break;

            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                if (!empty($params)) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonEncode ? json_encode($params) : $params);
                }
                break;

            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if (!empty($params) && !$jsonEncode) {
                    $queryString = http_build_query($params);
                    curl_setopt($ch, CURLOPT_URL, $url . (strpos($url, '?') === false ? '?' : '&') . $queryString);
                } elseif (!empty($params)) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
                }
                break;

            default:
                $this->logger->error('Método HTTP no soportado', ['method' => $method]);
                return null;
        }

        // Configurar cabeceras
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        // Ejecutar la petición
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $duration = round((microtime(true) - $startTime) * 1000, 2); // en ms

        // Verificar si hubo errores
        if ($response === false) {
            $errorData = [
                'method' => $method,
                'url' => $url,
                'curl_error' => curl_error($ch),
                'curl_errno' => curl_errno($ch),
                'http_code' => $httpCode,
                'duration_ms' => $duration
            ];

            $this->logger->error('Error en petición HTTP', $errorData);
            curl_close($ch);
            return null;
        }

        // Cerrar cURL
        curl_close($ch);

        // Verificar el código HTTP
        if ($httpCode < 200 || $httpCode >= 300) {
            $this->logger->error('Error HTTP en petición', [
                'method' => $method,
                'url' => $url,
                'http_code' => $httpCode,
                'response' => $response,
                'duration_ms' => $duration
            ]);
            return null;
        }

        // Decodificar la respuesta
        $data = json_decode($response, true);

        // Verificar si la respuesta es válida
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->logger->error('Error al decodificar respuesta JSON', [
                'method' => $method,
                'url' => $url,
                'json_error' => json_last_error_msg(),
                'response_preview' => substr($response, 0, 255)
            ]);
            return null;
        }

        $this->logger->info('Petición HTTP completada', [
            'method' => $method,
            'url' => $url,
            'http_code' => $httpCode,
            'duration_ms' => $duration,
            'response_size' => strlen($response)
        ]);

        return $data;
    }
}
