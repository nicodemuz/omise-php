<?php
namespace Omise\ApiCapabilities;

use Omise\Collection;
use Omise\Http\Response\Handler as ResponseHandler;
use Omise\Validator\Validator;

trait Listable
{
    /**
     * @param array|null $params
     */
    public static function all($params = null)
    {
        // var_dump(\OmiseCharge::retrieve());
        // 1. Set request url
        $endpoint = self::ENDPOINT;

        // 2. Make a request.
        $result = "{\"object\":\"list\",\"from\":\"1970-01-01T00:00:00Z\",\"to\":\"2018-11-07T08:55:34Z\",\"offset\":0,\"limit\":20,\"total\":45,\"order\":\"chronological\",\"location\":\"/charges\",\"data\":[{\"object\":\"charge\",\"id\":\"chrg_test_59trmbsjzzaplf1536g\"},{\"object\":\"charge\",\"id\":\"chrg_test_59trol3ieaclxo53y2l\"}]}";

        // 3. Validate a result, if it is in JSON format.
        if (! is_string($result) || ! json_decode($result)) {
            throw new \Exception('The response body is not in JSON format. (Bad Response)');
        }

        $result = json_decode($result, true);

        // 4. Validate a result, if it is not an error object.
        if ($result['object'] === 'error') {
            throw \OmiseException::getInstance($result);
        }

        // 5. Validate a result, if it is not a list object.
        if ($result['object'] !== 'list') {
            throw new \Exception('The response body is not in a list object. (Bad Response)');
        }

        // 6. Transform a response's JSON body to Omise\Collection object.
        // 7. Return Omise\Collection.
        return Collection::collect($result);
    }
}
