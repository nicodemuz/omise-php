<?php
namespace Omise\HttpClient;

use Exception;

class CurlClient implements ClientInterface
{
    /**
     * Timeout setting
     * @var int
     */
    private $OMISE_CONNECTTIMEOUT = 30;
    private $OMISE_TIMEOUT        = 60;

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
        $ch = curl_init($url);

        curl_setopt_array($ch, $this->genOptions($requestMethod, $key.':', $params));

        // Make a request or thrown an exception.
        if (($result = curl_exec($ch)) === false) {
            $error = curl_error($ch);
            curl_close($ch);

            throw new Exception($error);
        }

        // Close.
        curl_close($ch);

        return $result;
    }

    /**
     * Creates an option for php-curl from the given request method and parameters in an associative array.
     *
     * @param  string $requestMethod
     * @param  array  $params
     *
     * @return array
     */
    private function genOptions($requestMethod, $userpassword, $params)
    {
        $certificateFileLocation = dirname(__FILE__) . '/../../data/ca_certificates.pem';
        $userAgent               = 'OmisePHP/' . \Omise\ApiRequestor::OMISE_PHP_LIB_VERSION . ' PHP/' . phpversion();

        $options = array(
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,       // Set the HTTP version to 1.1.
            CURLOPT_CUSTOMREQUEST  => $requestMethod,              // Set the request method.
            CURLOPT_RETURNTRANSFER => true,                        // Make php-curl returns the data as string.
            CURLOPT_HEADER         => false,                       // Do not include the header in the output.
            CURLINFO_HEADER_OUT    => true,                        // Track the header request string and set the referer on redirect.
            CURLOPT_AUTOREFERER    => true,
            CURLOPT_TIMEOUT        => $this->OMISE_TIMEOUT,        // Time before the request is aborted.
            CURLOPT_CONNECTTIMEOUT => $this->OMISE_CONNECTTIMEOUT, // Time before the request is aborted when attempting to connect.
            CURLOPT_USERPWD        => $userpassword,               // Authentication.
            CURLOPT_CAINFO         => $certificateFileLocation     // CA bundle.
        );

        // Config Omise API Version
        if (defined('OMISE_API_VERSION')) {
            $options += array(CURLOPT_HTTPHEADER => array('Omise-Version: ' . OMISE_API_VERSION));

            $userAgent .= ' OmiseAPI/' . OMISE_API_VERSION;
        }

        // Config UserAgent
        if (defined('OMISE_USER_AGENT_SUFFIX')) {
            $userAgent .= $userAgent . ' ' . OMISE_USER_AGENT_SUFFIX;
        }

        $options += array(CURLOPT_USERAGENT => $userAgent);

        // Also merge POST parameters with the option.
        if (is_array($params) && count($params) > 0) {
            $httpQuery = http_build_query($params);
            $httpQuery = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $httpQuery);

            $options += array(CURLOPT_POSTFIELDS => $httpQuery);
        }

        return $options;
    }
}
