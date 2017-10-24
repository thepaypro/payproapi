<?php

namespace AppBundle\Service\BitcoinWalletApiClient

/**
 * Class HashingService
 * @package AppBundle\Service\BitcoinWalletApiClient
 */
class HashingService
{

    public function __construct() {
    }

    /**
     * Generates the HashId for the specified array.
     * @param  array  $params
     * @return array
     */
    public function generateHashId(Array $params)
    {
        $hashDataString = '';
        foreach ($params as $key => $param) {
            $combineOperator = $param === end($params) ? '' : '&';
            $stringParam = $param === 0 ? '' : $param;
            $hashDataString = $hashDataString.$stringParam.$combineOperator;
        }

        $params['HashId'] = sha1(base64_encode($hashDataString));

        return $params;
    }
}



