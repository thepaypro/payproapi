<?php

namespace AppBundle\Service\ContisApiClient;

use Symfony\Component\HttpFoundation\Session\Session;
use GuzzleHttp\Client;
use DateTime;
use Exception;

/**
 * Class RequestService
 * @package AppBundle\Service\ContisApiClient
 */
class RequestService
{
    const CONTIS_TOKEN_KEY = 'payproapi.contis_authentication_token';
    const CONTIS_SECURITY_KEY = 'payproapi.contis_authentication_security_key';
    const TOKEN_EXPIRY_DATE_KEY = 'payproapi.contis_token_expiry_time';

    protected $contisApiHost;
    protected $contisSecretKey;
    protected $session;

    /**
     * @param String $contisSecretKey
     * @param String $contisApiHost
     */
    public function __construct(String $contisApiHost) {
        $this->contisApiHost = $contisApiHost;
        $this->httpClient = new Client();
    }

    /**
     * This method authenticates with Contis if needed and create the hash data string and hash required by contis to execute the call.
     * @param String $endpoint
     * @param Array $params
     * @param Array $requestParams
     * @param String $jsonParametersKey
     * @return Array
     */
    public function call(String $endpoint, Array $params, Array $requestParams = [], String $jsonParamtersKey = 'objInfo') : Array
    {
        $payload[$jsonParamtersKey] = $params;
        $payload['objReqInfo'] = $requestParams;

        $payload = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($endpoint != 'Login') {
            // dump($payload);die();
        }
        try {
            $response = $this->httpClient->request(
                'POST',
                $this->contisApiHost.$endpoint,
                [
                    'headers' => [
                        'Content-type' => 'application/json'
                    ],
                    'body' => $payload
                ]
            );
        } catch (Exception $e) {
            dump($e->getResponse()->getBody()->getContents());die();
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
