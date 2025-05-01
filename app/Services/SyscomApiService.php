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

class SyscomApiService extends BaseApiService implements SyscomApiServiceInterface
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
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->apiBaseUrl = self::API_BASE_URL;
        $this->clientId = SYSCOM_CLIENT_ID;
        $this->clientSecret = SYSCOM_CLIENT_SECRET;
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
        // Asegurar que tenemos un token válido
        $token = $this->getAccessToken();
        if (empty($token)) {
            $this->logger->error('No se pudo obtener un token válido para la petición');
            return null;
        }

        // Preparar la URL
        $url = $this->apiBaseUrl . '/' . ltrim($endpoint, '/');

        // Preparar los headers
        $defaultHeaders = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'Accept: application/json'
        ];

        $requestHeaders = array_merge($defaultHeaders, $headers);

        // Usar el método de la clase base para hacer la petición HTTP
        return $this->makeHttpRequest($method, $url, $params, $requestHeaders);
    }
}
