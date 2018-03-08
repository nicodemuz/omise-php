<?php

abstract class OmiseHttpAbstractRequest extends OmiseAbstractRequest
{
    /**
     * @var string
     */
    protected $credential;

    /**
     * @var string
     */
    protected $omiseApiVersion;

    /**
     * @var string
     */
    protected $userAgent;

    /**
     * @param string $credential
     */
    public function withCredential($credential)
    {
        $this->credential = $credential;
        return $this;
    }

    /**
     * @param string $version
     */
    public function withOmiseApiVersion($version)
    {
        $this->omiseApiVersion = $version;
        return $this;
    }

    /**
     * @param string $user_agent
     */
    public function withUserAgent($user_agent)
    {
        $this->userAgent = $user_agent;
        return $this;
    }
}
