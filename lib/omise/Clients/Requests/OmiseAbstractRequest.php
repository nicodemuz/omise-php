<?php

abstract class OmiseAbstractRequest
{
    /**
     * @var int
     */
    protected $connecttimeout = 30;
    protected $timeout        = 60;

    /**
     * @param string $method
     * @param string $url
     * @param array  $params
     */
    abstract public function request($method, $url, $params = array());
}
