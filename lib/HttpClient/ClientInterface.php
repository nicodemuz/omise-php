<?php
namespace Omise\HttpClient;

interface ClientInterface
{
    /**
     * @param  string $url
     * @param  string $requestMethod
     * @param  array  $params
     *
     * @throws OmiseException
     *
     * @return string
     */
    public function request($url, $requestMethod, $key, $params);
}
