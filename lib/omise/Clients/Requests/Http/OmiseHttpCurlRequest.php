<?php
class OmiseHttpCurlRequest extends OmiseHttpAbstractRequest
{
    protected $curlOptions = array();

    public function __construct()
    {
        $this->curlOptions += $this->getDefaultCurlOptions();
    }

    public function withCredential($credential)
    {
        parent::withCredential($credential);

        $this->curlOptions += array(CURLOPT_USERPWD => $this->credential);
        return $this;
    }

    public function withOmiseApiVersion($version)
    {
        parent::withOmiseApiVersion($version);

        $this->curlOptions += array(CURLOPT_HTTPHEADER => array('Omise-Version: ' . $this->omiseApiVersion));
        return $this;
    }

    public function withUserAgent($user_agent)
    {
        parent::withUserAgent($user_agent);

        $this->curlOptions += array(CURLOPT_USERAGENT => $this->userAgent);
        return $this;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $params
     */
    public function request($method, $url, $params = array())
    {
        if (is_array($params) && ! empty($params)) {
            $this->curlOptions += array(CURLOPT_POSTFIELDS => $this->buildHttpQuery($params));
        }

        $curl = curl_init($url);
        curl_setopt_array($curl, $this->curlOptions);

        $result = curl_exec($curl);
        $error  = curl_error($curl);
        curl_close($curl);


        return $result === false ? false : $result;


        // $curl = $this->curlRequest();

        // if ($curl['result'] === false) {
        //     throw new Exception($curl['error']);
        // }

        // return $curl['result'];

        // $result = curl_exec($curl);
        // if ($result === false) {
        //     $error = curl_error($curl);

        //     curl_close($curl);

        //     throw new Exception($error);
        // }

        // curl_close($curl);

        // return $result;
    }

    
    private function buildHttpQuery($params)
    {
        if (! is_array($params) || empty($params)) {
            return null;
        }

        return preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', http_build_query($params));
    }

    /**
     * @return array
     */
    private function getDefaultCurlOptions()
    {
        return array(
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,                             // Set the HTTP version to 1.1.
            CURLOPT_RETURNTRANSFER => true,                                              // Make php-curl returns the data as string.
            CURLOPT_HEADER         => false,                                             // Do not include the header in the output.
            CURLINFO_HEADER_OUT    => true,                                              // Track the header request string and set the referer on redirect.
            CURLOPT_AUTOREFERER    => true,
            CURLOPT_TIMEOUT        => $this->connecttimeout,                             // Time before the request is aborted.
            CURLOPT_CONNECTTIMEOUT => $this->timeout,                                    // Time before the request is aborted when attempting to connect.
            CURLOPT_CAINFO         => OMISE_LIB_PATH . '/../../data/ca_certificates.pem' // CA bundle.
        );
    }
}
