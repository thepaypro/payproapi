<?php

namespace AppBundle\Service\Ethereum\Methods;

use Graze\GuzzleHttp\JsonRpc\Client;

abstract class AbstractMethods
{
    protected $client;

    /*
     * @param string $ethereumHost
     */
    public function __construct(string $ethereumHost)
    {
        $this->client = Client::factory($ethereumHost);
    }
}
