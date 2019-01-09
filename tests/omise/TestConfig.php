<?php

// Omise keys.
define('OMISE_PUBLIC_KEY', 'pkey');
define('OMISE_SECRET_KEY', 'skey');

// Include necessary file.
if (version_compare(phpversion(), '5.3.2') >= 0 && file_exists(dirname(__FILE__).'/../../vendor/autoload.php')) {
    require_once dirname(__FILE__).'/../../vendor/autoload.php';
} else {
    require_once dirname(__FILE__).'/../../lib/Omise.php';
}

require_once dirname(__FILE__) . '/../Utils/MockClient.php';

abstract class TestConfig extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		\Omise\ApiRequestor::setHttpClient(new MockClient);
	}
}
