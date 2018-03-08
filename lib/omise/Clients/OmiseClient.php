<?php
require_once OMISE_LIB_PATH . '/Clients/Requests/OmiseAbstractRequest.php';
require_once OMISE_LIB_PATH . '/Clients/Requests/Fixture/OmiseFixtureRequest.php';
require_once OMISE_LIB_PATH . '/Clients/Requests/Http/OmiseHttpAbstractRequest.php';
require_once OMISE_LIB_PATH . '/Clients/Requests/Http/OmiseHttpCurlRequest.php';

class OmiseClient
{
    /**
     * @var string
     */
    const OMISE_PHP_LIB_VERSION = '2.10.0-dev';

    /**
     * @var string
     */
    protected $apiUrl   = 'https://api.omise.co';
    protected $vaultUrl = 'https://vault.omise.co';

    /**
     * @var string
     */
    protected $credential;

    /**
     * Its value can be one of these values
     *   2017-11-02
     *   2015-11-17
     *   2014-07-27
     *
     * @var string
     */
    protected $omiseApiVersion;

    /**
     * @var array
     */
    protected $userAgent = array();

    /**
     * @var OmiseAbstractRequest
     */
    protected $requestor;

    /**
     * @param OmiseAbstractRequest
     */
    public function __construct(OmiseAbstractRequest $requestor)
    {
        $this->userAgent = array(
            'OmisePHP/' . self::OMISE_PHP_LIB_VERSION,
            'PHP/' . phpversion()
        );

        $this->requestor = $requestor;
    }

    /**
     * @param string $credential
     */
    public function setCredential($credential)
    {
        $this->credential = $credential;
    }

    /**
     * @param string $version
     */
    public function setOmiseApiVersion($version)
    {
        $this->omiseApiVersion = $version;

        $this->setUserAgent('OmiseAPI/' . $this->omiseApiVersion);
    }

    /**
     * @param string|array $user_agent
     */
    public function setUserAgent($user_agent)
    {
        // Note, to support the previous user-agent setter behaviour.
        if (is_string($user_agent)) {
            $user_agent = preg_split('/\s+/', $user_agent);
        }

        if (is_array($user_agent)) {
            $this->userAgent = array_merge($this->userAgent, $user_agent);    
        }
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $params
     */
    public function request($method, $url, $params = array())
    {
        return $this->requestor->request($method, $url, $params);
    }
}
