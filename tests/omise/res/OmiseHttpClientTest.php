<?php
require_once __DIR__ . '/../TestConfig.php';

class OmiseHttpClientTest extends TestConfig
{
    /**
     * @test
     */
    public function initiate_omise_client_with_its_specific_client_class()
    {
        $this->assertInstanceOf('OmiseClient', new OmiseHttpClient(new OmiseHttpCurlRequest));
    }

    /**
     * @test
     */
    public function set_credential()
    {
        $client = new OmiseHttpClient(new OmiseDumpRequest);
        $client->setCredential('my_secret_credential');

        $this->assertEquals('my_secret_credential:', $client->request('GET', 'https://api.omise.co/charges'));
    }
}

class OmiseDumpRequest extends OmiseHttpAbstractRequest
{
    public function request($method, $url, $params = array())
    {
        return $this->credential;
    }
}