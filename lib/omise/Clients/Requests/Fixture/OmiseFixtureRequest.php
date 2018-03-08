<?php

class OmiseTestClient extends OmiseAbstractRequest
{
    /**
     * @param string $method
     * @param string $url
     * @param array  $params
     */
    public function request($method, $url, $params = array())
    {
        return true;
    }
}
