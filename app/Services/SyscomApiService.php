<?php
/**
 * Servicio para interactuar con la API de SYSCOM
 *
 * Este servicio maneja la autenticación y las peticiones a la API de SYSCOM
 *
 * @package app\Services
 */
namespace app\Services;

use app\Interfaces\SyscomApiServiceInterface;
use app\Interfaces\LoggerServiceInterface;

class SyscomApiService implements SyscomApiServiceInterface
{
    /**
     * URL base de la API de SYSCOM
     */
    private const API_BASE_URL = 'https://developers.syscom.mx/api/v1';

    /**
     * URL para obtener el token de acceso
     */
    private const TOKEN_URL = 'https://developers.syscom.mx/oauth/token';

    /**
     * Token de acceso actual
     *
     * @var string|null
     */
    private $accessToken = null;

    /**
     * Tiempo de expiración del token en timestamp
     *
     * @var int|null
     */
    private $tokenExpiration = null;

    /**
     * ID de cliente para la API
     *
     * @var string
     */
    private $clientId;

    /**
     * Secreto de cliente para la API
     *
     * @var string
     */
    private $clientSecret;

    /**
     * Servicio de logging
     *
     * @var LoggerServiceInterface
     */
    private $logger;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->clientId = SYSCOM_CLIENT_ID;
        $this->clientSecret = SYSCOM_CLIENT_SECRET;
        $this->logger = ServiceFactory::getLoggerService();
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken(): string
    {
        if (!$this->isTokenValid()) {
            $this->refreshToken();
        }

        return $this->accessToken ?? '';
    }

    /**
     * Verifica si el token actual es válido
     *
     * @return bool True si el token es válido, false en caso contrario
     */
    private function isTokenValid(): bool
    {
        // Si no hay token o no hay tiempo de expiración, no es válido
        if (empty($this->accessToken) || empty($this->tokenExpiration)) {
            return false;
        }

        // Si el token expira en menos de 5 minutos, considerarlo inválido
        return $this->tokenExpiration > (time() + 300);
    }

    /**
     * Refresca el token de acceso
     *
     * @return bool True si el token se refrescó correctamente, false en caso contrario
     */
    private function refreshToken(): bool
    {
        $this->logger->info('Refrescando token de SYSCOM API');

        $params = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::TOKEN_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || empty($response)) {
            $this->logger->error('Error al obtener token de SYSCOM API', [
                'http_code' => $httpCode,
                'response' => $response
            ]);
            return false;
        }

        $data = json_decode($response, true);

        if (empty($data['access_token']) || empty($data['expires_in'])) {
            $this->logger->error('Respuesta de token inválida', [
                'response' => $data
            ]);
            return false;
        }

        $this->accessToken = $data['access_token'];
        $this->tokenExpiration = time() + $data['expires_in'];

        $this->logger->info('Token de SYSCOM API obtenido correctamente', [
            'expires_in' => $data['expires_in']
        ]);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function request(string $method, string $endpoint, array $params = [], array $headers = []): ?array
    {
        $startTime = microtime(true);

        $this->logger->debug('Iniciando petición a SYSCOM API', [
            'method' => $method,
            'endpoint' => $endpoint,
            'params' => $params
        ]);

        // Asegurar que tenemos un token válido
        $token = $this->getAccessToken();
        if (empty($token)) {
            $this->logger->error('No se pudo obtener un token válido para la petición');
            return null;
        }

        // Preparar la URL
        $url = self::API_BASE_URL . '/' . ltrim($endpoint, '/');

        // Preparar los headers
        $defaultHeaders = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'Accept: application/json'
        ];

        $requestHeaders = array_merge($defaultHeaders, $headers);

        // Inicializar cURL
        $ch = curl_init();

        // Configurar la petición según el método
        switch (strtoupper($method)) {
            case 'GET':
                if (!empty($params)) {
                    $url .= '?' . http_build_query($params);
                }
                break;

            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
                break;

            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
                break;

            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if (!empty($params)) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
                }
                break;

            default:
                $this->logger->error('Método HTTP no soportado', ['method' => $method]);
                return null;
        }

        // Configurar opciones comunes
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Ejecutar la petición
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        $endTime = microtime(true);
        $duration = round(($endTime - $startTime) * 1000, 2); // en milisegundos

        // Si hay un error en la petición
        if ($httpCode < 200 || $httpCode >= 300 || $response === false) {
            $errorData = [
                'method' => $method,
                'endpoint' => $endpoint,
                'http_code' => $httpCode,
                'curl_error' => $curlError,
                'duration_ms' => $duration
            ];

            // Para errores 422 (Unprocessable Entity), intentar decodificar la respuesta para obtener más detalles
            if ($httpCode === 422 && !empty($response)) {
                $errorResponse = json_decode($response, true);
                if ($errorResponse !== null) {
                    $errorData['error_details'] = $errorResponse;
                } else {
                    $errorData['error_response'] = $response;
                }

                // Registrar información adicional para depuración
                $errorData['url'] = $url;
                $errorData['params'] = $params;
            }

            $this->logger->error('Error en petición a SYSCOM API', $errorData);
            return null;
        }

        // Decodificar la respuesta
        $data = json_decode($response, true);

        // Verificar si la respuesta es válida
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->logger->error('Error al decodificar respuesta JSON', [
                'method' => $method,
                'endpoint' => $endpoint,
                'json_error' => json_last_error_msg(),
                'response_preview' => substr($response, 0, 255)
            ]);
            return null;
        }

        $this->logger->info('Petición a SYSCOM API completada', [
            'method' => $method,
            'endpoint' => $endpoint,
            'http_code' => $httpCode,
            'duration_ms' => $duration,
            'response_size' => strlen($response)
        ]);

        return $data;
    }
}
