<?php
namespace Omise;

use Omise\Http\Response\Handler as ResponseHandler;
use Omise\HttpClient\ClientInterface;
use Omise\HttpClient\CurlClient;
use Exception;

class ApiRequestor
{
    /**
     * @var string
     */
    const OMISE_PHP_LIB_VERSION = '3.0.0-dev';
    const OMISE_API_URL         = 'https://api.omise.co/';
    const OMISE_VAULT_URL       = 'https://vault.omise.co/';

    /**
     * Request methods
     * @var string
     */
    const REQUEST_GET     = 'GET';
    const REQUEST_POST    = 'POST';
    const REQUEST_PATCH   = 'PATCH';
    const REQUEST_DELETE  = 'DELETE';
    const REQUEST_METHODS = array(self::REQUEST_GET, self::REQUEST_POST, self::REQUEST_PATCH, self::REQUEST_DELETE);

    /**
     * @var Omise\HttpClient\ClientInterface
     */
    private static $httpClient;

    /**
     * @param string $arguments[0]  An API endpoint
     * @param string $arguments[1]  Omise secret key
     * @param array  $arguments[2]  Parameters
     */
    public function __call($name, $arguments)
    {
        $requestMethodName = strtoupper($name);
        if (! in_array($requestMethodName, self::REQUEST_METHODS)) {
            throw new Exception('Request method "' . $requestMethodName . '" not supported.', 1);
        }

        return $this->request($arguments[0], $requestMethodName, $arguments[1], count($arguments) > 2 ? $arguments[2] : null);
    }

    /**
     * @param string $url
     * @param string $requestMethod
     * @param string $key
     * @param array  $params
     */
    public function request($url, $requestMethod, $key, $params = null)
    {
        $result = $this->httpClient()->request($url, $requestMethod, $key, $params);

        $responseHandler = new ResponseHandler;
        return $responseHandler->handle($result);
    }

    /**
     * Customize a httpClient class.
     *
     * @param \Omise\HttpClient\ClientInterface $httpClient
     */
    public static function setHttpClient(ClientInterface $httpClient)
    {
        self::$httpClient = $httpClient;
    }


    /**
     * @return \Omise\HttpClient\ClientInterface
     */
    protected function httpClient()
    {
        if (!self::$httpClient) {
            self::$httpClient = new CurlClient;
        }

        return self::$httpClient;
    }
}
