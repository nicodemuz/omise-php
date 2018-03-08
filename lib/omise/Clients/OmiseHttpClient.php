<?php
require_once OMISE_LIB_PATH . '/Clients/OmiseClient.php';

class OmiseHttpClient extends OmiseClient
{
    /**
     * @var array
     */
    protected $allowedRequestMethods = array('GET', 'POST', 'PATCH', 'DELETE');

    /**
     * @param OmiseAbstractRequest
     */
    public function __construct(OmiseHttpAbstractRequest $requestor)
    {
        parent::__construct($requestor);
    }

    /**
     * @param string $method
     */
    public function isMethodAllowed($method)
    {
        return in_array(strtoupper($method), $this->allowedRequestMethods);
    }

    /**
     * @param string $method
     */
    public function isMethodNotAllowed($method)
    {
        return ! $this->isMethodAllowed($method);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $params
     */
    public function request($method, $url, $params = array())
    {
        if ($this->isMethodNotAllowed($method)) {
            // TODO :: Proper handle error case.
            throw new Exception('Method not allowed');
        }

        return $this->requestor->withCredential($this->credential . ':')
                               ->withOmiseApiVersion($this->omiseApiVersion)
                               ->withUserAgent(implode(' ', $this->userAgent))
                               ->request($method, $url, $params);
    }
}
