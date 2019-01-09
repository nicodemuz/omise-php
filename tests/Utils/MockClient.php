<?php
use Omise\HttpClient\ClientInterface;
use Omise\HttpClient\CurlClient;

class MockClient implements ClientInterface
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
    public function request($url, $requestMethod, $key, $params = null)
    {
        // Extract only hostname and URL path without trailing slash.
        $parsed      = parse_url($url);
        $requestUrl = $parsed['host'] . rtrim($parsed['path'], '/');

        // Convert query string into filename friendly format.
        if (!empty($parsed['query'])) {
            $query      = base64_encode($parsed['query']);
            $query      = str_replace(array('+', '/', '='), array('-', '_', ''), $query);
            $requestUrl = $requestUrl . '-' . $query;
        }

        // Finally.
        $requestUrl = dirname(__FILE__) . '/../fixtures/' . $requestUrl . '-' . strtolower($requestMethod) . '.json';

        // Make a request from Curl if json file was not exists.
        if (!file_exists($requestUrl)) {
            // Get a directory that's file should contain.
            $request_dir = explode('/', $requestUrl);
            unset($request_dir[count($request_dir) - 1]);
            $request_dir = implode('/', $request_dir);

            // Create directory if it not exists.
            if (! file_exists($request_dir)) {
                mkdir($request_dir, 0777, true);
            }

            $curlClient = new CurlClient;
            $result = $curlClient->request($url, $requestMethod, $key, $params);

            $f = fopen($requestUrl, 'w');
            if ($f) {
                fwrite($f, $result);
                fclose($f);
            }
        } else {
            // Or get response from json file.
            $result = file_get_contents($requestUrl);
        }

        return $result;
    }
}
